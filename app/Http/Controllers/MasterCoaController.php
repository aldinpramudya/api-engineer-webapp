<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterCoa\StoreMasterCoaRequest;
use App\Models\MasterCoa;
use Illuminate\Http\Request;

class MasterCoaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mastersCoa = MasterCoa::with('categoryCoa')->get();
        return response()->json([
            "message" => "Data Master Fetched",
            "data" => $mastersCoa,
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMasterCoaRequest $request)
    {
        $masterCoa = MasterCoa::create($request->validated());
        return response()->json([
            "message" => "Data Master Saved Succesfully",
            "data" => $masterCoa
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $masterCoa = MasterCoa::with('categoryCoa')->find($id);
        if (!$masterCoa) {
            return response()->json(
                ["message" => "Data Master Not Found"],
                404
            );
        }

        return response()->json([
            "message" => "Data Master Shown",
            "data" => $masterCoa
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $masterCoa = MasterCoa::with('categoryCoa')->find($id);
        if (!$masterCoa) {
            return response()->json(["message" => "Data Master Not Found"], 404);
        }
        $request->validate([
            "code" => "required|integer",
            "name" => "required|string",
            "category_coa_id" => "required|exists:categories_coa,id",
        ]);
        $masterCoa->update($request->all());
        return response()->json([
            "message" => "Data Master Changed Successfully",
            "data" => $masterCoa
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $masterCoa = MasterCoa::with('categoryCoa')->find($id);
        if (!$masterCoa) {
            return response()->json([
                "message" => "Data Master Not Found"
            ], 404);
        }
        $masterCoa->delete();
        return response()->json([
            "message" => "Data Master Deleted Successfully",
            "data" => $masterCoa
        ], 201);
    }
}
