<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
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
        $scheduling->status = StatusEnum::SCHEDULED;
        $scheduling->payment_status = 'waiting';
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

        if ($request->has('status')) {
            $schedulings->where('status', $request->status);
        }

        if ($request->has('payment_status')) {
            $schedulings->where('payment_status', $request->payment_status);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $schedulings->whereBetween('date_time', [$request->start_date, $request->end_date]);
        }

        $schedulings = $schedulings->with('patient')->paginate($request->size, ['*'], 'page', $request->page);

        return response()->json($schedulings, 200);
    }

    public function cancel($id)
    {
        $scheduling = Scheduling::find($id);

        if (!$scheduling) {
            return response()->json(['message' => 'scheduling not found'], 404);
        }

        $scheduling->status = StatusEnum::CANCELED;
        $scheduling->save();

        return response()->json(['message' => 'updated successfuly scheduling'], 200);
    }
}
