<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id()->from(20033);
            $table->foreignId('user_id')->constrained();
            $table->foreignId('vendor_id')->constrained();
            $table->unsignedInteger('proforma_invoice');
            $table->integer('vendor_branch_id')->nullable();
            $table->enum('status',["rejected","pending","approved","processing","draft"])->default("pending");
            $table->double('total');
            $table->double('discounted_amount')->default(0);
            $table->text('comments')->nullable();
            $table->string('invoice')->nullable();
            $table->string('phone_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
