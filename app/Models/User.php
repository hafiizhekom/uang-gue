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
 * 
 * @property Collection|TicketAttachment[] $ticket_attachments
 * @property Collection|Ticket[] $tickets
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

	// Di dalam class User
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
