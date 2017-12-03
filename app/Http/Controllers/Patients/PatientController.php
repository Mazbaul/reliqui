<?php

namespace App\Http\Controllers\Patients;

use App\Models\Patient\Patient;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view-patients|add-patients|edit-patients|delete-patients']);
    }

    public function index()
    {
        return view('patients.index', [
            'patients' => Patient::with('user')->paginate(6)
        ]);
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
