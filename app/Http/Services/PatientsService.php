<?php

namespace App\Http\Services;

use App\Exports\PatientExport;
use App\Exports\PatientsExport;
use App\Models\Patients;
use App\Models\PatientsAddress;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PatientsService
{
    public static function getPatients(): ?Collection
    {
        if (Cache::has('patients')) {
            \Log::info('Cache');
            return Cache::get('patients');
        } else {
            \Log::info('DB');
            $patients = Patients::with('address')->get();
            Cache::put('patients', $patients);
            return $patients;
        }
    }

    public static function createPatient(array $data): Patients
    {
        try {
            DB::beginTransaction();
            $patient = Patients::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'mother_name' => $data['mother_name'],
                'birthday' => $data['birthday'],
                'cpf' => $data['cpf'],
                'cns' => $data['cns'],
                'photo' => $data['photo'],
            ]);
            if ($patient) {
                $data['patient_id'] = $patient->id;
                $patientAddress = PatientsAddress::create($data);
            }
            if ($patientAddress) {
                DB::commit();
                return $patient->with('address')->find($patient->id);
            } else {
                throw new \Exception('Failed to create patient address');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public static function updatePatient(array $data, string $id): Patients
    {
        try {
            DB::beginTransaction();
            $patients = Patients::with('address')->find($id);
            $patients->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'mother_name' => $data['mother_name'],
                'birthday' => $data['birthday'],
                'cpf' => $data['cpf'],
                'cns' => $data['cns'],
                'photo' => $data['photo'],
            ]);
            $patients->address->update($data);
            DB::commit();
            return $patients->with('address')->find($patients->id);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public static function export(string $fileType = 'csv'): BinaryFileResponse
    {
        return Excel::download(new PatientsExport, "patients.{$fileType}");
    }
}
