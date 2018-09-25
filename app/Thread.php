<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class Thread extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id', 'title', 'slug', 'is_pinned', 'is_closed'
    ];

    /**
     * A thread has many posts.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * A thread has an original post.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function original()
    {
        return $this->hasOne(Post::class);
    }

    /**
     * Threads which are closed, not pinned, and updated within the last 7 days.
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeArchival($query)
    {
        return $query->where('is_closed', 1)
            ->where('is_pinned', 0)
            ->where('updated_at', '>', Carbon::now()->subDays(7)->format('Y-m-d H:i:s'));
    }

    /**
     * Threads which are classed as viewable by open state, unless pinned, or
     * they meet the criteria of archival (due to being closed).
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeViewable($query)
    {
        return $query->where('is_closed', 0)->orWhere('is_pinned', 1)->orWhere(function ($query) {
            $query->archival();
        });
    }

    /**
     * Threads which are ordered by pinned, closed, & last updated.
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeStructured($query)
    {
        return $query->orderByDesc('is_pinned')
            ->orderBy('is_closed')
            ->orderByDesc('updated_at');
    }

    /**
     * Threads which are considered open.
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
        return $query->where('is_closed', 0);
    }

    /**
     * Thread that is closed and not a stickied.
     *
     * @return bool
     */
    public function getClosedNotPinnedAttribute()
    {
        return $this->is_closed && !$this->is_pinned;
    }

    /**
     * Toggle pinning of a thread to increase prominence.
     *
     * @return mixed
     */
    public function togglePinned()
    {
        $this->timestamps = false;

        return $this->update([
            'is_pinned' => (1 - $this->is_pinned)
        ]);
    }

    /**
     * Toggle closing of a thread to prevent further replies.
     *
     * @return mixed
     */
    public function toggleClosed()
    {
        $this->timestamps = false;

        return $this->update([
            'is_closed' => (1 - $this->is_closed)
        ]);
    }
}
