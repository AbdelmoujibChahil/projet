<?php

namespace App\Features\Rating\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRatingRequest;
use App\Models\Rating;
use App\Models\Plat;
use Illuminate\Http\JsonResponse;
/**
 * @OA\Tag(
 *     name="Ratings",
 *     description="Product rating management"
 * )
 */
class RatingController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/ratings",
     *     tags={"Ratings"},
     *     summary="Submit or update a product rating",
     *     description="Allows an authenticated user to rate a product or update an existing rating.",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"plat_id","rating"},
     *             @OA\Property(
     *                 property="plat_id",
     *                 type="integer",
     *                 example=1,
     *                 description="Product ID"
     *             ),
     *             @OA\Property(
     *                 property="rating",
     *                 type="integer",
     *                 minimum=1,
     *                 maximum=5,
     *                 example=5,
     *                 description="Rating value between 1 and 5"
     *             ),
     *             @OA\Property(
     *                 property="feedback",
     *                 type="string",
     *                 example="Excellent product and fast delivery"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rating submitted successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */

    /* STORE RATING */
    public function store(StoreRatingRequest $request): JsonResponse {

        $rating = Rating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'plat_id' => $request->plat_id,
            ],
            [
                'rating' => $request->rating,
                'feedback' => $request->feedback,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Rating submitted successfully',
            'rating' => $rating
        ], 201);
    }


    /**
     * @OA\Get(
     *     path="/api/v1/ratings/{plat}/average",
     *     tags={"Ratings"},
     *     summary="Get product rating statistics",
     *     description="Returns the average rating and total number of ratings for a product.",
     *     @OA\Parameter(
     *         name="plat",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rating statistics retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     */

    /* PRODUCT RATING STATS */
    public function averageRating(Plat $plat): JsonResponse {

        return response()->json([
            'success' => true,
            'data' => [
                'average' => round($plat->ratings()->avg('rating') ?? 0,1),
                'count' => $plat->ratings()->count()
            ]
        ]);
    }
}