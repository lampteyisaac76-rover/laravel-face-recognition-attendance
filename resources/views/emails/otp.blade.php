<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #003366;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 1px;
        }
        .content {
            padding: 40px;
            text-align: center;
            color: #333333;
        }
        .content h2 {
            color: #003366;
            margin-bottom: 10px;
        }
        .content p {
            color: #555555;
            line-height: 1.6;
        }
        .code-box {
            background-color: #f0f4f8;
            border-radius: 8px;
            padding: 20px 40px;
            margin: 30px auto;
            display: inline-block;
            border: 2px dashed #003366;
        }
        .otp-code {
            font-size: 36px;
            font-weight: 800;
            color: #003366;
            letter-spacing: 6px;
            margin: 0;
        }
        .warning {
            background-color: #fff8e1;
            border-left: 4px solid #f9a825;
            padding: 12px 16px;
            margin: 20px 0;
            text-align: left;
            border-radius: 4px;
            font-size: 13px;
            color: #6d4c00;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #777777;
            border-top: 1px solid #eeeeee;
        }
        .footer p {
            margin: 4px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>GCTU.</h1>
        </div>

        <div class="content">
            <h2>Account Verification</h2>
            <p>
                Thank you for registering on the GCTU Face Recognition Attendance System.
                Use the verification code below to activate your account:
            </p>

            <div class="code-box">
                <p class="otp-code">{{ $code }}</p>
            </div>

            <div class="warning">
                ⚠️ This code will expire in <strong>10 minutes</strong>.
                If you did not request this, please ignore this email or
                contact your system administrator immediately.
            </div>

            <p style="font-size: 13px; color: #888;">
                Do not share this code with anyone.
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Ghana Communication Technology University</p>
            <p>Face Recognition Attendance Management System</p>
        </div>
    </div>
</body>
</html>