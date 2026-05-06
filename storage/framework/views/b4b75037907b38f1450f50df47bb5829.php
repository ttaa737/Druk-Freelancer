<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>OTP Verification – Druk Freelancer</title>
</head>
<body style="margin:0;padding:0;background:#f5f5f5;font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" bgcolor="#f5f5f5" cellpadding="0" cellspacing="0">
    <tr><td align="center" style="padding:40px 20px;">
        <table width="560" bgcolor="#ffffff" cellpadding="0" cellspacing="0" style="border-radius:8px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.07);">
            <!-- Header -->
            <tr><td bgcolor="#1A3A5C" style="padding:28px 40px;text-align:center;">
                <h1 style="color:#ffffff;margin:0;font-size:24px;letter-spacing:-0.5px">🏔 Druk Freelancer</h1>
                <p style="color:#F4A823;margin:6px 0 0;font-size:13px">Bhutan's Digital Marketplace</p>
            </td></tr>
            <!-- Body -->
            <tr><td style="padding:40px 40px 20px;">
                <h2 style="color:#1A3A5C;margin-top:0;font-size:20px">
                    <?php if($type === 'withdrawal'): ?> Withdrawal Verification
                    <?php elseif($type === 'phone_verify'): ?> Phone Number Verification
                    <?php else: ?> Identity Verification
                    <?php endif; ?>
                </h2>
                <p style="color:#555;line-height:1.6;margin-top:0;">
                    <?php if($type === 'withdrawal'): ?>
                        You requested to withdraw <strong>Nu. <?php echo e(number_format($amount ?? 0)); ?></strong> from your Druk Freelancer wallet. Use the OTP below to confirm.
                    <?php elseif($type === 'phone_verify'): ?>
                        Use the code below to verify your phone number.
                    <?php else: ?>
                        Please use the code below to verify your identity.
                    <?php endif; ?>
                </p>

                <!-- OTP Block -->
                <div style="background:#f0f4f8;border:2px dashed #1A3A5C;border-radius:8px;text-align:center;padding:24px;margin:28px 0;">
                    <p style="margin:0 0 8px;color:#888;font-size:12px;text-transform:uppercase;letter-spacing:1px">Your One-Time Password</p>
                    <div style="font-size:48px;font-weight:bold;color:#FF6B35;letter-spacing:12px;font-family:monospace"><?php echo e($otp); ?></div>
                    <p style="margin:8px 0 0;color:#888;font-size:12px">Expires in <strong><?php echo e($expiresInMinutes ?? 10); ?> minutes</strong></p>
                </div>

                <p style="color:#888;font-size:13px;line-height:1.6;">If you did not request this, please ignore this email or contact <a href="mailto:support@drukfreelancer.bt" style="color:#FF6B35">support@drukfreelancer.bt</a> immediately.</p>
            </td></tr>
            <!-- Footer -->
            <tr><td bgcolor="#f8f9fa" style="padding:20px 40px;text-align:center;">
                <p style="margin:0;color:#aaa;font-size:12px">© <?php echo e(date('Y')); ?> Druk Freelancer · Thimphu, Bhutan</p>
                <p style="margin:4px 0 0;color:#aaa;font-size:11px">Do not reply to this email.</p>
            </td></tr>
        </table>
    </td></tr>
</table>
</body>
</html>
<?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views\emails\otp.blade.php ENDPATH**/ ?>