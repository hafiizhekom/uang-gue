<?php

namespace App\Models;

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
        'master_outcome_payment_id' => 'int',
        'date' => 'date',
        'tags' => 'array' // Supaya otomatis jadi JSON di DB & Array di Laravel/Next.js
    ];

    protected $fillable = [
        'outcome_id', 'date', 'title', 'amount', 
        'master_outcome_payment_id', 'note', 'tags'
    ];

    protected static function booted() {
        static::saved(function ($detail) {
            $detail->outcome->update(['amount' => $detail->outcome->details()->sum('amount')]);
        });
    }

    public function outcome() {
        return $this->belongsTo(Outcome::class);
    }

    public function payment() {
        return $this->belongsTo(MasterOutcomePayment::class, 'master_outcome_payment_id');
    }

    public function tags() {
        return $this->belongsToMany(MasterOutcomeDetailTag::class, 'outcome_detail_tag');
    }
}
