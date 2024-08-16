<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $hidden = ['patient_id' ,'scheduling_id'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function scheduling()
    {
        return $this->belongsTo(Scheduling::class);
    }

    static function rules()
    {
        return [
            'payment_date' => 'required',
            'payment_reason' => 'required',
            'scheduling_id' => 'required',
            'amount' => 'required',
        ];
    }

    public static function feedbacks()
    {
        return [
            'payment_date.required' => 'O campo payment date é obrigatório',
            'payment_reason.required' => 'O campo payment reason é obrigatório',
            'scheduling_id.required' => 'O campo scheduling id é obrigatório',
            'amount.required' => 'O campo amount é obrigatório',
        ];
    }
}
