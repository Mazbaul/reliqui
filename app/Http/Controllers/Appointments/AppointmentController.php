<?php

namespace App\Http\Controllers\Appointments;

use App\Http\Controllers\Controller;
use App\Models\Appointment\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:view-appointments|add-appointments|edit-appointments|delete-appointments']);
    }

    public function index()
    {
        $appointments = Appointment::with('doctor.schedule')->where('status', '=', 'active')->oldest()->get();

        return view('appointments.index', compact('appointments'));
    }

    public function today()
    {
        $today = \Carbon\Carbon::now()->format('Y-m-d');

        $appointments = Appointment::with('doctor.schedule', 'user')->where([
            ['date_of_visit', '=', $today],
            ['status', '=', 'active']
        ])->oldest()->get();
        
        return view('appointments.today', compact('appointments'));
    }

    public function cancelAppointment(Appointment $appointment)
    {
        $appointment->fill(['status' => 'cancel']);
        $appointment->save();

        flash('Successful! Your appointment canceled')->important();

        return redirect()->back();
    }

    public function cancel()
    {
        $appointments = Appointment::with('doctor.schedule', 'user')->where('status', 'cancel')->latest()->get();

        return view('appointments.cancel', compact('appointments'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $appointment_number = time();

        $appointment = Appointment::create([
            'user_id' => Auth::user()->id,
            'doctor_id' => $request->get('doctor_id'),
            'appointment_number' => $appointment_number,
            'date_of_visit' => $request->get('date_of_visit'),
            'queue_number' => $request->get('queue_number'),
            'patient_condition' => $request->get('patient_condition'),
            'status' => 'active',
        ]);

        flash('Successful! Your appointment was created')->important();

        return redirect('/people/appointments');
    }

    public function show(Appointment $appointment)
    {
        $appointment = $appointment->load('doctor', 'user.patient');

        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        return view('appointments.edit', compact('appointment'));
    }

    public function update(Appointment $appointment)
    {
        $appointment->fill(['status' => 'confirmed']);
        
        // dd($appointment);
        $appointment->save();

        flash('Successful! Appointment Confirmed')->important();

        return redirect('/appointments');
    }

    public function destroy($id)
    {
        //
    }
}
