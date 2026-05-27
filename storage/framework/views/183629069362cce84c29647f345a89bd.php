<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Resubmission Required – Druk Freelancer</title>
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
                <p style="margin:0 0 16px;color:#555;font-size:14px">Hi <strong><?php echo e($freelancer->name); ?></strong>,</p>
                <h2 style="color:#1A3A5C;margin:0 0 24px;font-size:20px">📝 Resubmission Required</h2>
                
                <p style="color:#555;line-height:1.7;margin:0 0 24px;font-size:15px">
                    Thank you for submitting your work! Unfortunately, it requires revisions before we can process payment. The admin team has provided feedback below.
                </p>

                <!-- Project Details Box -->
                <div style="background:#f8f9fa;border-left:4px solid #F59E0B;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 12px 0;color:#1A3A5C;font-weight:bold;font-size:14px">📌 Project Details</p>
                    <p style="margin:0 0 8px;color:#555;font-size:13px"><strong>Project:</strong> <?php echo e($contract->job->title); ?></p>
                    <p style="margin:0 0 8px;color:#555;font-size:13px"><strong>Contract #:</strong> <?php echo e($contract->contract_number); ?></p>
                    <p style="margin:0 0 0;color:#555;font-size:13px"><strong>Amount:</strong> Nu. <?php echo e(number_format($contract->total_amount, 2)); ?></p>
                </div>

                <!-- Admin Feedback Box -->
                <div style="background:#FEF2F2;border-left:4px solid #EF4444;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 12px;color:#7C2D2B;font-weight:bold;font-size:13px;">📋 Admin Feedback</p>
                    <p style="margin:0;color:#7C2D2B;font-size:13px;line-height:1.8;">
                        <?php echo e(nl2br(e($rejectionReason))); ?>

                    </p>
                </div>

                <!-- What To Do Box -->
                <div style="background:#DDD6FE;border-left:4px solid #8B5CF6;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 8px;color:#5B21B6;font-weight:bold;font-size:13px">✅ What You Should Do</p>
                    <ol style="margin:0;color:#5B21B6;font-size:13px;padding-left:20px;line-height:1.6;">
                        <li>Review the feedback carefully</li>
                        <li>Make the necessary revisions to your work</li>
                        <li>Prepare any additional evidence or documentation</li>
                        <li>Resubmit your corrected work with updated files</li>
                    </ol>
                </div>

                <!-- Action Button -->
                <div style="text-align:center;margin:28px 0;">
                    <a href="<?php echo e($resubmitUrl); ?>" style="background:#8B5CF6;color:#ffffff;text-decoration:none;padding:14px 32px;border-radius:6px;font-weight:bold;font-size:15px;display:inline-block">
                        Resubmit Your Work
                    </a>
                </div>

                <!-- Resubmission Policy Box -->
                <div style="background:#F9FAFB;border-left:4px solid #6B7280;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 8px;color:#374151;font-weight:bold;font-size:13px">❓ Resubmission Process</p>
                    <ul style="margin:0;color:#555;font-size:12px;padding-left:20px;line-height:1.6;">
                        <li>You can resubmit your work from your contract page</li>
                        <li>Include all corrections and new evidence</li>
                        <li>Our team will review the new submission (usually within 1-2 days)</li>
                        <li>Payment will be processed once approved</li>
                    </ul>
                </div>

                <!-- Motivation Box -->
                <div style="background:#FEF3C7;border-left:4px solid #F59E0B;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 8px;color:#92400E;font-weight:bold;font-size:13px">💡 You've Got This!</p>
                    <p style="margin:0;color:#92400E;font-size:13px;line-height:1.6;">
                        Don't be discouraged! Most resubmissions are approved. Take the feedback as constructive guidance and submit your best work. We're here to ensure both you and your client are satisfied.
                    </p>
                </div>

                <p style="color:#bbb;font-size:12px;line-height:1.6;margin:24px 0 0;">
                    Questions about the feedback? Contact support at <a href="mailto:support@drukfreelancer.bt" style="color:#8B5CF6">support@drukfreelancer.bt</a>
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
<?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/emails/completion/rejected.blade.php ENDPATH**/ ?>