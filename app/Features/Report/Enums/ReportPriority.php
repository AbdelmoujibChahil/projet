<?php

namespace App\Features\Report\Enums;

enum ReportPriority: string
{
    case High = 'high';
    case Medium = 'medium';
    case Low = 'low';
}