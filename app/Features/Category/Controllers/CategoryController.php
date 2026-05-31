<?php
namespace App\Features\Category\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
 * @OA\Get(
 *     path="/api/v1/categories",
 *     summary="Get all categories",
 *     description="Retrieve all product categories ordered by name",
 *     tags={"Categories"},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Categories retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="nom", type="string", example="Pizza"),
 *                     @OA\Property(property="created_at", type="string", example="2026-01-01T10:00:00Z"),
 *                     @OA\Property(property="updated_at", type="string", example="2026-01-01T10:00:00Z")
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Error fetching categories"
 *     )
 * )
 */
    public function index(): JsonResponse
    {
        try {
            $categories = Category::orderBy('nom')->get();

            return response()->json([
                'success' => true,
                'data' => $categories,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching categories',
            ], 500);
        }
    }    
}