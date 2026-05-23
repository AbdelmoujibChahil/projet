<?php

namespace App\Features\Order\Enums;

enum OrderStatus: string
{
    case Pending = 'Pending';

    case OnDelivery = 'On Delivery';

    case Completed = 'Completed';
}