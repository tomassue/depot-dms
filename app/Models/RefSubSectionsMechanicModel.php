<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RefSubSectionsMechanicModel extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = "ref_sub_sections_mechanic";
    protected $fillable = [
        'ref_sections_mechanic_id',
        'name'
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty();
    }
}
