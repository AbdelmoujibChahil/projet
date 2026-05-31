<?php

namespace App\Features\Payment\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Features\Payment\Services\PaymentService;
use App\Features\Payment\Services\StripeService;
use App\Features\Payment\Services\PaypalService;
/**
 * @OA\Tag(
 *     name="Payments",
 *     description="Payment processing endpoints (cash, stripe, paypal)"
 * )
 */
class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private StripeService $stripeService,
        private PaypalService $paypalService
    ) {}

    /*   CASH PAYMENT   */
/**
     * @OA\Post(
     *     path="/payments/cash",
     *     tags={"Payments"},
     *     summary="Cash payment",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"commande_id"},
     *             @OA\Property(property="commande_id", type="integer", example=12)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cash payment successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cash payment completed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function cash(Request $request)
    {
        return response()->json(
            $this->paymentService->cashPayment(
                $request->commande_id
            )
        );
    }

    /*   STRIPE INTENT   */
 /**
     * @OA\Post(
     *     path="/payments/stripe/intent",
     *     tags={"Payments"},
     *     summary="Create Stripe payment intent",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"amount","commande_id"},
     *             @OA\Property(property="amount", type="number", example=120.50),
     *             @OA\Property(property="commande_id", type="integer", example=12)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Stripe intent created",
     *         @OA\JsonContent(
     *             @OA\Property(property="client_secret", type="string"),
     *             @OA\Property(property="payment_intent_id", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
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
 /**
     * @OA\Post(
     *     path="/payments/paypal/verify",
     *     tags={"Payments"},
     *     summary="Verify PayPal payment",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"orderID","amount","commande_id"},
     *             @OA\Property(property="orderID", type="string", example="PAYPAL12345"),
     *             @OA\Property(property="amount", type="number", example=120),
     *             @OA\Property(property="commande_id", type="integer", example=12)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="PayPal verified successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="status", type="string", example="completed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid payment"
     *     )
     * )
     */
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