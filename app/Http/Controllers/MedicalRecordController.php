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
        $attachment->store();

        $medicalRecord->attachment = $attachment->getClientOriginalName();

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

        $medicalRecords = $medicalRecords->with('patient')->paginate();

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
