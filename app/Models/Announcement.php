<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'announcement';
    protected $primaryKey = 'id_announcement';
    public function users_creator()
    {
        return $this->belongsTo(Users::class, 'creator_announcement_id', 'id');
    }
}
