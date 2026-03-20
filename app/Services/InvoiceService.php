<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Milestone;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    protected float $platformFeePercent;

    public function __construct()
    {
        $this->platformFeePercent = (float) config('platform.service_fee_percent', 10);
    }

    /**
     * Generate an invoice for a contract milestone.
     */
    public function generateMilestoneInvoice(Milestone $milestone): Invoice
    {
        $contract = $milestone->contract;
        $platformFee = round($milestone->amount * ($this->platformFeePercent / 100), 2);
        $subtotal = $milestone->amount;

        $invoice = Invoice::create([
            'contract_id'  => $contract->id,
            'milestone_id' => $milestone->id,
            'billed_to'    => $contract->poster_id,
            'billed_by'    => $contract->freelancer_id,
            'subtotal'     => $subtotal,
            'platform_fee' => $platformFee,
            'tax_amount'   => 0,
            'total_amount' => $subtotal,
            'amount_paid'  => $subtotal,
            'status'       => 'paid',
            'paid_at'      => now(),
            'notes'        => "Payment for milestone: {$milestone->title} — Contract #{$contract->contract_number}",
        ]);

        // Generate PDF
        $pdfPath = $this->generatePdf($invoice);
        $invoice->update(['pdf_path' => $pdfPath]);

        return $invoice;
    }

    /**
     * Generate invoice PDF and store it.
     */
    public function generatePdf(Invoice $invoice): string
    {
        $invoice->load(['contract.job', 'billedTo', 'billedBy', 'milestone']);

        $pdf = Pdf::loadView('pdfs.invoice', ['invoice' => $invoice])
                  ->setPaper('a4')
                  ->setOptions([
                      'dpi'             => 150,
                      'defaultFont'     => 'Helvetica',
                      'isRemoteEnabled' => false,
                  ]);

        $filename = "invoices/{$invoice->invoice_number}.pdf";
        Storage::disk('public')->put($filename, $pdf->output());

        return $filename;
    }

    /**
     * Generate a contract summary invoice.
     */
    public function generateContractInvoice(Contract $contract): Invoice
    {
        $platformFee = round($contract->total_amount * ($this->platformFeePercent / 100), 2);

        $invoice = Invoice::create([
            'contract_id'  => $contract->id,
            'billed_to'    => $contract->poster_id,
            'billed_by'    => $contract->freelancer_id,
            'subtotal'     => $contract->total_amount,
            'platform_fee' => $platformFee,
            'tax_amount'   => 0,
            'total_amount' => $contract->total_amount,
            'amount_paid'  => $contract->total_amount,
            'status'       => 'paid',
            'paid_at'      => $contract->completed_at ?? now(),
            'notes'        => "Final invoice for Contract #{$contract->contract_number}",
        ]);

        $pdfPath = $this->generatePdf($invoice);
        $invoice->update(['pdf_path' => $pdfPath]);

        return $invoice;
    }
}
