<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileDataModel extends Model
{
    protected $table = "file_data";

    protected $fillable = [
        "name",
        "size",
        "type",
        "data",
        "user_id"
    ];
}
