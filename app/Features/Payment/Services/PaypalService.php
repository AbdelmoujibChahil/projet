<?php

namespace App\Features\Payment\Services;

use App\Models\Commande;
use App\Models\Payment;

class PaypalService
{
    public function verify(
        string $orderID,
        float $amount,
        int $commandeId
    ) {

        /*  Verify PayPal API*/

        $commande = Commande::findOrFail($commandeId);

        $payment = Payment::create([
            'commande_id' => $commande->id,
            'transaction_id' => $orderID,
            'amount' => $amount,
            'payment_method' => 'paypal',
            'statut' => 'paid'
        ]);

        $commande->update([
            'statut' => 'confirmed'
        ]);

        return [
            'message' => 'Paypal payment verified',
            'payment' => $payment
        ];
    }
}