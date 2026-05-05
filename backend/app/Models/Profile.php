<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['user_id','bio','experience','skills','resume_url','target_role','pricing','availability','rating_average','review_count'];
    protected $casts = ['skills' => 'array', 'availability' => 'array', 'pricing' => 'decimal:2', 'rating_average' => 'decimal:2'];
    public function user() { return $this->belongsTo(User::class); }
}
