<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Proposal Update – Druk Freelancer</title>
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
            <tr><td bgcolor="#EF4444" style="height:4px;padding:0;line-height:0;font-size:0">&nbsp;</td></tr>
            <!-- Body -->
            <tr><td style="padding:36px 40px;">
                <p style="margin:0 0 16px;color:#555;font-size:14px">Hi <strong>{{ $freelancer->name }}</strong>,</p>
                <h2 style="color:#1A3A5C;margin:0 0 24px;font-size:20px">Proposal Update</h2>
                
                <p style="color:#555;line-height:1.7;margin:0 0 24px;font-size:15px">
                    Thank you for submitting your proposal! Unfortunately, the client has decided to move forward with another freelancer for this project.
                </p>

                <!-- Job Details Box -->
                <div style="background:#f8f9fa;border-left:4px solid #EF4444;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 12px 0;color:#1A3A5C;font-weight:bold;font-size:14px">📌 Job Details</p>
                    <p style="margin:0 0 8px;color:#555;font-size:13px"><strong>Job:</strong> {{ $job->title }}</p>
                    <p style="margin:0 0 8px;color:#555;font-size:13px"><strong>Your Bid:</strong> Nu. {{ number_format($proposal->bid_amount, 2) }}</p>
                </div>

                @if($rejectionReason && $rejectionReason !== 'No reason provided')
                <!-- Feedback Box -->
                <div style="background:#FCE7E6;border-left:4px solid #EF4444;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 8px;color:#7C2D2B;font-weight:bold;font-size:13px">📝 Client Feedback</p>
                    <p style="margin:0;color:#7C2D2B;font-size:13px;line-height:1.6;">
                        {{ $rejectionReason }}
                    </p>
                </div>
                @endif

                <p style="color:#555;line-height:1.7;margin:0 0 24px;font-size:15px">
                    Don't be discouraged! This is a normal part of freelancing. Every project you apply for is an opportunity to learn and improve. Use this feedback to strengthen your future proposals.
                </p>

                <!-- Tips Box -->
                <div style="background:#FEF3C7;border-left:4px solid #F59E0B;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 8px;color:#92400E;font-weight:bold;font-size:13px">💡 Keep Improving</p>
                    <p style="margin:0;color:#92400E;font-size:13px;line-height:1.6;">
                        Continue building your profile, collect positive reviews, and refine your proposals for future opportunities. There are always more great projects on the platform!
                    </p>
                </div>

                <!-- Action Button -->
                <div style="text-align:center;margin:28px 0;">
                    <a href="{{ $browsJobsUrl }}" style="background:#3B82F6;color:#ffffff;text-decoration:none;padding:14px 32px;border-radius:6px;font-weight:bold;font-size:15px;display:inline-block">
                        Browse More Jobs
                    </a>
                </div>

                <p style="color:#bbb;font-size:12px;line-height:1.6;margin:24px 0 0;">
                    Questions? Contact our support team at <a href="mailto:support@drukfreelancer.bt" style="color:#3B82F6">support@drukfreelancer.bt</a>
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
