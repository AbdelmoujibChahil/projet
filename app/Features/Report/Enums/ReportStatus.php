<?php

namespace App\Features\Report\Enums;

enum ReportStatus: string
{
    case Unread = 'unread';
    case Read = 'read';
    case Resolved = 'resolved';
}