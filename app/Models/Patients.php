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
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    public function address(): HasOne
    {
        return $this->hasOne(PatientsAddress::class);
    }
}
