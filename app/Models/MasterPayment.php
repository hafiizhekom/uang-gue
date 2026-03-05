<?php

namespace App\Models;

use App\Models\Income;
use App\Models\Outcome;
use App\Models\OutcomeDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterPayment extends Model
{
    /** @use HasFactory<\Database\Factories\MasterPaymentFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'master_payments';

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'balance',
    ];

    // Relasi balik ke Outcome (Parent)
    public function outcomes()
    {
        return $this->hasMany(Outcome::class, 'master_payment_id');
    }

    // Relasi balik ke Outcome Detail
    public function outcome_details()
    {
        return $this->hasMany(OutcomeDetail::class, 'master_payment_id');
    }

    public function incomes()
    {
        return $this->hasMany(Income::class, 'master_payment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
