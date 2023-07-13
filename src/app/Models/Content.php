<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'work_provider_id',
        'price',
    ];

    //get student
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    //get writer
    public function writer()
    {
        return $this->belongsTo(Writer::class, 'writer_id', 'id');
    }

    //get workProvider
    public function workProvider()
    {
        return $this->belongsTo(WorkProvider::class, 'work_provider_id', 'id');
    }
}
