<?php

namespace App\Models;

use App\Models\Outcome;
use App\Models\OutcomeDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterOutcomePayment extends Model
{
    /** @use HasFactory<\Database\Factories\MasterOutcomePaymentFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'master_outcome_payments';

    protected $fillable = [
        'name',
        'slug',
    ];

    // Relasi balik ke Outcome (Parent)
    public function outcomes()
    {
        return $this->hasMany(Outcome::class, 'master_outcome_payment_id');
    }

    // Relasi balik ke Outcome Detail
    public function outcome_details()
    {
        return $this->hasMany(OutcomeDetail::class, 'master_outcome_payment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
