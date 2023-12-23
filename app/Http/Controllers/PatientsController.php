<?php

namespace App\Http\Controllers;

use App\Events\JobCompleted;
use App\Http\Requests\PatientsRequest;
use App\Http\Requests\PatientsUpdateRequest;
use App\Http\Resources\PatientsResource;
use App\Http\Services\PatientsService;
use App\Jobs\ImportPatientsJob;
use App\Models\Patients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Inertia\Inertia;

class PatientsController extends Controller
{
    public function index()
    {
        return Inertia::render('Patients/Index', [
            'patients' => PatientsService::getPatients()
        ]);
    }

    public function store(PatientsRequest $request)
    {
        try {
            $validated = $request->validated();
            $patient = PatientsService::createPatient($validated);
            return to_route('patients.index');
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function show($id)
    {
        return Inertia::render('Patients/Show', [
            'patient' => Patients::with('address')->find($id)
        ]);
    }

    public function update(PatientsUpdateRequest $request, string $id)
    {
        try {
            $patients = PatientsService::updatePatient($request->validated(), $id);
            return to_route('patients.index', $patients);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function destroy(Patients $patients)
    {
        $patients->delete();

        return response()->json();
    }

    public function edit($id)
    {
        return Inertia::render('Patients/Edit', [
            'patient' => Patients::with('address')->find($id)
        ]);
    }

    public function getPatients()
    {
        return PatientsResource::collection(PatientsService::getPatients());
    }

    public function uploadPatientImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time().'.'.$request->file->extension();

        $fullPath = public_path('images').'/'.$imageName;

        if (file_exists($fullPath)) {
            return response()->json(['error' => 'Image already exists'], 400);
        }

        $path = $request->file->move(public_path('images'), $imageName);

        $assetPath = asset('images/'.$imageName);

        return response()->json(['url' => $assetPath]);
    }

    public function export($type)
    {
        return PatientsService::export($type);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file')->store('imports');

        ImportPatientsJob::dispatch($file);

        return response()->json(['message' => 'Importação iniciada.']);
    }
}
