<?php

namespace App\Models;

use App\Models\Outcome;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterOutcomeCategory extends Model
{
    /** @use HasFactory<\Database\Factories\MasterOutcomeCategoryFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'master_outcome_categories';

    protected $fillable = [
        'name',
        'slug',
    ];

    // Relasi balik ke Outcome
    public function outcomes()
    {
        return $this->hasMany(Outcome::class, 'master_outcome_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
