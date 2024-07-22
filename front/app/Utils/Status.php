<?php

namespace App\Utils;

enum Status: string
{
    case RUNNING = 'Running';
    case COMPLETED = 'Completed';
    case ERROR = 'Error';
    case NEVER_RAN = 'Never Ran';
}
