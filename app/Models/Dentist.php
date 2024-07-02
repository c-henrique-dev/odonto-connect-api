<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dentist extends Model
{
    use HasFactory;

    protected $hidden = ['address_id'];

    protected $fillable = [
        'name',
    ];

    public function schedulings(): HasMany
    {
        return $this->hasMany(Scheduling::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    static function rules()
    {
        return [
            'name' => 'required',
            'telephone' => 'required',
            'email' => 'email|required|unique:dentists,email',
            'specialty' => 'required',
        ];
    }

    static function feedbacks()
    {
        return [
            'name.required' => 'O campo name é obrigatório',
            'telephone.required' => 'O campo telephone é obrigatório',
            'email.email' => 'O email informado não é válido',
            'email.unique' => 'O email já está cadastrado',
            'email.required' => 'O campo email é obrigatório',
            'specialty.required' => 'O campo date_birth é obrigatório',
        ];
    }
}
