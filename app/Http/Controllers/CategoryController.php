<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $categories = Category::query()->whereNull('user_id')
            ->orWhere('user_id', $request->user()->id)
            ->get();

        return response()->json([
            'data' => $categories,
            'success' => true
        ]);
    }

    public function store(CategoryStoreRequest $request): JsonResponse
    {
        $category = $request->user()->categories()
            ->create($request->validated());

        return response()->json([
            'data' => $category,
            'success' => true,
            'message' => "Custom category created successfully!"
        ]);
    }

    /**
     * @throws Exception
     */
    public function update(CategoryStoreRequest $request, $categoryId): JsonResponse
    {
        $category = Category::where('user_id', $request->user()->id)
            ->where('id', $categoryId)
            ->first();


        if (empty($category)) {
            throw new Exception('Resource does not exists!', 400);
        }

        $category->name = $request->name;
        $category->save();

        return response()->json([
            'data' => $category,
            'success' => true,
            'message' => "Custom category updated successfully!"
        ]);
    }

    /**
     * @throws Exception
     */
    public function destroy(Request $request, $categoryId): JsonResponse
    {
        $categoryDeleted = Category::where('user_id', $request->user()->id)
            ->where('id', $categoryId)
            ->delete();


        if (!$categoryDeleted) {
            throw new Exception('Resource does not exists!', 400);
        }

        return response()->json([
            'success' => true,
            'message' => "Custom category deleted successfully!"
        ]);
    }
}
