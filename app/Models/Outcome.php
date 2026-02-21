<?php

namespace App\Models;

use App\Models\MasterOutcomeCategory;
use App\Models\MasterOutcomeHutang;
use App\Models\MasterOutcomePayment;
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
        'master_outcome_hutang_id' => 'int',
        'master_outcome_payment_id' => 'int',
        'date' => 'date'
    ];

    protected $fillable = [
        'user_id', 'date', 'title', 'amount', 'has_detail',
        'master_outcome_category_id', 'master_outcome_hutang_id', 'master_outcome_payment_id'
    ];

    public function user() { return $this->belongsTo(User::class); }

    public function category() { 
        return $this->belongsTo(MasterOutcomeCategory::class, 'master_outcome_category_id'); 
    }

    public function hutang() { 
        return $this->belongsTo(MasterOutcomeHutang::class, 'master_outcome_hutang_id'); 
    }

    public function payment() { 
        return $this->belongsTo(MasterOutcomePayment::class, 'master_outcome_payment_id'); 
    }

    public function details() {
        return $this->hasMany(OutcomeDetail::class);
    }
}
