<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Writer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'address',
        'commission',
    ];

    //get contents
    public function contents()
    {
        return $this->hasMany(Content::class, 'writer_id', 'id');
    }

    //get Archive
    public function Archive()
    {
        return $this->hasMany(Content::class, 'writer_id', 'id')->whereNotNull('delivered_at');
    }
}
