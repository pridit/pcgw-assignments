<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SettingType extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * A setting type has many settings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function settings()
    {
        return $this->hasMany(Setting::class);
    }
}
