<?php

namespace App\Services;

use App\Models\DeptStudent;
use App\Models\HistoryPayments;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentReversalService
{
    /**
     * Reverse a payment and restore user's status
     *
     * @param int $paymentId The ID of the payment to reverse
     * @param string|null $reason Reason for reversal
     * @return array ['success' => bool, 'message' => string]
     */
    public function reversePayment(int $paymentId, ?string $reason = null): array
    {
        try {
            return DB::transaction(function () use ($paymentId, $reason) {
                // Find the payment to reverse
                $originalPayment = HistoryPayments::findOrFail($paymentId);

                // Check if already reversed
                if ($originalPayment->is_reversed) {
                    return [
                        'success' => false,
                        'message' => 'This payment has already been reversed.'
                    ];
                }

                $user = User::findOrFail($originalPayment->user_id);
                $deptStudent = DeptStudent::where('user_id', $user->id)->first();

                if (!$deptStudent) {
                    return [
                        'success' => false,
                        'message' => 'Student payment record not found.'
                    ];
                }

                // Calculate the reversal amount
                $reversalAmount = $originalPayment->payment;

                // Create the reversal payment record (negative)
                $reversalPayment = HistoryPayments::create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'payment' => -$reversalAmount,
                    'group' => $originalPayment->group,
                    'date' => Carbon::now()->format('Y-m-d'),
                    'type_of_money' => $originalPayment->type_of_money,
                    'is_reversed' => false,
                    'reversed_by_id' => null,
                ]);

                // Mark original payment as reversed
                $originalPayment->update([
                    'is_reversed' => true,
                    'reversed_by_id' => $reversalPayment->id,
                ]);

                // Now reverse the payment logic effects
                $this->reversePaymentEffects($deptStudent, $user, $reversalAmount);

                return [
                    'success' => true,
                    'message' => 'Payment reversed successfully. Student status restored.',
                    'reversal_id' => $reversalPayment->id,
                ];
            });
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error reversing payment: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Reverse the effects of a payment on student's DeptStudent record and User status
     *
     * @param DeptStudent $deptStudent
     * @param User $user
     * @param float $reversalAmount
     * @return void
     */
    private function reversePaymentEffects(DeptStudent $deptStudent, User $user, float $reversalAmount): void
    {
        $monthlyDept = $deptStudent->dept;
        $remainingReversal = $reversalAmount;

        // If we're reversing a payment that completed partial months, we need to undo that
        // Start from the most recent state and work backwards

        if ($monthlyDept > 0) {
            // Check if there are full months paid that we need to reverse
            $fullMonthsToReverse = floor($remainingReversal / $monthlyDept);
            
            if ($fullMonthsToReverse > 0) {
                $deptStudent->status_month -= $fullMonthsToReverse;
                $user->status -= $fullMonthsToReverse;
                $remainingReversal -= ($fullMonthsToReverse * $monthlyDept);
            }
        }

        // Handle remaining reversal amount (partial month)
        if ($remainingReversal > 0) {
            if ($deptStudent->payed > 0) {
                // If there was a partial payment, we need to check if this reversal applies to it
                if ($remainingReversal >= $deptStudent->payed) {
                    // The reversal covers the partial payment
                    $remainingReversal -= $deptStudent->payed;
                    $deptStudent->payed = 0;
                    
                    // Undo the month increase if applicable
                    if ($remainingReversal > 0 && $monthlyDept > 0 && $remainingReversal >= $monthlyDept) {
                        $deptStudent->status_month--;
                        $user->status--;
                    }
                } else {
                    // The reversal only partially reverses the partial payment
                    $deptStudent->payed -= $remainingReversal;
                    $remainingReversal = 0;
                }
            }
        }

        // Save the changes
        $deptStudent->save();
        $user->save();
    }
}
