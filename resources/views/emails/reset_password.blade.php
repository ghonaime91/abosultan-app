<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعادة تعيين كلمة المرور</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            font-size: 24px;
            text-align: center;
            color: #333;
        }

        p {
            font-size: 16px;
            color: #555;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }

        input[type="password"] {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="password"]:focus {
            border-color: #007BFF;
            outline: none;
        }

        button {
            padding: 12px;
            background-color: #007BFF;
            border: none;
            color: #fff;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            color: #888;
        }

        .footer a {
            color: #007BFF;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>إعادة تعيين كلمة المرور</h1>
        <p>مرحبًا،</p>
        <p>لقد طلبت إعادة تعيين كلمة المرور الخاصة بك. الرجاء ملء الحقول أدناه لإعادة تعيين كلمة المرور:</p>
        
        <!-- Form to reset password -->
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">
            
            <div>
                <label for="password">الباسوورد الجديد</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div>
                <label for="password_confirmation">تأكيد الباسوورد الجديد</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div>
                <button type="submit">إعادة تعيين كلمة المرور</button>
            </div>
        </form>

        <p class="footer">إذا لم تطلب إعادة تعيين كلمة المرور، يرجى تجاهل هذا البريد الإلكتروني.</p>
        <p class="footer">شكرًا،</p>
        <p class="footer">فريق الدعم</p>
    </div>
</body>
</html>
