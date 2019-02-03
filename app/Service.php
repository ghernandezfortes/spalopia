<?php

namespace Spalopia;

use Illuminate\Database\Eloquent\Model;
use Spalopia\Schedule;

class Service extends Model
{
    public function shedules()
    {
        return $this->hasMany(Schedule::class, 'schedule_id');
    }
}
