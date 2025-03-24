<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>کد تأیید ایمیل</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }

        .code {
            font-size: 24px;
            font-weight: bold;
            color: #3498db;
            padding: 10px;
            border: 2px dashed #3498db;
            display: inline-block;
            margin-top: 10px;
        }

        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>کد تأیید ایمیل شما</h2>
        <p>از کد زیر برای تأیید ایمیل خود استفاده کنید:</p>
        <div class="code">{{ $verificationCode }}</div>
        <p class="footer">اگر این ایمیل را اشتباهی دریافت کرده‌اید، لطفاً آن را نادیده بگیرید.</p>
    </div>
</body>

</html>
