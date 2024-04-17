<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guide extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'guide';
    protected $primaryKey = 'id_guide';
    public function users_creator()
    {
        return $this->belongsTo(Users::class, 'creator_guide_id', 'id');
    }
}
