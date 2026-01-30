<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: #4285f4; color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .content { padding: 30px; }
        .content h2 { color: #333; }
        .content p { color: #666; line-height: 1.6; }
        .button { display: inline-block; background: #4285f4; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .info-box { background: #f9f9f9; padding: 15px; border-left: 4px solid #4285f4; margin: 20px 0; }
        .footer { background: #f4f4f4; padding: 20px; text-align: center; color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Selamat Datang!</h1>
        </div>
        
        <div class="content">
            <h2>Halo {{ $customer->firstname }}!</h2>
            
            <p>Terima kasih sudah bergabung dengan <strong>{{ config('app.name') }}</strong> menggunakan akun Google Anda.</p>
            
            <div class="info-box">
                <strong>📧 Email:</strong> {{ $customer->email }}<br>
                <strong>👤 Username:</strong> {{ $customer->username }}<br>
                <strong>📅 Terdaftar:</strong> {{ now()->format('d F Y, H:i') }}
            </div>
            
            <p>Akun Anda telah berhasil dibuat! Silakan verifikasi email Anda dengan mengklik tombol di bawah ini:</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/customer/email/verify/' . $customer->id . '/' . sha1($customer->email)) }}" class="button">
                    ✅ Verifikasi Email Saya
                </a>
            </div>
            
            <p>Atau copy link ini ke browser Anda:</p>
            <p style="word-break: break-all; color: #4285f4;">
                {{ url('/customer/email/verify/' . $customer->id . '/' . sha1($customer->email)) }}
            </p>
            
            <p style="margin-top: 30px;">Selamat berbelanja! 🛒</p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>Email ini dikirim otomatis, mohon tidak membalas.</p>
        </div>
    </div>
</body>
</html>