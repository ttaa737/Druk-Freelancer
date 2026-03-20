<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique()->comment('e.g. INV-2026-00001');
            $table->unsignedBigInteger('contract_id');
            $table->unsignedBigInteger('milestone_id')->nullable();
            $table->unsignedBigInteger('billed_to')->comment('Job poster user ID');
            $table->unsignedBigInteger('billed_by')->comment('Freelancer user ID');
            $table->decimal('subtotal', 12, 2)->comment('Amount before fees in BTN');
            $table->decimal('platform_fee', 10, 2)->default(0)->comment('Platform service fee in BTN');
            $table->decimal('tax_amount', 10, 2)->default(0)->comment('Tax if applicable in BTN');
            $table->decimal('total_amount', 12, 2)->comment('Total invoice amount in BTN');
            $table->decimal('amount_paid', 12, 2)->default(0)->comment('Amount paid in BTN');
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])->default('draft');
            $table->timestamp('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('pdf_path')->nullable()->comment('Generated PDF file path');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
            $table->foreign('billed_to')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('billed_by')->references('id')->on('users')->onDelete('cascade');
            $table->index('status');
            $table->index('invoice_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
