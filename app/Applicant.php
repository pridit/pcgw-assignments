<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Applicant extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'blacklisted'
    ];

    /**
     * An applicant has many applications.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    /**
     * An applicant has many assigns.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assigns()
    {
        return $this->hasMany(Assign::class);
    }

    /**
     * An applicant has many assignments via assigns.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assignments()
    {
        return $this->hasManyThrough(Assignment::class, Assign::class, 'applicant_id', 'id');
    }

    /**
     * An applicant has one dxdiag.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function dxdiag()
    {
        return $this->hasOne(Dxdiag::class);
    }

    /**
     * Applicant can have outstanding assignments.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function outstanding()
    {
        return $this->assigns()->whereHas('assignment', function ($query) {
            $query->where('standard', 0)->orWhere(function ($query) {
                $query->where('report', 1)->whereNull('path_report');
            });
        });
    }

    /**
     * Toggle blacklisting of an applicant to restrict their usage.
     *
     * @return mixed
     */
    public function toggleBlacklist()
    {
        return $this->update([
            'blacklisted' => (1 - $this->blacklisted)
        ]);
    }
}
