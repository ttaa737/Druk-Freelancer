<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Verification Approved – Druk Freelancer</title>
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
            <tr><td bgcolor="#10B981" style="height:4px;padding:0;line-height:0;font-size:0">&nbsp;</td></tr>
            <!-- Body -->
            <tr><td style="padding:36px 40px;">
                <p style="margin:0 0 16px;color:#555;font-size:14px">Hi <strong>{{ $user->name }}</strong>,</p>
                <h2 style="color:#1A3A5C;margin:0 0 24px;font-size:20px">✅ Verification Approved!</h2>
                
                <p style="color:#555;line-height:1.7;margin:0 0 24px;font-size:15px">
                    Excellent news! Your <strong>{{ $documentType }}</strong> document has been successfully verified by our admin team. Your account credibility has been enhanced, and you can now access all platform features without restrictions.
                </p>

                <!-- Verification Status Box -->
                <div style="background:#ECFDF5;border-left:4px solid #10B981;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 12px 0;color:#065F46;font-weight:bold;font-size:14px">📋 Verification Status</p>
                    <table width="100%" style="border-collapse:collapse;font-size:13px;color:#065F46;">
                        <tr style="border-bottom:1px solid #D1FAE5;">
                            <td style="padding:8px 0;"><strong>Document Type:</strong></td>
                            <td style="text-align:right;padding:8px 0;">{{ $documentType }}</td>
                        </tr>
                        <tr style="border-bottom:1px solid #D1FAE5;">
                            <td style="padding:8px 0;"><strong>Status:</strong></td>
                            <td style="text-align:right;padding:8px 0;"><span style="background:#10B981;color:white;padding:4px 8px;border-radius:3px;font-weight:bold;">✓ Verified</span></td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;"><strong>Verified On:</strong></td>
                            <td style="text-align:right;padding:8px 0;">{{ now()->format('M d, Y H:i A') }}</td>
                        </tr>
                    </table>
                </div>

                <!-- What This Means Box -->
                <div style="background:#DDD6FE;border-left:4px solid #8B5CF6;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 12px;color:#5B21B6;font-weight:bold;font-size:13px">🎯 What This Means For You</p>
                    <ul style="margin:0;color:#5B21B6;font-size:13px;padding-left:20px;line-height:1.8;">
                        <li>Your profile is now fully verified and trusted</li>
                        <li>You have higher visibility on the platform</li>
                        <li>Clients have more confidence in working with you</li>
                        <li>You can now submit unlimited proposals</li>
                        <li>Your account has enhanced protection</li>
                    </ul>
                </div>

                <!-- Next Steps Box -->
                <div style="background:#FEF3C7;border-left:4px solid #F59E0B;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 8px;color:#92400E;font-weight:bold;font-size:13px">📌 Recommended Next Steps</p>
                    <ol style="margin:0;color:#92400E;font-size:13px;padding-left:20px;line-height:1.8;">
                        <li>Complete your profile with a professional photo and description</li>
                        <li>Add your skills and certifications</li>
                        <li>Build your portfolio with past work samples</li>
                        <li>Start bidding on relevant projects</li>
                    </ol>
                </div>

                <!-- Action Button -->
                <div style="text-align:center;margin:28px 0;">
                    <a href="{{ $dashboardUrl }}" style="background:#10B981;color:#ffffff;text-decoration:none;padding:14px 32px;border-radius:6px;font-weight:bold;font-size:15px;display:inline-block">
                        Go to Dashboard
                    </a>
                </div>

                <!-- Benefits Box -->
                <div style="background:#EFF6FF;border-left:4px solid #3B82F6;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 8px;color:#1E40AF;font-weight:bold;font-size:13px">🏅 Verified Badge Benefits</p>
                    <p style="margin:0;color:#1E40AF;font-size:13px;line-height:1.6;">
                        Your verified badge will appear on your profile, distinguishing you from unverified users and making you stand out to potential clients. This significantly increases your chances of winning contracts!
                    </p>
                </div>

                <p style="color:#bbb;font-size:12px;line-height:1.6;margin:24px 0 0;">
                    Questions? Contact our support team at <a href="mailto:support@drukfreelancer.bt" style="color:#10B981">support@drukfreelancer.bt</a>
                </p>
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
