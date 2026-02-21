<?php

namespace App\Models;

use App\Models\OutcomeDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterOutcomeDetailTag extends Model
{
    /** @use HasFactory<\Database\Factories\MasterOutcomeDetailTagFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug'];

    public function outcome_details() {
        return $this->belongsToMany(OutcomeDetail::class, 'outcome_detail_tag');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
