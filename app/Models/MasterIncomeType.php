<?php

namespace App\Models;

use App\Models\Income;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Observers\MasterIncomeTypeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(MasterIncomeTypeObserver::class)]
class MasterIncomeType extends Model
{
    /** @use HasFactory<\Database\Factories\MasterIncomeTypeFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'master_income_types';

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
    ];

    // Relasi balik ke Income
    public function incomes()
    {
        return $this->hasMany(Income::class, 'master_income_type_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
