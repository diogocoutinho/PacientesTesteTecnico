<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientsRequest;
use App\Http\Resources\PatientsResource;
use App\Models\Patients;

class PatientsController extends Controller
{
    public function index()
    {
        return PatientsResource::collection(Patients::all());
    }

    public function store(PatientsRequest $request)
    {
        return new PatientsResource(Patients::create($request->validated()));
    }

    public function show(Patients $patients)
    {
        return new PatientsResource($patients);
    }

    public function update(PatientsRequest $request, Patients $patients)
    {
        $patients->update($request->validated());

        return new PatientsResource($patients);
    }

    public function destroy(Patients $patients)
    {
        $patients->delete();

        return response()->json();
    }
}
