<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Models\Income;
use App\Models\MasterPeriod;
use App\Models\Outcome;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as AuthenticatableUser;
/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
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
class User extends AuthenticatableUser
{
	use SoftDeletes;
	use HasApiTokens, HasFactory, Notifiable;
	
	protected $table = 'users';

	protected $casts = [
		'email_verified_at' => 'datetime'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'email_verified_at',
		'password',
		'remember_token'
	];

	public function projects(): BelongsToMany
	{
		return $this->belongsToMany(Project::class, 'project_user')
			->withPivot('role')
			->withTimestamps();
	}

	public function created_projects(): HasMany
	{
		return $this->hasMany(Project::class, 'created_by');
	}

	public function belongsToProject($projectId): bool
	{
		if (!$projectId) {
			return false;
		}
		return $this->projects()->where('projects.id', $projectId)->exists();
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

	public function outcome_detail_tags(): HasMany
	{
		return $this->hasMany(MasterOutcomeDetailTag::class);
	}

	public function payments(): HasMany
	{
		return $this->hasMany(MasterPayment::class);
	}
	
	public function incomes(): HasMany
	{
		return $this->hasMany(Income::class);
	}

	public function outcomes(): HasMany
	{
		return $this->hasMany(Outcome::class);
	}

	public function periods(): HasMany
	{
		return $this->hasMany(MasterPeriod::class);
	}
}
