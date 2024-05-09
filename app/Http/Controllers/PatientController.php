<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{

    public function store(Request $request)
    {
        $request->validate(Patient::rules(), Patient::feedbacks());
        $request->validate(Address::rules(), Address::feedbacks());

        $dateOfBirth = date('Y-m-d H:i:s', strtotime($request->date_birth));

        $request->merge(['date_birth' => $dateOfBirth]);

        $patient = new Patient();
        $patient->name = $request->name;
        $patient->gender = $request->gender;
        $patient->telephone = $request->telephone;
        $patient->email = $request->email;
        $patient->date_birth = $request->date_birth;
        $patient->cpf = $request->cpf;

        $address = new Address();
        $address->street = $request->address['street'];
        $address->city = $request->address['city'];
        $address->cep = $request->address['cep'];
        $address->number = $request->address['number'];

        $address->save();

        $patient->address()->associate($address);

        $patient->save();

        return response()->json($patient, 201);
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::find($id);

        if ($patient === null) {
            return response()->json(['erro' => 'Paciente não encontrado'], 404);
        }

        if ($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            foreach (Patient::rules() as $input => $regra) {

                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, Patient::feedbacks());
        } else {
            $request->validate(Patient::rules(), Patient::feedbacks());
        }

        $patient->fill($request->all());

        $patient->save();

        return response()->json($patient, 200);
    }

    public function index(Request $request)
    {
        $patients = Patient::query();

        if ($request->has('name')) {
            $patients->where('name', 'LIKE', '%'.$request->name.'%');
        }

        $patients = $patients->paginate();

        return response()->json($patients, 200);
    }
}
