<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'comment';
    protected $primaryKey = 'id_comment';

    public function users_creator()
    {
        return $this->belongsTo(Users::class, 'creator_comment_id', 'id');
    }
    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id_campaign');
    }
}
