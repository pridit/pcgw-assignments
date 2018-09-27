<?php

namespace App;

use App\Token;
use App\UserHash;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'first_name', 'last_name', 'username', 'password', 'email',
        'disabled'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * A user can have many user hashes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hash()
    {
        return $this->hasMany(UserHash::class);
    }

    /**
     * A user can have many user settings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function settings()
    {
        return $this->hasMany(UserSetting::class);
    }

    /**
     * A user can have tokens they have generated.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    /**
     * User is considered active.
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('disabled', 0);
    }

    /**
     * Toggle disabling of user to restrict access.
     *
     * @return boolean
     */
    public function toggleDisabled()
    {
        return $this->update([
            'disabled' => (1 - $this->disabled)
        ]);
    }
}
