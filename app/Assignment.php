<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Expression as Raw;

class Assignment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'app_id', 'url', 'path_report', 'report', 'standard',
        'open', 'release_at', 'expire_at'
    ];

    /**
     * An assignment has many applications.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    /**
     * An assignment has many assignees via applicants.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function assignees()
    {
        return $this->hasManyThrough(Applicant::class, Assign::class, 'assignment_id', 'id', 'id', 'applicant_id')
            ->where('applicants.name', '!=', 'System')
            ->orderBy('name');
    }

    /**
     * An assignment has many assigns.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assigns()
    {
        return $this->hasMany(Assign::class);
    }

    /**
     * Assignments which are considered available, and ordered based on
     * whether a report is required and expiration of given assignment.
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->selectRaw('*, DATEDIFF(expire_at, CURDATE()) AS diff')
            ->open()
            ->orderBy('report', 'desc')
            ->orderBy(new Raw('CASE WHEN diff < 1 THEN 1 ELSE 0 END, diff'));
    }

    /**
     * Assignments which are open.
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
        return $query->where('open', 1);
    }

    /**
     * Assignments which are closed.
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotOpen($query)
    {
        return $query->where('open', 0);
    }

    /**
     * Assignments which are expired, and closed.
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
        return $query->notOpen()->orWhere(function($query) {
            $query->whereNotNull('expire_at')
                ->where('expire_at', '<', date('Y-m-d'));
        });
    }

    /**
     * Assignments which are unexpired, and open.
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotExpired($query)
    {
        return $query->open()->where(function($query) {
            $query->where('expire_at', '>', date('Y-m-d'))
                ->orWhereNull('expire_at');
        });
    }

    /**
     * Toggle the open state of an assignment.
     *
     * @return boolean
     */
    public function toggleOpen()
    {
        return $this->update([
            'open' => (1 - $this->open)
        ]);
    }

    /**
     * Toggle the completion of an assignment (standard reached).
     *
     * @return boolean
     */
    public function toggleComplete()
    {
        return $this->update([
            'standard' => (1 - $this->standard)
        ]);
    }
}
