<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmsRegistrationBatch extends Model
{
    protected $fillable = ['name', 'is_active'];

    public function registrations()
    {
        return $this->hasMany(EmsRegistration::class, 'batch_id');
    }
}