<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Payment;
use App\Models\Scheduling;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(Payment::rules(), Payment::feedbacks());

        $patient = Patient::find($request->patient_id);

        $scheduling = Scheduling::find($request->scheduling_id);

        if (!$patient) {
            return response()->json(['message' => 'Paciente não encontrado!'], 404);
        }

        if (!$scheduling) {
            return response()->json(['message' => 'Agendamento não encontrado!'], 404);
        }

        $paymentDate = date('Y-m-d H:i:s', strtotime($request->payment_date));

        $request->merge(['payment_date' => $paymentDate]);

        $payment = new Payment();
        $payment->payment_date = $request->payment_date;
        $payment->payment_reason = $request->payment_reason;
        $payment->amount = $request->amount;
        $payment->patient()->associate($patient);
        $payment->scheduling()->associate($scheduling);

        if ($payment->save()) {
            $scheduling->payment_status = 'paid';
            $scheduling->save();
        }

        $payment->load('patient', 'scheduling');

        return response()->json($payment, 201);
    }

    public function getPaymentByScheduling($schedulingId)
    {
        $payment = Payment::where('scheduling_id', $schedulingId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Pagamento não encontrado!'], 404);
        }

        return response()->json($payment, 200);
    }

    public function getTotalPaymentByPatient($patientId)
    {
        $totalPayment = Payment::where('patient_id', $patientId)->sum('amount');

        if (!$totalPayment) {
            return response()->json(['message' => 'Pagamento não encontrado!']);
        }
        return response()->json(['total' => $totalPayment], 200);
    }
}
