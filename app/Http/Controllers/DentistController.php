<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Dentist;
use Illuminate\Http\Request;

class DentistController extends Controller
{

    public function store(Request $request)
    {
        $request->validate(Dentist::rules(), Dentist::feedbacks());
        $request->validate(Address::rules(), Address::feedbacks());

        $dentist = new Dentist();
        $dentist->name = $request->name;
        $dentist->specialty = $request->specialty;
        $dentist->telephone = $request->telephone;
        $dentist->email = $request->email;
        $dentist->cpf = $request->cpf;

        $address = new Address();
        $address->street = $request->address['street'];
        $address->city = $request->address['city'];
        $address->cep = $request->address['cep'];
        $address->number = $request->address['number'];

        $address->save();

        $dentist->address()->associate($address);

        $dentist->save();

        return response()->json($dentist, 201);
    }

    public function update(Request $request, $id)
    {
        $dentist = Dentist::find($id);

        if ($dentist === null) {
            return response()->json(['erro' => 'Dentista não encontrado'], 404);
        }

        if ($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            foreach (Dentist::rules() as $input => $regra) {

                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            if ($request->has('address')) {
                $request->validate(Address::rules(), Address::feedbacks());

                $dentist->address->update($request->input('address'));
            }

            $request->validate($regrasDinamicas, Dentist::feedbacks());
        } else {
            $request->validate(Dentist::rules(), Dentist::feedbacks());
        }

        $dentist->fill($request->except('address'));

        $dentist->save();

        return response()->json($dentist, 200);
    }

    public function index()
    {
        $dentists = Dentist::with('address')->paginate();

        return response()->json($dentists, 200);
    }

    public function delete($id)
    {
        $dentist = Dentist::find($id);

        if ($dentist) {
            $dentist->delete();
            return response()->json("Dentista deletado com sucesso!", 200);
        } else {
            return response()->json("Dentista não encontrado!", 404);
        }
    }
}
