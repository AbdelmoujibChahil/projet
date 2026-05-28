<?php

namespace App\Features\Rating\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRatingRequest;
use App\Models\Rating;
use App\Models\Plat;
use Illuminate\Http\JsonResponse;

class RatingController extends Controller
{
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