<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MedicalRecordController extends Controller
{
    public function store(Request $request)
    {
        $patient = Patient::find($request->patient_id);

        if (!$patient) {
            return response()->json(['message' => 'patient not found'], 404);
        }

        $request->validate(MedicalRecord::rules(), MedicalRecord::feedbacks());

        $medicalRecord = new MedicalRecord();
        $medicalRecord->record_date = $request->record_date;
        $medicalRecord->description = $request->description;
        $medicalRecord->patient()->associate($patient);

        $attachment = $request->attachment;

        $path = $attachment->store();

        $medicalRecord->attachment = $path;

        $medicalRecord->save();

        $medicalRecord->load('patient');

        return response()->json($medicalRecord, 201);
    }

    public function index(Request $request)
    {
        $medicalRecords = MedicalRecord::query();

        if ($request->has('name')) {
            $medicalRecords->whereHas('patient', function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->name . '%');
            });
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $medicalRecords->whereBetween('record_date', [$request->start_date, $request->end_date]);
        }

        $medicalRecords = $medicalRecords->with('patient')->paginate($request->size, ['*'], 'page', $request->page);

        return response()->json($medicalRecords, 200);
    }

    public function downloadMedicalRecord($id)
    {
        $medicalRecord = MedicalRecord::where('patient_id', $id)->first();

        if (!$medicalRecord) {
            return response()->json(['error' => 'Medical Record not found'], 404);
        }

        return Storage::download($medicalRecord->attachment);
    }
}
