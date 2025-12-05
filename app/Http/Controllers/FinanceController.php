<?php

namespace App\Http\Controllers;

use App\Http\Requests\Finance\StoreRequest;
use App\Http\Requests\Finance\UpdateRequest;
use App\Models\Finance;
use Carbon\Carbon;

class FinanceController extends Controller
{
    public function index()
    {
        $finances = Finance::all();
        $totalConsumption = $finances->sum('payment');
        $dailyConsumption = $finances->where('created_at', '>=', Carbon::today())->sum('payment');

        return view('admin.finance.index', [
            'finances' => $finances,
            'consumption' => $totalConsumption,
            'daily_consumption' => $dailyConsumption
        ]);
    }

    public function store(StoreRequest $request)
    {
        $validatedData = $request->validated();
        Finance::create($validatedData);

        return redirect()->back()->with('success', 'Xarajat muvaffaqiyatli qo\'shildi.');
    }

    public function update(UpdateRequest $request, $id)
    {
        $validatedData = $request->validated();
        $finance = Finance::findOrFail($id);
        $finance->update($validatedData);

        return redirect()->back()->with('success', 'Xarajat muvaffaqiyatli yangilandi.');
    }

    public function destroy($id)
    {
        $finance = Finance::findOrFail($id);
        $finance->delete();

        return redirect()->back()->with('success', 'Xarajat muvaffaqiyatli o\'chirildi.');
    }
}
