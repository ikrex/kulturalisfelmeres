<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Új kapcsolati űrlap üzenet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #f9f9f9;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
        }
        h1 {
            color: #4F5D75;
            border-bottom: 2px solid #BF4B75;
            padding-bottom: 10px;
        }
        .content {
            margin-top: 20px;
            background: white;
            padding: 15px;
            border-radius: 3px;
        }
        .field {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
            color: #4F5D75;
        }
        .message {
            white-space: pre-wrap;
            background: #f5f5f5;
            padding: 15px;
            border-left: 4px solid #BF4B75;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Új üzenet a kapcsolati űrlapról</h1>

        <div class="content">
            <div class="field">
                <div class="label">Név:</div>
                <div>{{ $name }}</div>
            </div>

            <div class="field">
                <div class="label">E-mail:</div>
                <div>{{ $email }}</div>
            </div>

            <div class="field">
                <div class="label">Tárgy:</div>
                <div>{{ $subject }}</div>
            </div>

            <div class="field">
                <div class="label">Üzenet:</div>
                <div class="message">{{ $message }}</div>
            </div>
        </div>

        <div class="footer">
            <p>Ezt az e-mailt a kulturaliskutatas.hu oldalon keresztül küldték.</p>
        </div>
    </div>
</body>
</html>
