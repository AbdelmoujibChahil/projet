<?php

namespace App\Features\Payment\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripeService
{
    public function createIntent($amount, $commandeId)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $intent = PaymentIntent::create([
            'amount' => $amount * 100,
            'currency' => 'usd',

            'metadata' => [
                'commande_id' => $commandeId
            ],

            'automatic_payment_methods' => [
                'enabled' => true
            ]
        ]);

        return [
            'clientSecret' => $intent->client_secret
        ];
    }
}