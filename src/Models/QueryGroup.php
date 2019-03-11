<?php

namespace Geocoding\Laravel\Models;

use Illuminate\Database\Eloquent\Model;

class QueryGroup extends Model
{
    const GROUP_BY_ADDRESS = 'address';

    const GROUP_BY_CITY = 'city';
}
