<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\MasterIncomeType;
use App\Models\User;

class Income extends Model
{
    /** @use HasFactory<\Database\Factories\IncomeFactory> */
    use HasFactory;
    use SoftDeletes, HasFactory;

    protected $casts = [
        'amount' => 'float',
        'master_income_type_id' => 'int',
        'user_id' => 'int',
        'date' => 'date'
    ];

    protected $fillable = [
        'user_id', 'date', 'title', 'amount', 'master_income_type_id', 'note'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function type() {
        return $this->belongsTo(MasterIncomeType::class, 'master_income_type_id');
    }
}
