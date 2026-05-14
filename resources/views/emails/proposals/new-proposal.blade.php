<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>New Proposal Received – Druk Freelancer</title>
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
            <tr><td bgcolor="#3B82F6" style="height:4px;padding:0;line-height:0;font-size:0">&nbsp;</td></tr>
            <!-- Body -->
            <tr><td style="padding:36px 40px;">
                <p style="margin:0 0 16px;color:#555;font-size:14px">Hi <strong>{{ $poster->name }}</strong>,</p>
                <h2 style="color:#1A3A5C;margin:0 0 24px;font-size:20px">📧 New Proposal Received</h2>
                
                <p style="color:#555;line-height:1.7;margin:0 0 24px;font-size:15px">
                    You have a new proposal for your job posting! A freelancer is interested in working on your project.
                </p>

                <!-- Job & Proposal Details Box -->
                <div style="background:#f8f9fa;border-left:4px solid #3B82F6;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 12px 0;color:#1A3A5C;font-weight:bold;font-size:14px">📌 Job & Proposal Details</p>
                    <p style="margin:0 0 8px;color:#555;font-size:13px"><strong>Job:</strong> {{ $job->title }}</p>
                    <p style="margin:0 0 8px;color:#555;font-size:13px"><strong>Freelancer:</strong> {{ $freelancer->name }}</p>
                    <p style="margin:0 0 8px;color:#555;font-size:13px"><strong>Bid Amount:</strong> <span style="color:#3B82F6;font-weight:bold;">{{ $bidAmount }}</span></p>
                    <p style="margin:0 0 0;color:#555;font-size:13px"><strong>Delivery Time:</strong> {{ $proposal->delivery_days }} days</p>
                </div>

                <!-- Freelancer Info Box -->
                <div style="background:#EFF6FF;border-left:4px solid #3B82F6;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 12px 0;color:#1E40AF;font-weight:bold;font-size:13px">👤 Freelancer Profile</p>
                    @if($freelancer->profile)
                    <p style="margin:0 0 4px;color:#1E40AF;font-size:13px"><strong>Rating:</strong> 
                        @if($freelancer->profile->avg_rating)
                            ⭐ {{ number_format($freelancer->profile->avg_rating, 1) }}/5.0
                        @else
                            No ratings yet
                        @endif
                    </p>
                    @endif
                    <p style="margin:0 0 0;color:#1E40AF;font-size:13px"><strong>Member Since:</strong> {{ $freelancer->created_at->format('M Y') }}</p>
                </div>

                <!-- Proposal Excerpt -->
                <div style="background:#F9FAFB;padding:16px;margin:24px 0;border-radius:4px;border:1px solid #E5E7EB;">
                    <p style="margin:0 0 8px;color:#1A3A5C;font-weight:bold;font-size:13px">💬 Proposal Preview</p>
                    <p style="margin:0;color:#555;font-size:13px;line-height:1.6;max-height:80px;overflow:hidden;text-overflow:ellipsis;">
                        {{ Str::limit($proposal->cover_letter, 200) }}
                    </p>
                </div>

                <!-- Action Buttons -->
                <div style="text-align:center;margin:28px 0;">
                    <a href="{{ $proposalUrl }}" style="background:#3B82F6;color:#ffffff;text-decoration:none;padding:12px 28px;border-radius:6px;font-weight:bold;font-size:14px;display:inline-block;margin:0 8px 8px 0;">
                        Review Proposal
                    </a>
                    <a href="{{ $listProposalsUrl }}" style="background:#F3F4F6;color:#1A3A5C;text-decoration:none;padding:12px 28px;border-radius:6px;font-weight:bold;font-size:14px;display:inline-block;margin:0 8px 8px 0;border:1px solid #E5E7EB;">
                        View All Proposals
                    </a>
                </div>

                <!-- Tips Box -->
                <div style="background:#FEF3C7;border-left:4px solid #F59E0B;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 8px;color:#92400E;font-weight:bold;font-size:13px">💡 Tip</p>
                    <p style="margin:0;color:#92400E;font-size:13px;line-height:1.6;">
                        Take time to review all proposals carefully. Consider the freelancer's experience, portfolio, and bid amount. Don't rush – finding the right match is key to project success!
                    </p>
                </div>

                <p style="color:#bbb;font-size:12px;line-height:1.6;margin:24px 0 0;">
                    Questions? Contact support at <a href="mailto:support@drukfreelancer.bt" style="color:#3B82F6">support@drukfreelancer.bt</a>
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
