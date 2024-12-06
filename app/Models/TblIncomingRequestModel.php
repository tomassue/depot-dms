<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TblIncomingRequestModel extends Model
{
    use LogsActivity;

    protected $table = "tbl_incoming_requests";

    protected $fillable = [
        'reference_no',
        'ref_incoming_request_types_id',
        'ref_office_id',
        'ref_types_id',
        'ref_models_id',
        'number'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Check if the reference number is already set
            if (empty($model->reference_no)) {
                $model->reference_no = self::generateUniqueReference('REF-', 8);
            }
        });
    }

    /**
     * Generate a unique reference number.
     *
     * @param string $prefix
     * @param int $length
     * @return string
     */
    public static function generateUniqueReference(string $prefix = '', int $length = 6): string
    {
        do {
            // Generate the reference number with the specified prefix
            $reference = $prefix . strtoupper(substr(uniqid(), -$length));
        } while (self::where('reference_no', $reference)->exists());

        return $reference;
    }

    /* -------------------------------------------------------------------------- */

    public function incoming_request_type(): BelongsTo
    {
        return $this->belongsTo(RefIncomingRequestTypeModel::class, 'ref_incoming_request_types_id', 'id');
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(RefOfficesModel::class, 'ref_office_id', 'id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(RefTypeModel::class, 'ref_types_id', 'id');
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(RefModelModel::class, 'ref_models_id', 'id');
    }

    /* -------------------------------------------------------------------------- */

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty();
    }
}
