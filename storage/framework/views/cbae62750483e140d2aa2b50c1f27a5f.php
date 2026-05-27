<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Proposal Accepted – Druk Freelancer</title>
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
                <p style="margin:0 0 16px;color:#555;font-size:14px">Hi <strong><?php echo e($freelancer->name); ?></strong>,</p>
                <h2 style="color:#1A3A5C;margin:0 0 24px;font-size:20px">🎉 Congratulations!</h2>
                
                <p style="color:#555;line-height:1.7;margin:0 0 24px;font-size:15px">
                    Your proposal has been accepted! The client is impressed with your bid and is ready to move forward.
                </p>

                <!-- Job Details Box -->
                <div style="background:#f8f9fa;border-left:4px solid #10B981;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 12px 0;color:#1A3A5C;font-weight:bold;font-size:14px">📌 Job Details</p>
                    <p style="margin:0 0 8px;color:#555;font-size:13px"><strong>Job:</strong> <?php echo e($job->title); ?></p>
                    <p style="margin:0 0 8px;color:#555;font-size:13px"><strong>Your Bid:</strong> <span style="color:#10B981;font-weight:bold;"><?php echo e($bidAmount); ?></span></p>
                    <p style="margin:0 0 0;color:#555;font-size:13px"><strong>Delivery Time:</strong> <?php echo e($proposal->delivery_days); ?> days</p>
                </div>

                <p style="color:#555;line-height:1.7;margin:0 0 24px;font-size:15px">
                    A contract has been created and is awaiting the client's completion of payment setup (escrow funding). Once they've done that, you can begin work on the project.
                </p>

                <!-- Action Button -->
                <div style="text-align:center;margin:28px 0;">
                    <a href="<?php echo e($contractUrl); ?>" style="background:#10B981;color:#ffffff;text-decoration:none;padding:14px 32px;border-radius:6px;font-weight:bold;font-size:15px;display:inline-block">
                        View Your Contract
                    </a>
                </div>

                <!-- Tips Box -->
                <div style="background:#FEF3C7;border-left:4px solid #F59E0B;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 8px;color:#92400E;font-weight:bold;font-size:13px">💡 Pro Tip</p>
                    <p style="margin:0;color:#92400E;font-size:13px;line-height:1.6;">
                        Make sure your contract is fully signed and escrow is funded before you start working. This protects both you and the client. Check your contract dashboard for status updates.
                    </p>
                </div>

                <p style="color:#bbb;font-size:12px;line-height:1.6;margin:24px 0 0;">
                    If you have any questions, contact our support team at <a href="mailto:support@drukfreelancer.bt" style="color:#10B981">support@drukfreelancer.bt</a>
                </p>
            </td></tr>
            <!-- Footer -->
            <tr><td bgcolor="#f8f9fa" style="padding:18px 40px;text-align:center;">
                <p style="margin:0;color:#aaa;font-size:12px">© <?php echo e(date('Y')); ?> Druk Freelancer · Thimphu, Bhutan · <a href="<?php echo e(url('/')); ?>" style="color:#aaa">drukfreelancer.bt</a></p>
            </td></tr>
        </table>
    </td></tr>
</table>
</body>
</html>
<?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/emails/proposals/accepted.blade.php ENDPATH**/ ?>