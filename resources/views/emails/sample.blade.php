<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            width: 60%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #e2e2e2;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .header {
            text-align: center;
            color: #ffffff;;
            background-color: #669900;
            padding: 10px;
            border-radius: 5px;
        }
        .content {
            padding: 20px 20px 20px 20px;
            background-color: #ffffff;
        }
        .footer {
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Payment Confirmation</h1>
        </div>
        <div class="content">
            <p>Dear {{ $name }},</p>
            <p>We are pleased to inform you that we have received your payment for the following:</p>
            <ul>
                <li><strong>Amount:</strong> ${{ $amount }} Birr</li>
                <li><strong>Payment Date:</strong> {{ $paymentDate }}</li>
                <li><strong>Transaction ID:</strong> {{ $transactionId }}</li>
            </ul>
            <p>{{ $Message }} To download your payment information, please click this link: {{ $link }} </p>
            <p>{{ $schoolName }}</p> 

        </div>
        <div class="footer">
            <p>&copy; {{ $schoolYear }} AIT Technology. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
