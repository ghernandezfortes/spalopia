<?php

namespace Spalopia;

use Illuminate\Database\Eloquent\Model;
use Spalopia\Service;

class Schedule extends Model
{
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
