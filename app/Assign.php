<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assign extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'assignment_id', 'applicant_id', 'assigned_by'
    ];

    /**
     * An assign belongs to an applicant.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    /**
     * An assign belongs to an assignment.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * An assign has many applications via assignment.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function applications()
    {
        return $this->hasManyThrough(Application::class, Assignment::class, 'id', 'assignment_id', 'assignment_id');
    }

    /**
     * Assigns which are not to the 'System' internal applicant.
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotSystem($query)
    {
        return $query->where('applicant_id', '!=', 1);
    }
}
