<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>You've Been Shortlisted – Druk Freelancer</title>
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
            <tr><td bgcolor="#8B5CF6" style="height:4px;padding:0;line-height:0;font-size:0">&nbsp;</td></tr>
            <!-- Body -->
            <tr><td style="padding:36px 40px;">
                <p style="margin:0 0 16px;color:#555;font-size:14px">Hi <strong>{{ $freelancer->name }}</strong>,</p>
                <h2 style="color:#1A3A5C;margin:0 0 24px;font-size:20px">⭐ You've Been Shortlisted!</h2>
                
                <p style="color:#555;line-height:1.7;margin:0 0 24px;font-size:15px">
                    Great news! Your proposal has been shortlisted for the project. The client is interested in your skills and experience, and you're now in the running to win this contract.
                </p>

                <!-- Job Details Box -->
                <div style="background:#f8f9fa;border-left:4px solid #8B5CF6;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 12px 0;color:#1A3A5C;font-weight:bold;font-size:14px">📌 Project Details</p>
                    <p style="margin:0 0 8px;color:#555;font-size:13px"><strong>Job:</strong> {{ $job->title }}</p>
                    <p style="margin:0 0 8px;color:#555;font-size:13px"><strong>Your Bid:</strong> <span style="color:#8B5CF6;font-weight:bold;">{{ $bidAmount }}</span></p>
                    <p style="margin:0 0 0;color:#555;font-size:13px"><strong>Status:</strong> <span style="color:#8B5CF6;font-weight:bold;">Shortlisted ⭐</span></p>
                </div>

                <p style="color:#555;line-height:1.7;margin:0 0 24px;font-size:15px">
                    The client is reviewing all shortlisted proposals. You may be asked to provide additional details, answer questions, or participate in a discussion before they make their final decision.
                </p>

                <!-- What's Next Box -->
                <div style="background:#DDD6FE;border-left:4px solid #8B5CF6;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 12px;color:#5B21B6;font-weight:bold;font-size:13px">📋 What Happens Next?</p>
                    <ol style="margin:0;color:#5B21B6;font-size:13px;padding-left:20px;">
                        <li style="margin:0 0 6px;line-height:1.6;">Watch your notification for client messages or questions</li>
                        <li style="margin:0 0 6px;line-height:1.6;">Be responsive and professional in all communications</li>
                        <li style="margin:0 0 0;line-height:1.6;">If selected, the client will award the contract and fund escrow</li>
                    </ol>
                </div>

                <!-- Action Button -->
                <div style="text-align:center;margin:28px 0;">
                    <a href="{{ $proposalUrl }}" style="background:#8B5CF6;color:#ffffff;text-decoration:none;padding:14px 32px;border-radius:6px;font-weight:bold;font-size:15px;display:inline-block">
                        View Your Proposal
                    </a>
                </div>

                <p style="color:#bbb;font-size:12px;line-height:1.6;margin:24px 0 0;">
                    Need help? Contact our support team at <a href="mailto:support@drukfreelancer.bt" style="color:#8B5CF6">support@drukfreelancer.bt</a>
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
