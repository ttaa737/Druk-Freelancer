<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Document Verification Update – Druk Freelancer</title>
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
            <tr><td bgcolor="#F59E0B" style="height:4px;padding:0;line-height:0;font-size:0">&nbsp;</td></tr>
            <!-- Body -->
            <tr><td style="padding:36px 40px;">
                <p style="margin:0 0 16px;color:#555;font-size:14px">Hi <strong>{{ $user->name }}</strong>,</p>
                <h2 style="color:#1A3A5C;margin:0 0 24px;font-size:20px">📝 Document Verification Update</h2>
                
                <p style="color:#555;line-height:1.7;margin:0 0 24px;font-size:15px">
                    Thank you for submitting your <strong>{{ $documentType }}</strong> for verification. Unfortunately, our admin team was unable to verify this document at this time. Please see the feedback below and resubmit with corrections.
                </p>

                <!-- Verification Status Box -->
                <div style="background:#FEF2F2;border-left:4px solid #EF4444;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 12px 0;color:#7C2D2B;font-weight:bold;font-size:14px">📋 Document Status</p>
                    <table width="100%" style="border-collapse:collapse;font-size:13px;color:#7C2D2B;">
                        <tr style="border-bottom:1px solid #FECACA;">
                            <td style="padding:8px 0;"><strong>Document Type:</strong></td>
                            <td style="text-align:right;padding:8px 0;">{{ $documentType }}</td>
                        </tr>
                        <tr style="border-bottom:1px solid #FECACA;">
                            <td style="padding:8px 0;"><strong>Status:</strong></td>
                            <td style="text-align:right;padding:8px 0;"><span style="background:#EF4444;color:white;padding:4px 8px;border-radius:3px;font-weight:bold;">✗ Rejected</span></td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;"><strong>Received:</strong></td>
                            <td style="text-align:right;padding:8px 0;">{{ now()->format('M d, Y') }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Admin Feedback Box -->
                <div style="background:#FCE7E6;border-left:4px solid #EF4444;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 12px;color:#7C2D2B;font-weight:bold;font-size:13px">💬 Admin Feedback</p>
                    <p style="margin:0;color:#7C2D2B;font-size:13px;line-height:1.8;">
                        {{ $rejectionReason }}
                    </p>
                </div>

                <!-- What To Do Box -->
                <div style="background:#DDD6FE;border-left:4px solid #8B5CF6;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 12px;color:#5B21B6;font-weight:bold;font-size:13px">✅ How to Proceed</p>
                    <ol style="margin:0;color:#5B21B6;font-size:13px;padding-left:20px;line-height:1.8;">
                        <li>Review the feedback carefully</li>
                        <li>Prepare a corrected/updated version of your document</li>
                        <li>Ensure the document is clear, legible, and all required information is visible</li>
                        <li>Log in to your profile and resubmit the document</li>
                        <li>Our team will review again within 24-48 hours</li>
                    </ol>
                </div>

                <!-- Requirements Box -->
                <div style="background:#EFF6FF;border-left:4px solid #3B82F6;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 12px;color:#1E40AF;font-weight:bold;font-size:13px">📋 Document Requirements</p>
                    <ul style="margin:0;color:#1E40AF;font-size:13px;padding-left:20px;line-height:1.8;">
                        <li>Document must be original or officially issued copy</li>
                        <li>All text must be clearly readable and not obscured</li>
                        <li>Document must show your full name exactly as registered</li>
                        <li>Expiry dates must be current (not expired)</li>
                        <li>No edited or tampered documents</li>
                    </ul>
                </div>

                <!-- Action Button -->
                <div style="text-align:center;margin:28px 0;">
                    <a href="{{ $resubmitUrl }}" style="background:#8B5CF6;color:#ffffff;text-decoration:none;padding:14px 32px;border-radius:6px;font-weight:bold;font-size:15px;display:inline-block">
                        Resubmit Document
                    </a>
                </div>

                <!-- Support Box -->
                <div style="background:#FEF3C7;border-left:4px solid #F59E0B;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 8px;color:#92400E;font-weight:bold;font-size:13px">💡 Need Help?</p>
                    <p style="margin:0;color:#92400E;font-size:13px;line-height:1.6;">
                        If you're unsure about the requirements or need clarification, don't hesitate to contact our support team. We're here to help you get verified successfully!
                    </p>
                </div>

                <p style="color:#bbb;font-size:12px;line-height:1.6;margin:24px 0 0;">
                    Questions? Contact support at <a href="mailto:support@drukfreelancer.bt" style="color:#8B5CF6">support@drukfreelancer.bt</a>
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
