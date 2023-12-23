<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientsAddress extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'patient_id',
        'postal_code',
        'street',
        'number',
        'neighborhood',
        'city',
        'state',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patients::class, 'patient_id');
    }
}
