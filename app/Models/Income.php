<?php

namespace App\Models;

use App\Models\MasterIncomeType;
use App\Models\MasterPeriod;
use App\Models\User;
use App\Models\MasterPayment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Income extends Model
{
    /** @use HasFactory<\Database\Factories\IncomeFactory> */
    use SoftDeletes, HasFactory;

    protected $casts = [
        'amount' => 'float',
        'master_income_type_id' => 'int',
        'user_id' => 'int',
        'date' => 'date',
        'master_period_id' => 'int',
        'note' => 'string',
    ];

    protected $fillable = [
        'user_id', 'date', 'title', 'amount', 'master_income_type_id', 'note', 'master_period_id', 'master_payment_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function type() {
        return $this->belongsTo(MasterIncomeType::class, 'master_income_type_id');
    }

    public function period() { 
        return $this->belongsTo(MasterPeriod::class, 'master_period_id'); 
    }

    public function payment() { 
        return $this->belongsTo(MasterPayment::class, 'master_payment_id'); 
    }
}
