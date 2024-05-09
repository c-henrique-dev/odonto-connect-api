<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Address extends Model
{
    use HasFactory;

    public function dentist(): HasOne
    {
        return $this->hasOne(Dentist::class);
    }

    public function patient(): HasOne
    {
        return $this->hasOne(Patient::class);
    }

    static function rules()
    {
        return [
            'address.street' => 'required',
            'address.cep' => 'required',
            'address.city' => 'required',
        ];
    }

    public static function feedbacks()
    {
        return [
            'address.street.required' => 'O campo rua é obrigatório',
            'address.cep.required' => 'O campo cep é obrigatório',
            'address.city.required' => 'O campo cidade é obrigatório',
        ];
    }
}
