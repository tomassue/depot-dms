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
        Schema::create('tbl_incoming_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->string('ref_office_id');
            $table->dateTime('date_and_time');
            $table->string('ref_types_id');
            $table->string('ref_models_id');
            $table->string('number');
            $table->string('mileage');
            $table->string('driver_in_charge');
            $table->string('contact_number');
            $table->timestamps();
            $table->timestamp('deleted_at');
        });

        Schema::create('tbl_job_order', function (Blueprint $table) {
            $table->id();
            $table->string('job_order_no');
            $table->string('reference_no');
            $table->string('ref_category_id');
            $table->string('ref_sub_category_id');
            $table->string('ref_location_id');
            $table->string('ref_status_id');
            $table->string('ref_type_of_repair_id');
            $table->string('ref_mechanics');
            $table->longText('issue_or_concern');
            $table->dateTime('date_and_time')->nullable();
            $table->string('total_repair_time')->nullable();
            $table->string('claimed_by')->nullable();
            $table->longText('remarks')->nullable();
            $table->string('ref_signatories_id')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_incoming_requests');
        Schema::dropIfExists('tbl_job_order');
    }
};
