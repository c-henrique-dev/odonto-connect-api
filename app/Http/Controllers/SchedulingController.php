<?php

namespace App\Http\Controllers;

use App\Models\Dentist;
use App\Models\Patient;
use App\Models\Scheduling;
use Illuminate\Http\Request;

class SchedulingController extends Controller
{
    public function store(Request $request)
    {
        $patient = Patient::find($request->patient_id);

        $dentist = Dentist::find($request->dentist_id);

        if (!$patient) {
            return response()->json(['message' => 'patient not found'], 404);
        }

        if (!$dentist) {
            return response()->json(['message' => 'dentist not found'], 404);
        }

        $request->validate(Scheduling::rules(), Scheduling::feedbacks());

        $scheduling = new Scheduling();
        $scheduling->comment = $request->comment;
        $scheduling->treatment_type = $request->treatment_type;
        $scheduling->date_time = $request->date_time;
        $scheduling->dentist()->associate($dentist);
        $scheduling->patient()->associate($patient);

        $scheduling->save();

        $scheduling->load('dentist', 'patient');

        return response()->json($scheduling, 201);
    }

    public function index(Request $request)
    {
        $schedulings = Scheduling::query();

        if ($request->has('name')) {
            $schedulings->whereHas('patient', function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->name . '%');
            });
        }

        $schedulings = $schedulings->with('patient')->paginate();

        return response()->json($schedulings, 200);
    }
}
