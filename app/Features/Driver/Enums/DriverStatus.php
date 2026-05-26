<?php
namespace App\Features\Driver\Enums;

enum DriverStatus: string
{
    case Active = 'active';
    case OnDelivery = 'on_delivery';
    case Offline = 'offline';
}