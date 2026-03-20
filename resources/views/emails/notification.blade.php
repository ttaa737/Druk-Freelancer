<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $subject ?? 'Notification' }} – Druk Freelancer</title>
</head>
<body style="margin:0;padding:0;background:#f5f5f5;font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" bgcolor="#f5f5f5" cellpadding="0" cellspacing="0">
    <tr><td align="center" style="padding:40px 20px;">
        <table width="560" bgcolor="#ffffff" cellpadding="0" cellspacing="0" style="border-radius:8px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.07);">
            <!-- Header -->
            <tr><td bgcolor="#1A3A5C" style="padding:24px 40px;text-align:center;">
                <h1 style="color:#ffffff;margin:0;font-size:22px">🏔 Druk Freelancer</h1>
                <p style="color:#F4A823;margin:4px 0 0;font-size:12px">Bhutan's Digital Marketplace</p>
            </td></tr>
            <!-- Accent bar -->
            <tr><td bgcolor="#FF6B35" style="height:4px;padding:0;line-height:0;font-size:0">&nbsp;</td></tr>
            <!-- Body -->
            <tr><td style="padding:36px 40px;">
                @isset($userName)
                <p style="margin:0 0 16px;color:#555;font-size:14px">Hi <strong>{{ $userName }}</strong>,</p>
                @endisset
                <h2 style="color:#1A3A5C;margin:0 0 16px;font-size:20px">{{ $subject ?? 'Platform Notification' }}</h2>
                <p style="color:#555;line-height:1.7;margin:0 0 24px;font-size:15px">{{ $body ?? 'You have a new notification.' }}</p>

                @isset($actionUrl)
                <div style="text-align:center;margin:28px 0;">
                    <a href="{{ $actionUrl }}" style="background:#FF6B35;color:#ffffff;text-decoration:none;padding:14px 32px;border-radius:6px;font-weight:bold;font-size:15px;display:inline-block">
                        {{ $actionLabel ?? 'View Now' }}
                    </a>
                </div>
                @endisset

                <p style="color:#bbb;font-size:12px;line-height:1.6;margin:0;">If you did not expect this email, you may safely ignore it. Contact <a href="mailto:support@drukfreelancer.bt" style="color:#FF6B35">support@drukfreelancer.bt</a> for help.</p>
            </td></tr>
            <!-- Footer -->
            <tr><td bgcolor="#f8f9fa" style="padding:18px 40px;text-align:center;">
                <p style="margin:0;color:#aaa;font-size:12px">© {{ date('Y') }} Druk Freelancer · Thimphu, Bhutan · <a href="{{ url('/') }}" style="color:#aaa">drukfreelancer.bt</a></p>
            </td></tr>
        </table>
    </td></tr>
</table>
</body>
</html>
