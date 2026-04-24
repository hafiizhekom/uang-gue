<?php

namespace App\Models;

use App\Models\Outcome;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Observers\MasterOutcomeTypeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(MasterOutcomeTypeObserver::class)]
class MasterOutcomeType extends Model
{
    /** @use HasFactory<\Database\Factories\MasterOutcomeTypeFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'master_outcome_types';

    protected $fillable = [
        'user_id',
        'name',
        'slug',
    ];

    // Relasi balik ke Outcome
    public function outcomes()
    {
        return $this->hasMany(Outcome::class, 'master_outcome_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
