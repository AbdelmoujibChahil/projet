<?php

namespace App\Features\Payment\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Features\Payment\Services\PaymentService;
use App\Features\Payment\Services\StripeService;
use App\Features\Payment\Services\PaypalService;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private StripeService $stripeService,
        private PaypalService $paypalService
    ) {}

    /*   CASH PAYMENT   */

    public function cash(Request $request)
    {
        return response()->json(
            $this->paymentService->cashPayment(
                $request->commande_id
            )
        );
    }

    /*   STRIPE INTENT   */

    public function createStripeIntent(Request $request)
    {
        return response()->json(
            $this->stripeService->createIntent(
                $request->amount,
                $request->commande_id
            )
        );
    }

    /*   VERIFY PAYPAL   */

    public function verifyPaypal(Request $request)
    {
        return response()->json(
            $this->paypalService->verify(
                $request->orderID,
                $request->amount,
                $request->commande_id
            )
        );
    }
}