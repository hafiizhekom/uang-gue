<?php

namespace App\Models;

use App\Models\MasterOutcomeCategory;
use App\Models\MasterOutcomeType;
use App\Models\MasterPayment;
use App\Models\OutcomeDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outcome extends Model
{
    /** @use HasFactory<\Database\Factories\OutcomeFactory> */
    use SoftDeletes, HasFactory;

    protected $casts = [
        'amount' => 'float',
        'has_detail' => 'boolean',
        'user_id' => 'int',
        'master_outcome_category_id' => 'int',
        'master_outcome_type_id' => 'int',
        'master_payment_id' => 'int',
        'date' => 'date',
        'master_period_id' => 'int',
        'note' => 'string',
    ];

    protected $fillable = [
        'user_id', 'date', 'title', 'amount', 'has_detail', 'master_outcome_category_id', 'master_outcome_type_id', 'master_payment_id', 'master_period_id', 'master_payment_id', 'note'
    ];

    public function user() { return $this->belongsTo(User::class); }

    public function category() { 
        return $this->belongsTo(MasterOutcomeCategory::class, 'master_outcome_category_id'); 
    }

    public function type() { 
        return $this->belongsTo(MasterOutcomeType::class, 'master_outcome_type_id'); 
    }

    public function payment() { 
        return $this->belongsTo(MasterPayment::class, 'master_payment_id'); 
    }

    public function details() {
        return $this->hasMany(OutcomeDetail::class);
    }

    public function period() { 
        return $this->belongsTo(MasterPeriod::class, 'master_period_id'); 
    }

    protected static function booted()
    {
        static::deleting(function ($outcome) {
            if (!$outcome->isForceDeleting()) {
                $outcome->details()->delete();
            }
        });

        static::restoring(function ($outcome) {
            $outcome->details()->withTrashed()->restore();
        });
    }
}
