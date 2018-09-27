<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key', 'name', 'type'
    ];

    /**
     * A setting belongs to a type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(SettingType::class);
    }

    /**
     * A setting has one user setting.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userSetting()
    {
        return $this->hasOne(UserSetting::class);
    }
}
