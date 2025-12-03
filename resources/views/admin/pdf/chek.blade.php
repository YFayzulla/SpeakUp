<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .receipt {
            width: 320px;
            background: white;
            padding: 15px;
            border: 1px solid #000;
            text-align: center;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
        }

        .subtitle {
            font-size: 14px;
        }

        .info {
            font-size: 12px;
            text-align: left;
            margin-top: 10px;
        }

        .bold {
            font-weight: bold;
            font-size: 14px;
        }

        .status {
            background: black;
            color: white;
            padding: 5px;
            font-weight: bold;
            margin-top: 10px;
        }

        .btn {
            margin-top: 20px;
            padding: 8px 15px;
            font-size: 14px;
            background: blue;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="receipt">
    <div class="title">Speak Up</div>
    <div class="subtitle">ООО «Learning Center»</div>
    <div class="info">
        Karavan shopping 4 floor <br>
        ДАТА {{$payment -> created_at->format('d-m-y')}} ВРЕМЯ {{$payment->created_at->format('H:i:s')}} <br>
        Payment type: {{$payment->type_of_money}} <br>
        Payment ID: {{$payment->id}}<br><br>

        <span class="bold">Payment</span> <br>
        Student : {{$student -> name}} <br>
        Phone Number : {{$student->phone}}<br>
        Group: {{$student->group->name}} <br>
        Teacher: {{$student->group->teacher->name}}<br>

        amount of payment: {{number_format($payment->payment,0,' ',' ')}} sum <br>
        @php
            $dept =  $payment->payment - $student->should_pay;
            if($dept > 0){
             echo 'student has'.$dept.'dept';
        }
        @endphp
        <br><br>
        🌟 Education is not an expense; it's the best investment in your future! 🌟
        <br>

    </div>

    <div class="status">ОТКАЗАНО</div>

    <button class="btn" onclick="window.print()">Печать</button>
</div>

</body>
</html>
