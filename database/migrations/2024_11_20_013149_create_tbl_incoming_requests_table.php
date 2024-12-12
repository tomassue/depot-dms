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
            $table->string('ref_incoming_request_types_id');
            $table->string('ref_office_id');
            $table->string('ref_types_id');
            $table->string('ref_models_id');
            $table->string('number'); # This could be plate number for vehicles or model number for aircons
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('tbl_job_order', function (Blueprint $table) {
            $table->id();
            $table->string('job_order_no');
            $table->string('reference_no');
            $table->dateTime('date_and_time_in');
            $table->string('ref_category_id');
            $table->json('ref_sub_category_id'); //* Array
            $table->string('mileage')->nullable(); //* Required if the incoming request type is vehicle
            $table->string('ref_location_id');
            $table->string('person_in_charge'); //* Former driver_in_charge, we will make it to sound like more general since we have Aircon
            $table->string('contact_number');
            $table->string('ref_status_id');
            $table->string('ref_type_of_repair_id');
            $table->json('ref_mechanics'); # Array
            $table->longText('issue_or_concern');
            $table->longText('findings')->nullable();
            $table->dateTime('date_and_time_out')->nullable();
            $table->string('total_repair_time')->nullable();
            $table->string('claimed_by')->nullable();
            $table->longText('remarks')->nullable();
            $table->string('ref_signatories_id')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
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
