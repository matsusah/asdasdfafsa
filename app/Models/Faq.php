<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'faq';
    protected $primaryKey = 'id_faq';
    public function users_creator()
    {
        return $this->belongsTo(Users::class, 'creator_faq_id', 'id');
    }
}
