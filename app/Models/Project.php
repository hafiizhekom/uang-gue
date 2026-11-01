<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Activity;

/**
 * Class Project
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property User $creator
 * @property Collection|User[] $users
 * @property Collection|Income[] $incomes
 * @property Collection|Outcome[] $outcomes
 * @property Collection|MasterPeriod[] $periods
 * @property Collection|MasterIncomeType[] $income_types
 * @property Collection|MasterOutcomeCategory[] $outcome_categories
 * @property Collection|MasterOutcomeType[] $outcome_types
 * @property Collection|MasterOutcomeDetailTag[] $outcome_detail_tags
 * @property Collection|MasterPayment[] $payments
 *
 * @package App\Models
 */
class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'projects';

    protected $fillable = [
        'name',
        'description',
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function periods(): HasMany
    {
        return $this->hasMany(MasterPeriod::class);
    }

    public function income_types(): HasMany
    {
        return $this->hasMany(MasterIncomeType::class);
    }

    public function outcome_categories(): HasMany
    {
        return $this->hasMany(MasterOutcomeCategory::class);
    }

    public function outcome_types(): HasMany
    {
        return $this->hasMany(MasterOutcomeType::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(MasterPayment::class);
    }

    public function outcome_detail_tags(): HasMany
    {
        return $this->hasMany(MasterOutcomeDetailTag::class);
    }

    public function incomes(): HasMany
    {
        return $this->hasMany(Income::class);
    }

    public function outcomes(): HasMany
    {
        return $this->hasMany(Outcome::class);
    }

    public function activity_logs(): HasMany
    {
        return $this->hasMany(Activity::class, 'project_id');
    }

    protected static function booted()
    {
        static::deleting(function ($model) {
            if (!$model->isForceDeleting()) {
                $model->periods()->delete();
                $model->income_types()->delete();
                $model->outcome_categories()->delete();
                $model->outcome_types()->delete();
                $model->outcome_detail_tags()->delete();
                $model->payments()->delete();
                $model->incomes()->delete();
                $model->outcomes()->delete();
            }
        });

        static::restoring(function ($model) {
            $model->periods()->withTrashed()->restore();
            $model->income_types()->withTrashed()->restore();
            $model->outcome_categories()->withTrashed()->restore();
            $model->outcome_types()->withTrashed()->restore();
            $model->outcome_detail_tags()->withTrashed()->restore();
            $model->payments()->withTrashed()->restore();
            $model->incomes()->withTrashed()->restore();
            $model->outcomes()->withTrashed()->restore();
        });
    }
}
