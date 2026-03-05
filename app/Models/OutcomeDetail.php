<?php

namespace App\Models;

use App\Models\MasterOutcomeDetailTag;
use App\Models\MasterPayment;
use App\Models\Outcome;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutcomeDetail extends Model
{
    /** @use HasFactory<\Database\Factories\OutcomeDetailFactory> */
    use HasFactory, SoftDeletes;

    protected $casts = [
        'amount' => 'float',
        'outcome_id' => 'int',
        'master_payment_id' => 'int',
        'date' => 'date',
        'note' => 'string',
    ];

    protected $fillable = [
        'outcome_id', 'date', 'title', 'amount', 
        'master_payment_id', 'note', 'tags'
    ];

    public function outcome() {
        return $this->belongsTo(Outcome::class);
    }

    public function payment() {
        return $this->belongsTo(MasterPayment::class, 'master_payment_id');
    }

    public function tags() {
        return $this->belongsToMany(MasterOutcomeDetailTag::class, 'outcome_detail_tag');
    }

    protected static function booted() {
        static::saved(function ($detail) {
            $detail->outcome->update(['amount' => $detail->outcome->details()->sum('amount')]);
        });

        static::deleted(function ($detail) {
            if ($detail->outcome && !$detail->outcome->isForceDeleting()) {
                $detail->outcome->update(['amount' => $detail->outcome->details()->sum('amount')]);
            }
        });
    }
}
