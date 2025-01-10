<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Task extends Model
{

    use HasFactory;
    protected $table = "tasks";
    
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'priority',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ] ;
}
