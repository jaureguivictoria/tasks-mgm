<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Project
 * @package App
 *
 * @property int $id
 * @property string $name
 */
class Project extends Model
{
    protected $fillable = [
        'name',
    ];
}
