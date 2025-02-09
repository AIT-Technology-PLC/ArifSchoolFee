<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            width: 70%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #e2e2e2;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .header {
            text-align: center;
            color: #ffffff;;
            background-color: #6079ca;
            padding: 3px;
            border-radius: 5px;
        }
        .content {
            padding: 20px 20px 20px 20px;
            background-color: #f7f5f5;
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
            <h2>{{ $subject }}</h2>
        </div>
        <div class="content">
            <p>Dear {{$receiver}},</p>
            <p>{!! nl2br($message_content) !!}</p>
        </div>
        <div class="footer">
            <p>&copy; AIT Technology. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
