<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'campaign';
    protected $primaryKey = 'id_campaign';

    public function comment()
    {
        return $this->hasMany(Comment::class, 'campaign_id', 'id_campaign');
    }
    public function users_creator()
    {
        return $this->belongsTo(Users::class, 'creator_campaign_id', 'id');
    }
}
