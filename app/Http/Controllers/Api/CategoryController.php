<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('name', 'ASC');

        if (!empty($request->keyword)) {
            $categories = $categories->where('name', 'LIKE', '%' . $request->keyword . '%');
        }

        if (!empty($request->type)) {
            $categories = $categories->whereType($request->type);
        }

        $categories = $categories->paginate(25);
        $categories = $categories->each(fn($category) => $category->append(['total_transaction_amount', 'total_transaction_amount_formatted']));
        $categories = $categories->map(fn($category) => new CategoryResource($category));

        return response()->success('Successfully get categories', $categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        $categoryRequest = $request->validated();

        try {
            $category = Category::create($categoryRequest);
            return response()->success('Category successfully created', new CategoryResource($category));

        } catch (Throwable $t) {
            return response()->failed('Failed to create category');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);
        if (empty($category)) {
            return response()->failed('Category not found', NULL, JsonResponse::HTTP_NOT_FOUND);
        }

        return response()->success('Successfully get category', new CategoryResource($category));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $category = Category::find($id);
        if (empty($category)) {
            return response()->failed('Category not found', NULL, JsonResponse::HTTP_NOT_FOUND);
        }

        $categoryRequest = $request->validated();

        try {
            $category->update($categoryRequest);
            return response()->success('Category successfully updated', new CategoryResource($category));

        } catch (Throwable $t) {
            return response()->failed('Failed to update category');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if (empty($category)) {
            return response()->failed('Category not found', NULL, JsonResponse::HTTP_NOT_FOUND);
        }

        try {
            $category->delete();
            return response()->success('Category successfully deleted');

        } catch (Throwable $t) {
            return response()->failed('Failed to delete category');
        }
    }
}
