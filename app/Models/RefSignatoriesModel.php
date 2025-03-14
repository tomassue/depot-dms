<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RefSignatoriesModel extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = "ref_signatories";

    protected $fillable = [
        'name',
        'designation',
        'is_division_chief'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty();
    }
}
