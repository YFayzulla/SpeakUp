<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <style>
        /* Base sizing tuned for 58mm thermal printers */
        @page {
            size: 58mm;
            margin: 0;
        }
        html, body {
            padding: 0;
            margin: 0;
            background: #fff;
        }
        body {
            width: 58mm; /* hard cap for 58mm paper */
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 12px; /* readable on 58mm */
            line-height: 1.2;
            color: #000;
        }
        .receipt {
            box-sizing: border-box;
            width: 58mm;
            padding: 3mm 2mm; /* tiny inner padding, printer margins are 0 */
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: 700; }
        .muted { color: #111; }
        .mt-4 { margin-top: 4px; }
        .mt-8 { margin-top: 8px; }
        .mt-12 { margin-top: 12px; }
        .divider {
            margin: 6px 0;
            border-top: 1px dashed #000;
        }
        .kv {
            display: flex;
            justify-content: space-between;
            gap: 6px;
        }
        .title {
            font-size: 13px;
            letter-spacing: .5px;
        }
        .footer-note { font-size: 11px; }
        .btns {
            display: flex;
            gap: 8px;
            padding: 8px;
            justify-content: center;
        }
        .btn {
            border: 1px solid #999;
            background: #f6f6f6;
            padding: 6px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        .no-print { display: block; }
        @media print {
            .no-print { display: none !important; }
            .btns { display: none !important; }
        }
    </style>
</head>
<body>
<div class="receipt" id="receiptRoot">
    <div class="center">
        <div class="bold title">{{ config('app.name', 'SpeakUp') }}</div>
        <div class="muted">Payment Receipt</div>
    </div>

    <div class="divider"></div>

    <div class="kv"><span>ID</span><span class="bold">#{{ $payment->id }}</span></div>
    <div class="kv"><span>Date</span><span>{{ \Carbon\Carbon::parse($payment->date)->format('Y-m-d') }}</span></div>

    <div class="divider"></div>

    <div class="kv"><span>Student</span><span class="right">{{ $student->name }}</span></div>
    @if(!empty($payment->group))
        <div class="kv"><span>Group</span><span class="right">{{ $payment->group }}</span></div>
    @endif

    <div class="divider"></div>

    <div class="kv"><span>Amount</span><span class="bold right">
        {{ number_format($payment->payment, 0, '.', ' ') }}
    </span></div>
    <div class="kv"><span>Method</span><span class="right">{{ ucfirst($payment->type_of_money) }}</span></div>

    @php
        $monthlyDept = isset($dept) ? (float)$dept : (isset($student->deptStudent->dept) ? (float)$student->deptStudent->dept : (float)($student->should_pay ?? 0));
    @endphp
    @if($monthlyDept > 0)
        <div class="kv"><span>Monthly Fee</span><span class="right">{{ number_format($monthlyDept, 0, '.', ' ') }}</span></div>
    @endif

    @php
        $partialPaid = isset($student->deptStudent) ? (float)($student->deptStudent->payed ?? 0) : 0;
        $remainingToComplete = max(0, $monthlyDept - $partialPaid);
    @endphp
    @if($partialPaid > 0)
        <div class="kv"><span>Paid (partial)</span><span class="right">{{ number_format($partialPaid, 0, '.', ' ') }}</span></div>
        <div class="kv"><span>Next to pay</span><span class="bold right">{{ number_format($remainingToComplete, 0, '.', ' ') }}</span></div>
    @endif

    @if(isset($student->status) && $student->status < 0)
        <div class="kv"><span>Debt</span><span class="right">{{ abs($student->status) }} month(s)</span></div>
    @endif

    <div class="divider"></div>

    <div class="center footer-note mt-8">
        Thank you!<br>
        Keep this receipt for your records.
    </div>
</div>

<div class="btns no-print">
    <button class="btn" onclick="window.print()">Print</button>
    <button class="btn" onclick="window.close()">Close</button>
</div>

<script>
    // Optional: Auto-print when opened from the admin page
    // Uncomment if you want it to immediately open the print dialog
    // window.addEventListener('load', function() { window.print(); });
</script>
</body>
</html>