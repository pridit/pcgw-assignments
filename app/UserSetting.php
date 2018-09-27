<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSetting extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'setting_id', 'enabled'
    ];

    /**
     * A user setting belongs to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A user setting belongs to a setting.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function setting()
    {
        return $this->belongsTo(Setting::class);
    }

    /**
     * Setting which is from the key & enabled.
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  string                               $key
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeForKey($query, $key)
    {
        return $query->whereHas('setting', function ($query) use ($key) {
            $query->where('key', $key)->where('enabled', 1);
        });
    }
}
