<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property Collection<Library> $libraries
 * @property Collection<Borrow> $borrows
 * @property string $name
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'membership_number',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function libraries() : BelongsToMany
    {
        return $this->belongsToMany(Library::class, 'user_libraries');
    }

    public function borrows() : HasMany
    {
        return $this->hasMany(Borrow::class);
    }

    public function overdue_borrows() : HasMany
    {
        return $this->hasMany(Borrow::class)->where("due_date", ">", Carbon::now());
    }

    public function current_borrows() : HasMany
    {
        return $this->hasMany(Borrow::class)->whereNull("returned_at");
    }

    public function isAdmin() : bool
    {
        return $this->user_type === UserType::ADMIN->value;
    }
}
