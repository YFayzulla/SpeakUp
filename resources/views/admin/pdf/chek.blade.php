<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Monthly Payment Receipt</title>

    <style>
        /* --- RESET & GLOBAL --- */
        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            background: #fff;
            color: #000;
            font-family: "Times New Roman", Times, serif;
            font-size: 10px;
            line-height: 1.2;
        }

        /* --- RECEIPT CONTAINER --- */
        .receipt {
            width: 100%;
            max-width: 58mm;
            margin: 0 auto;
            padding: 2mm 3mm;
            background: #fff;
        }

        .bold {
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .uppercase {
            text-transform: uppercase;
        }

        /* --- HEADER & LOGO --- */
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2px;
        }

        .logo-img {
            height: 22px;
            width: auto;
            margin-right: 3px;
        }

        .logo-text {
            font-family: sans-serif;
            font-weight: 800;
            font-size: 18px;
            letter-spacing: -0.5px;
            color: #000;
        }

        .sub-header {
            text-align: center;
            font-size: 9px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .receipt-title-box {
            border: 1.5px solid #4a934a;
            border-radius: 4px;
            padding: 3px 0;
            text-align: center;
            margin: 0 0 10px 0;
            width: 100%;
        }

        .receipt-title-text {
            font-weight: 800;
            font-size: 10px;
            text-transform: uppercase;
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 4px;
        }

        .label {
            text-align: left;
            flex-shrink: 0;
            padding-right: 5px;
        }

        .value {
            text-align: right;
            font-weight: bold;
            white-space: normal;
            max-width: 65%;
        }

        .indent-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 4px;
        }

        .indent-label {
            padding-left: 20px;
            font-weight: bold;
            font-size: 9px;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 6px 0;
            width: 100%;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        @media print {
            @page {
                size: 58mm auto;
                margin: 0;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .receipt {
                width: 58mm;
                box-shadow: none;
                margin: 0;
                page-break-inside: avoid;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>

    @if(!request()->boolean('embed'))
        <script>
            window.addEventListener('load', function () {
                setTimeout(function () {
                    window.print();
                }, 500);
            });
        </script>
    @endif

    <?php
// --- DATA PREP ---
    $studentName = $student->name ?? ($payment->name ?? 'Unknown Student');
    $roomName = $student->groups->first()?->room?->room ?? 'Unknown Room';
    $courseName = $payment->group ?? ($student->groups->pluck('name')->implode(', ') ?: 'IELTS/CEFR/GEN.ENG');

    $methodRaw = strtolower($payment->type_of_money ?? 'cash');
    $method = $methodRaw === 'electronic' ? 'CARD' : 'CASH';


    $amount = (float)($payment->payment ?? 0);
    $monthlyBase = (float)($dept ?? ($student->deptStudent->dept ?? $student->should_pay ?? 0));
    $partialPaid = (float)data_get($student, 'deptStudent.payed', 0);
    $remainingToPay = $partialPaid > 0 ? max(0, $monthlyBase - $partialPaid) : 0;

    // Dates
    $createdAt = $payment->created_at ?? now();
    $dateDisplay = ($payment->date ?? $createdAt->format('Y-m-d'));
    $payDateObj = \Carbon\Carbon::parse($dateDisplay);

    $printDate = $payDateObj->format('d.m.Y');
    $printTime = $createdAt->format('H:i');

    // --- LOGIC FOR PERIOD (FROM/TO) ---
    // We anchor the "Day" to the student's registration date
    $registrationDate = $student->created_at ?? $createdAt;
    $billingDay = $registrationDate->day;

    // Period From: Take the Month/Year of payment, but the Day of registration
    // We use "subMonths(0)" trick or explicit creation to avoid Carbon overflow bugs
    $periodFromObj = \Carbon\Carbon::create($payDateObj->year, $payDateObj->month, $billingDay);

    // If registration was on 31st but payment is in June (30 days),
    // Carbon automatically adjusts to June 30.

    $periodFrom = $periodFromObj->format('d/m/Y');
    $periodTo = $periodFromObj->copy()->addMonth()->format('d/m/Y');

    $debtMonths = isset($student->status) && $student->status < 0 ? abs((int)$student->status) : 0;
    ?>
</head>

<body>

<div class="receipt">
    <div class="logo-container">
        <img src="/logos/SymbolRed.svg" class="logo-img" alt="S">
        <span class="logo-text">SpeakUp</span>
    </div>
    <div class="sub-header">LEARNING CENTER</div>

    <div class="receipt-title-box">
        <span class="receipt-title-text">MONTHLY PAYMENT RECEIPT</span>
    </div>

    <div class="row">
        <div>Date: <span class="bold">{{ $printDate }}</span></div>
        <div>Time: <span class="bold">{{ $printTime }}</span></div>
    </div>

    <div class="line"></div>

    <div class="row">
        <div class="label">STUDENT:</div>
        <div class="value uppercase">{{ $studentName }}</div>
    </div>
    <div class="row">
        <div class="label">ROOM:</div>
        <div class="value uppercase">{{ $roomName }}</div>
    </div>
    <div class="row">
        <div class="label">COURSE:</div>
        <div class="value uppercase">{{ $courseName }}</div>
    </div>
    <div class="row">
        <div class="label">ASSIGNED:</div>
        <div class="value">{{ $registrationDate->format('d/m/Y') }}</div>
    </div>

    <div class="line"></div>

    <div class="row">
        <div class="label">MONTHLY FEE:</div>
        <div class="value">{{ number_format($monthlyBase, 0, '.', ' ') }}</div>
    </div>

    <div class="row" style="margin-top: 5px; margin-bottom: 2px;">
        <div class="label" style="font-weight:bold; font-size: 9px;">PERIOD COVERED:</div>
    </div>

    <div class="indent-row">
        <div class="indent-label">FROM:</div>
        <div class="value">{{ $periodFrom }}</div>
    </div>
    <div class="indent-row">
        <div class="indent-label">TO:</div>
        <div class="value">{{ $periodTo }}</div>
    </div>

    <div class="line"></div>

    <div class="row">
        <div class="label">DEBT:</div>
        <div class="value">{{ $debtMonths }} MONTH(S)</div>
    </div>
    <div class="row">
        <div class="label">METHOD:</div>
        <div class="value uppercase">{{ $method }}</div>
    </div>
    <div class="row">
        <div class="label">REST TO PAY:</div>
        <div class="value">{{ number_format($remainingToPay, 0, '.', ' ') }}</div>
    </div>

    <div class="line" style="border-top: 2px solid #000;"></div>

    <div class="row">
        <div class="label bold" style="font-size: 14px;">PAID:</div>
        <div class="value bold" style="font-size: 14px;">{{ number_format($amount, 0, '.', ' ') }}</div>
    </div>

    <div class="line" style="border-top: 1px solid #000; margin-top: 10px;"></div>


    <div class="footer">
        <div class="bold" style="margin-bottom: 4px;">THANK YOU!</div>
        <div class="row" style="justify-content: center;">
            <div class="center">
                <div class="bold">SPEAKUP ADMIN</div>
                <div class="bold">99 968 11 77</div>
            </div>
        </div>
    </div>

    <div style="height: 10px;"></div>

    <div class="center no-print" style="margin-top: 20px; padding-top: 10px; border-top: 1px dashed #ccc;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Print Receipt</button>
    </div>
</div>

</body>
</html>
