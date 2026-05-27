<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Work Approved & Payment Processing – Druk Freelancer</title>
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
                <h2 style="color:#1A3A5C;margin:0 0 24px;font-size:20px">🎉 Work Approved!</h2>
                
                <p style="color:#555;line-height:1.7;margin:0 0 24px;font-size:15px">
                    Excellent work! Your submission has been verified and approved by our admin team. Your payment is now being processed and will be transferred to your wallet shortly.
                </p>

                <!-- Project Details Box -->
                <div style="background:#f8f9fa;border-left:4px solid #10B981;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 12px 0;color:#1A3A5C;font-weight:bold;font-size:14px">📌 Project Details</p>
                    <p style="margin:0 0 8px;color:#555;font-size:13px"><strong>Project:</strong> <?php echo e($contract->job->title); ?></p>
                    <p style="margin:0 0 8px;color:#555;font-size:13px"><strong>Contract #:</strong> <?php echo e($contract->contract_number); ?></p>
                    <p style="margin:0 0 0;color:#555;font-size:13px"><strong>Completion Date:</strong> <?php echo e($submission->verified_at->format('M d, Y')); ?></p>
                </div>

                <!-- Payment Details Box -->
                <div style="background:#ECFDF5;border-left:4px solid #10B981;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 12px 0;color:#065F46;font-weight:bold;font-size:14px">💰 Payment Summary</p>
                    <table width="100%" style="border-collapse:collapse;font-size:13px;color:#065F46;">
                        <tr style="border-bottom:1px solid #D1FAE5;">
                            <td style="padding:8px 0;"><strong>Project Amount:</strong></td>
                            <td style="text-align:right;padding:8px 0;">Nu. <?php echo e(number_format($contract->total_amount, 2)); ?></td>
                        </tr>
                        <tr style="border-bottom:1px solid #D1FAE5;">
                            <td style="padding:8px 0;"><strong>Platform Fee (10%):</strong></td>
                            <td style="text-align:right;padding:8px 0;">-Nu. <?php echo e(number_format($contract->platform_fee, 2)); ?></td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;font-weight:bold;font-size:14px;"><strong>Your Earnings:</strong></td>
                            <td style="text-align:right;padding:8px 0;font-weight:bold;font-size:14px;color:#059669;"><strong><?php echo e($paymentAmount); ?></strong></td>
                        </tr>
                    </table>
                </div>

                <!-- What's Next Box -->
                <div style="background:#DDD6FE;border-left:4px solid #8B5CF6;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 8px;color:#5B21B6;font-weight:bold;font-size:13px">⏭️ What's Next?</p>
                    <ul style="margin:0;color:#5B21B6;font-size:13px;padding-left:20px;line-height:1.6;">
                        <li>Payment will be processed automatically within 1-2 hours</li>
                        <li>Funds will appear in your wallet account</li>
                        <li>You can withdraw your earnings anytime after verification</li>
                        <li>View your transaction history in the Wallet section</li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div style="text-align:center;margin:28px 0;">
                    <a href="<?php echo e($walletUrl); ?>" style="background:#10B981;color:#ffffff;text-decoration:none;padding:14px 32px;border-radius:6px;font-weight:bold;font-size:15px;display:inline-block;">
                        View Your Wallet
                    </a>
                </div>

                <!-- Tips Box -->
                <div style="background:#FEF3C7;border-left:4px solid #F59E0B;padding:16px;margin:24px 0;border-radius:4px;">
                    <p style="margin:0 0 8px;color:#92400E;font-weight:bold;font-size:13px">💡 Pro Tip</p>
                    <p style="margin:0;color:#92400E;font-size:13px;line-height:1.6;">
                        Consider asking the client for a review! Positive reviews boost your profile and help you win more projects. You can request a review from your contract page.
                    </p>
                </div>

                <p style="color:#bbb;font-size:12px;line-height:1.6;margin:24px 0 0;">
                    Questions? Contact support at <a href="mailto:support@drukfreelancer.bt" style="color:#10B981">support@drukfreelancer.bt</a>
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
<?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/emails/completion/approved.blade.php ENDPATH**/ ?>