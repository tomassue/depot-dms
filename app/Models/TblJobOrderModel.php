<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TblJobOrderModel extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = "tbl_job_order";

    protected $fillable = [
        'job_order_no',
        'reference_no',
        'ref_category_id',
        'ref_sub_category_id',
        'ref_location_id',
        'ref_status_id',
        'ref_type_of_repair_id',
        'ref_mechanics',
        'issue_or_concern',
        'date_and_time',
        'total_repair_time',
        'claimed_by',
        'remarks'
    ];

    /* -------------------------------------------------------------------------- */

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty();
    }
}
