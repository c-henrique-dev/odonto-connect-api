<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $hidden = ['patient_id'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    static function rules()
    {
        return [
            'record_date' => 'required|date',
            'description' => 'required|string',
            'attachment' => 'required|file',
        ];
    }

    static function feedbacks()
    {
        return [
            'record_date.required' => 'O campo record_date é obrigatório',
            'record_date.date' => 'O campo record_date deve ser uma data',
            'description.required' => 'O campo description é obrigatório',
            'description.string' => 'O campo description é deve ser uma string',
            'attachment.required' => 'O campo attachment é obrigatório',
            'attachment.file' => 'O campo attachment deve ser um file',
        ];
    }
}
