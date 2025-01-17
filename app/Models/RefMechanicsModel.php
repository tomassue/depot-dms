<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RefMechanicsModel extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'ref_mechanics';

    protected $fillable = [
        'name',
        'ref_sections_mechanic_id',
        'ref_sub_sections_mechanic_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty();
    }
}
