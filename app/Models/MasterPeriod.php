<?php

namespace App\Models;

use App\Models\Income;
use App\Models\Outcome;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterPeriod extends Model
{
    /** @use HasFactory<\Database\Factories\MasterPeriodFactory> */

    use HasFactory, SoftDeletes;
    protected $fillable = ['user_id', 'name', 'start_date', 'end_date', 'is_closed'];

    public function incomes() { 
        return $this->hasMany(Income::class); 
    }
    public function outcomes() { 
        return $this->hasMany(Outcome::class); 
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
