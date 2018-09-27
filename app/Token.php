<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Token extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'hash', 'used_by'
    ];

    /**
     * A token belongs to a user.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A token has a user it has been allocated to.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function allocation()
    {
        return $this->hasOne(User::class, 'id', 'used_by');
    }

    /**
     * Tokens which are valid for use.
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsValid($query)
    {
        return $query->notUsed();
    }

    /**
     * Tokens which have not been used in creation of an account.
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotUsed($query)
    {
        return $query->whereNull('used_by');
    }
}
