<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'user_id'
    ];

    /**
     * Belongs To Relation - Eloquent Relation For Todo user
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
