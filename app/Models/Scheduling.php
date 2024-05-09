<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scheduling extends Model
{
    use HasFactory;

    protected $hidden = ['dentist_id', 'patient_id'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function dentist()
    {
        return $this->belongsTo(Dentist::class);
    }

    static function rules()
    {
        return [
            'comment' => 'required',
            'treatment_type' => 'required',
            'date_time' => 'required',
            'patient_id' => 'required',
            'dentist_id' => 'required',
        ];
    }

    static function feedbacks()
    {
        return [
            'treatment_type.required' => 'O campo telephone é obrigatório',
            'date_time.required' => 'O campo date_time é obrigatório',
            'dentist_id.required' => 'O campo dentista é obrigatório',
            'patient_id.required' => 'O campo paciente é obrigatório',
        ];
    }
}
