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
        'date_and_time_in',
        'ref_category_id',
        'ref_sub_category_id',
        'mileage',
        'ref_location_id',
        'driver_in_charge',
        'contact_number',
        'ref_status_id',
        'ref_type_of_repair_id',
        'ref_mechanics',
        'issue_or_concern',
        'files',
        'findings',
        'date_and_time_out',
        'total_repair_time',
        'claimed_by',
        'remarks',
        'ref_signatories_id'
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

    public function incoming_request(): BelongsTo
    {
        return $this->belongsTo(TblIncomingRequestModel::class, 'reference_no', 'reference_no');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(RefCategoryModel::class, 'ref_category_id', 'id');
    }

    // public function sub_category()
    // {
    //     return $this->belongsTo(RefSubCategoryModel::class, 'ref_sub_category_id', 'id');
    // }

    public function getSubCategoryNamesAttribute()
    {
        $subCategoryIds = json_decode($this->ref_sub_category_id, true);

        $subCategoryNames = RefSubCategoryModel::whereIn('id', $subCategoryIds)->pluck('name')->toArray();

        return implode(', ', $subCategoryNames);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(RefStatusModel::class, 'ref_status_id', 'id');
    }

    public function type_of_repair(): BelongsTo
    {
        return $this->belongsTo(RefTypeOfRepairModel::class, 'ref_type_of_repair_id', 'id');
    }

    // public function mechanic(): BelongsTo
    // {
    //     return $this->belongsTo(RefMechanicsModel::class, 'ref_mechanics', 'id');
    // }


    public function mechanics()
    {
        $mechanicIds = is_array($this->ref_mechanics)
            ? $this->ref_mechanics
            : json_decode($this->ref_mechanics, true);

        $mechanicIds = is_array($mechanicIds) ? $mechanicIds : [$mechanicIds];

        return RefMechanicsModel::whereIn('id', $mechanicIds)->get();
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(RefLocationModel::class, 'ref_location_id', 'id');
    }

    // public function signatories(): BelongsTo
    // {
    //     return $this->belongsTo(RefSignatoriesModel::class, 'ref_signatories_id', 'id');
    // }

    /* -------------------------------------------------------------------------- */

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty();
    }

    /* -------------------------------------------------------------------------- */
}
