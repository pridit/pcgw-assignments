<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'assignment_id', 'applicant_id', 'answer_aspects', 'answer_standard'
    ];

    /**
     * An application belongs to an applicant.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    /**
     * An application belongs to an assignment.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * An application has one assign.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function assign()
    {
        // fundamentally an application has no concept of an assign so this
        // unfortunately has to be somewhat hacky to allow us to find an
        // assign on the basis of an application, if it exists
        return $this->hasOne(Assign::class, 'assignment_id', 'assignment_id')
            ->where('applicant_id', $this->applicant->id);
    }

    /**
     * An application which already exists for given applicant and assignment.
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  int $applicant
     * @param  int $assignment
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeApplied($query, $applicant, $assignment)
    {
        return $query->where('applicant_id', $applicant)
            ->where('assignment_id', $assignment);
    }
}
