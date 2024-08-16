<?php

namespace App\Enums;

enum StatusEnum: string
{
    case SCHEDULED = 'scheduled';
    case CANCELED = 'canceled';
}
