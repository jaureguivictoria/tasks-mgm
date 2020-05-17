<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Task
 * @package App
 *
 * @property string $name
 * @property int $priority
 * @property Project $project
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Task extends Model
{
    protected $fillable = [
      'name',
      'priority',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

}
