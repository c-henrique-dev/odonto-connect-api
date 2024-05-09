<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Validation\Rule;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'gender',
        'telephone',
        'date_birth',
    ];

    protected $hidden = ['address_id'];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function medicalRecord(): HasOne
    {
        return $this->hasOne(Patient::class);
    }

    public function schedulings(): HasMany
    {
        return $this->hasMany(Scheduling::class);
    }

    static function rules()
    {
        return [
            'name' => 'required',
            'telephone' => 'required',
            'gender' => ['required', Rule::in(['male', 'female'])],
            'email' => 'email|required|unique:patients,email',
            'cpf' => 'required|cpf|unique:patients,cpf',
            'date_birth' => 'required',
        ];
    }

    public static function feedbacks()
    {
        return [
            'name.required' => 'O campo name é obrigatório',
            'gender.required' => 'O campo gender é orbigatório',
            'gender.in' => 'O campo gender deve ser male ou female',
            'telephone.required' => 'O campo telephone é obrigatório',
            'email.email' => 'O email informado não é válido',
            'email.unique' => 'O email já está cadastrado',
            'email.required' => 'O campo email é obrigatório',
            'cpf.required' => 'O campo cpf é obrigatório',
            'cpf.cpf' => 'O campo cpf precisa ser válido',
            'cpf.unique' => 'O cpf já está cadastrado',
            'date_birth.required' => 'O campo date_birth é obrigatório',
        ];
    }
}
