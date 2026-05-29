<?php

namespace App\Features\Payment\Services;

use App\Models\Commande;
use App\Models\Payment;

class PaymentService
{
    /*    CASH PAYMENT    */

    public function cashPayment(int $commandeId)
    {
        $commande = Commande::findOrFail($commandeId);

        if ($commande->payment) {
            abort(400, 'Payment already exists');
        }

        $payment = Payment::create([
            'commande_id' => $commande->id,
            'amount' => $commande->prix_total,
            'payment_method' => 'cash',
            'statut' => 'pending',
        ]);

        return [
            'message' => 'Cash payment created',
            'payment' => $payment
        ];
    }
}