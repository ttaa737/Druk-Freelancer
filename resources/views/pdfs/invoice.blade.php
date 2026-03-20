<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Invoice #{{ $invoice_number }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #333; background: #fff; }
    .header { background: #1A3A5C; color: #fff; padding: 24px 32px; }
    .header h1 { font-size: 22px; margin-bottom: 2px; }
    .header .sub { color: #F4A823; font-size: 11px; }
    .invoice-meta { display: flex; justify-content: space-between; padding: 20px 32px; border-bottom: 2px solid #FF6B35; }
    .invoice-meta .label { color: #888; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
    .invoice-meta .value { font-size: 13px; font-weight: bold; color: #1A3A5C; }
    .parties { display: flex; justify-content: space-between; padding: 24px 32px; }
    .parties .party { width: 45%; }
    .parties .party h4 { color: #1A3A5C; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; border-bottom: 1px solid #eee; padding-bottom: 4px; }
    .parties .party p { font-size: 12px; line-height: 1.6; color: #555; }
    table.milestones { width: calc(100% - 64px); margin: 0 32px; border-collapse: collapse; }
    table.milestones thead tr { background: #1A3A5C; color: #fff; }
    table.milestones th { padding: 8px 12px; font-size: 11px; text-align: left; }
    table.milestones td { padding: 8px 12px; border-bottom: 1px solid #eee; font-size: 12px; }
    table.milestones tr:nth-child(even) { background: #f9fafb; }
    .totals { margin: 16px 32px; float: right; width: 260px; }
    .totals table { width: 100%; border-collapse: collapse; }
    .totals td { padding: 5px 8px; font-size: 12px; }
    .totals .row-total { background: #FF6B35; color: #fff; font-weight: bold; font-size: 14px; }
    .footer { clear: both; margin-top: 40px; padding: 16px 32px; background: #f8f9fa; border-top: 1px solid #eee; font-size: 10px; color: #aaa; text-align: center; }
    .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: bold; }
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    .badge-secondary { background: #f3f4f6; color: #6b7280; }
</style>
</head>
<body>

<!-- Header -->
<div class="header">
    <h1>🏔 Druk Freelancer</h1>
    <div class="sub">Bhutan's Digital Marketplace · drukfreelancer.bt</div>
</div>

<!-- Invoice Meta -->
<div class="invoice-meta">
    <div>
        <div class="label">Invoice Number</div>
        <div class="value">#{{ $invoice_number }}</div>
    </div>
    <div>
        <div class="label">Issue Date</div>
        <div class="value">{{ $issue_date }}</div>
    </div>
    <div>
        <div class="label">Contract ID</div>
        <div class="value">#{{ $contract->id }}</div>
    </div>
    <div>
        <div class="label">Status</div>
        <div class="value">
            <span class="badge badge-{{ $contract->status === 'completed' ? 'success' : 'warning' }}">{{ ucfirst($contract->status) }}</span>
        </div>
    </div>
</div>

<!-- Parties -->
<div class="parties">
    <div class="party">
        <h4>Bill To (Client)</h4>
        <p>
            <strong>{{ $contract->poster?->name }}</strong><br>
            {{ $contract->poster?->email }}<br>
            {{ $contract->poster?->profile?->dzongkhag ?? '' }}, Bhutan
        </p>
    </div>
    <div class="party">
        <h4>Service Provider (Freelancer)</h4>
        <p>
            <strong>{{ $contract->freelancer?->name }}</strong><br>
            {{ $contract->freelancer?->email }}<br>
            {{ $contract->freelancer?->profile?->dzongkhag ?? '' }}, Bhutan
        </p>
    </div>
</div>

<!-- Contract Info -->
<div style="margin: 0 32px 16px; padding: 12px; background: #f0f4f8; border-left: 4px solid #1A3A5C; border-radius: 4px;">
    <div style="font-weight: bold; color: #1A3A5C; font-size: 13px; margin-bottom: 4px;">{{ $contract->title }}</div>
    <div style="color: #666; font-size: 11px;">{{ Str::limit($contract->description, 200) }}</div>
</div>

<!-- Milestones Table -->
<table class="milestones">
    <thead>
        <tr>
            <th>#</th>
            <th>Milestone</th>
            <th>Status</th>
            <th style="text-align:right">Amount (Nu.)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($contract->milestones as $i => $milestone)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $milestone->title }}</td>
            <td><span class="badge badge-{{ $milestone->status === 'approved' ? 'success' : 'secondary' }}">{{ ucfirst($milestone->status) }}</span></td>
            <td style="text-align:right;font-weight:600">{{ number_format($milestone->amount) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Totals -->
<div class="totals" style="margin-top: 16px;">
    <table>
        <tr>
            <td>Subtotal</td>
            <td style="text-align:right">Nu. {{ number_format($subtotal) }}</td>
        </tr>
        <tr>
            <td>Platform Fee ({{ config('platform.service_fee_percent') }}%)</td>
            <td style="text-align:right; color:#e53e3e">– Nu. {{ number_format($platform_fee) }}</td>
        </tr>
        <tr class="row-total">
            <td style="padding: 8px 8px; border-radius: 4px 0 0 4px;">Freelancer Receives</td>
            <td style="text-align:right; padding: 8px 8px; border-radius: 0 4px 4px 0;">Nu. {{ number_format($freelancer_amount) }}</td>
        </tr>
    </table>
</div>

<div style="clear:both"></div>

<!-- Notes -->
@if(isset($notes) && $notes)
<div style="margin: 16px 32px; padding: 12px; background: #fffbeb; border: 1px solid #F4A823; border-radius: 4px; font-size: 11px; color: #555;">
    <strong>Notes:</strong> {{ $notes }}
</div>
@endif

<!-- Footer -->
<div class="footer">
    <p>This invoice is generated by Druk Freelancer · Thimphu, Bhutan · support@drukfreelancer.bt</p>
    <p style="margin-top: 4px;">All amounts in Bhutanese Ngultrum (Nu. / BTN)</p>
</div>

</body>
</html>
