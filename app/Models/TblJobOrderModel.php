<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    /**
     * Boot method to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically assign job_order_no when creating a new record
        static::creating(function ($model) {
            $model->job_order_no = self::getNextJobOrderNumber($model->reference_no);
        });
    }

    /**
     * Get the next job order number for a given reference number.
     *
     * @param string $referenceNo
     * @return int
     */
    public static function getNextJobOrderNumber($referenceNo)
    {
        $maxJobOrderNo = self::where('reference_no', $referenceNo)
            ->max('job_order_no');

        return $maxJobOrderNo ? $maxJobOrderNo + 1 : 1;
    }

    /* -------------------------------------------------------------------------- */

    public function category(): BelongsTo
    {
        return $this->belongsTo(RefCategoryModel::class, 'ref_category_id', 'id');
    }

    public function sub_category(): BelongsTo
    {
        return $this->belongsTo(RefSubCategoryModel::class, 'ref_sub_category_id', 'id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(RefStatusModel::class, 'ref_status_id', 'id');
    }

    /* -------------------------------------------------------------------------- */

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty();
    }

    /* -------------------------------------------------------------------------- */
}