<?php

declare(strict_types=1);

namespace Geocode\Exceptions;

use Http\Client\Exception;

class InvalidArgument extends \InvalidArgumentException implements Exception
{

}