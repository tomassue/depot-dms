<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RefModelModel extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = "ref_models";

    protected $fillable = [
        'ref_types_id',
        'name'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty();
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(RefTypeModel::class, 'ref_types_id', 'id');
    }
}
