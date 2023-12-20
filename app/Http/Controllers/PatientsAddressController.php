<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientsAddressRequest;
use App\Http\Resources\PatientsAddressResource;
use App\Models\PatientsAddress;

class PatientsAddressController extends Controller
{
    public function index()
    {
        return PatientsAddressResource::collection(PatientsAddress::all());
    }

    public function store(PatientsAddressRequest $request)
    {
        return new PatientsAddressResource(PatientsAddress::create($request->validated()));
    }

    public function show(PatientsAddress $patientsAddress)
    {
        return new PatientsAddressResource($patientsAddress);
    }

    public function update(PatientsAddressRequest $request, PatientsAddress $patientsAddress)
    {
        $patientsAddress->update($request->validated());

        return new PatientsAddressResource($patientsAddress);
    }

    public function destroy(PatientsAddress $patientsAddress)
    {
        $patientsAddress->delete();

        return response()->json();
    }
}
