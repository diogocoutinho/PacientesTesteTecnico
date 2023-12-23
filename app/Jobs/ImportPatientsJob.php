<?php

namespace App\Jobs;

use App\Events\JobCompleted;
use App\Http\Services\PatientsService;
use App\Models\Patients;
use App\Models\PatientsAddress;
use App\Rules\CnsRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ImportPatientsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    /**
     * @throws \Throwable
     */
    public function handle()
    {
        $data = array_map('str_getcsv', file(Storage::path($this->file)));

        foreach ($data as $row) {
            $patientData = [
                'first_name' => $row[0],
                'last_name' => $row[1],
                'mother_name' => $row[2],
                'birthday' => $row[3],
                'cpf' => $row[4],
                'cns' => $row[5],
                'photo' => $row[6],
                'postal_code' => $row[7],
                'street' => $row[8],
                'number' => $row[9],
                'complement' => $row[10],
                'neighborhood' => $row[11],
                'city' => $row[12],
                'state' => $row[13],
            ];

            $validator = Validator::make($patientData, [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'mother_name' => 'required|max:255',
                'birthday' => 'required|date',
                'cpf' => 'required|size:11|regex:/[0-9]{11}/',
                'cns' => 'required|size:15',
                'photo' => 'nullable|url',
                'postal_code' => 'required|digits:8',
                'street' => 'required|max:255',
                'number' => 'required|max:255',
                'complement' => 'nullable|max:255',
                'neighborhood' => 'required|max:255',
                'city' => 'required|max:255',
                'state' => 'required|max:255',
            ]);
            if ($validator->fails()) {
                \Log::error('Failed to import patient', $validator->errors()->all());
                continue;
            }
            $patient = PatientsService::createPatient($patientData);

            if ($patient) {
                event(new JobCompleted('Importação concluída.'));
            }
        }
        Storage::delete($this->file);
    }
}
