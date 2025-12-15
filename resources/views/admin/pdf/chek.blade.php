<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Receipt</title>
    <style>
        /* Receipt width ~ 80mm */
        html, body {
            margin: 0;
            padding: 0;
            background: #fff;
            color: #000;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 12px;
            line-height: 1.35;
        }
        .receipt {
            width: 80mm;
            max-width: 100%;
            margin: 0 auto;
            padding: 12px 10px;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .muted { color: #555; }
        h1, h2, h3, h4, h5, h6 { margin: 0; padding: 0; font-weight: 600; }
        .brand { font-size: 20px; font-weight: 800; letter-spacing: 1.2px; text-transform: uppercase; }
        .tagline { margin-top: 2px; font-size: 11px; letter-spacing: .4px; }
        .title { margin-top: 6px; font-size: 13px; }
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        .row { display: flex; justify-content: space-between; gap: 6px; }
        .label { color: #000; }
        .value { font-weight: 600; }
        .total { font-size: 14px; font-weight: 700; }
        .footer { margin-top: 10px; text-align: center; }
        .small { font-size: 11px; }
        .badge { display:inline-block; padding: 2px 6px; border: 1px solid #000; border-radius: 3px; font-weight: 700; font-size: 11px; letter-spacing: .6px; }
        @media print {
            @page { size: 80mm; margin: 0; }
            body { margin: 0; }
            .no-print { display: none !important; }
        }
    </style>
    @if(!request()->boolean('embed'))
    <script>
        // Auto print on load and close after printing (direct open only)
        window.addEventListener('load', function () {
            setTimeout(function(){
                window.print();
                try { window.close(); } catch (e) {}
            }, 150);
        });
    </script>
    @endif
    <?php
        use Carbon\Carbon;
        // Force brand name to Speak Up
        $appName = 'Speak Up';
        $studentName = $student->name ?? ($payment->name ?? '');
        $groupName = $payment->group ?? ($student->group->name ?? '');
        $method = ucfirst($payment->type_of_money ?? '');
        $amount = $payment->payment ?? 0;
        $monthly = $dept ?? null;
        $dateStr = $payment->date ?? optional($payment->created_at)->format('Y-m-d');
        $timeStr = optional($payment->created_at)->format('H:i');
        $operator = optional(auth()->user())->name;
    ?>
</head>
<body>
<div class="receipt">
    <div class="center">
        <div class="brand">SPEAK UP</div>
        <div class="tagline muted">Language Center</div>
        <div class="title" style="margin-top:6px;">
            <span class="badge">PAYMENT RECEIPT</span>
        </div>
        <div class="small muted">{{ $dateStr }} @if($timeStr) {{ $timeStr }} @endif</div>
    </div>

    <div class="divider"></div>

    <div class="row small">
        <div class="label">Student</div>
        <div class="value">{{ $studentName }}</div>
    </div>
    @if($groupName)
    <div class="row small">
        <div class="label">Group</div>
        <div class="value">{{ $groupName }}</div>
    </div>
    @endif
    @if(!is_null($monthly))
    <div class="row small">
        <div class="label">Monthly fee</div>
        <div class="value">{{ number_format($monthly, 0, '.', ' ') }}</div>
    </div>
    @endif

    <div class="row small">
        <div class="label">Method</div>
        <div class="value">{{ $method }}</div>
    </div>

    <div class="divider"></div>

    <div class="row total">
        <div class="label">PAID</div>
        <div class="value">{{ number_format($amount, 0, '.', ' ') }}</div>
    </div>

    <div class="divider"></div>

    <div class="footer small">
        @if($operator)
            <div>Operator: {{ $operator }}</div>
        @endif
        <div class="muted">Thank you!</div>
        <div class="muted">#{{ $payment->id }}</div>
    </div>

    <div class="center no-print" style="margin-top:8px;">
        <button onclick="window.print()">Print</button>
    </div>
</div>
</body>
</html>