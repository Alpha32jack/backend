<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atributo extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', // Relación con estudiantes
        'name',
        'armor_class_base',
        'armor_class_constitution',
        'armor_class_items',
        'armor_class_total',
        'dodge_base',
        'dodge_destreza',
        'dodge_items',
        'dodge_total',
        'hit_points_base',
        'damage_taken',
        'total_life',
        'level',
        'experience',
        'nickname',
        'characteristic',
        'bonus_item',
        'skill',
        'total_skill',
    ];

    // Relación con estudiantes
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
