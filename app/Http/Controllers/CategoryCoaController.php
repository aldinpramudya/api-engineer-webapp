<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryCoa\StoreCategoryRequest;
use App\Models\CategoryCoa;
use Illuminate\Http\Request;

class CategoryCoaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categoriesCoa = CategoryCoa::all();
        return response()->json([
            "message" => "Data Category Fetched",
            "data" => $categoriesCoa
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $categoryCoa = CategoryCoa::create($request->validated());
        return response()->json([
            "message" => "Data Category Saved Succesfully",
            "data" => $categoryCoa
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categoryCoa = CategoryCoa::find($id);

        if (!$categoryCoa) {
            return response()->json(
                ["message" => "Category Not Found"],
                404
            );
        }

        return response()->json([
            "message" => "Data Shown",
            "data" => $categoryCoa
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    // ON HTTPS Request : POST ditambahkan key value _method = PUT
    public function update(Request $request, $id)
    {
        $categoryCoaChanges = CategoryCoa::find($id);
        if (!$categoryCoaChanges) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $request->validate([
            'name_category' => 'required|string|max:255',
        ]);
        $categoryCoaChanges->update($request->all());
        return response()->json([
            "message" => "Data Category Changed Succesfully",
            "data" => $categoryCoaChanges
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categoryCoa = CategoryCoa::find($id);

        if (!$categoryCoa) {
            return response()->json([
                'message' => "Category Not Found"
            ], 404);
        }

        $categoryCoa->delete();

        return response()->json([
            "message" => "Data Category Deleted Succesfully",
            "data" => $categoryCoa
        ], 201);
    }
}
