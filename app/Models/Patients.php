<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patients extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'mother_name',
        'birthday',
        'cpf',
        'cns',
        'photo'
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function address(): HasOne
    {
        return $this->hasOne(PatientsAddress::class, 'patient_id');
    }

    public function filterPatients(array $only)
    {
        return $this->when($only['search'] ?? false, function ($query, $search) {
            $query->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('mother_name', 'like', "%{$search}%")
                ->orWhere('cpf', 'like', "%{$search}%")
                ->orWhere('cns', 'like', "%{$search}%");
        });
    }
}
