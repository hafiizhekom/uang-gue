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
    protected $fillable = [
        'user_id', 
        'name', 
        'start_date', 
        'end_date'
    ];

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

    protected static function booted()
    {
        static::deleting(function ($model) {
            if (!$model->isForceDeleting()) {
                $model->incomes()->delete();
                $model->outcomes()->delete();
            }
        });

        static::restoring(function ($model) {
            $model->incomes()->withTrashed()->restore();
            $model->outcomes()->withTrashed()->restore();
        });
    }
}
