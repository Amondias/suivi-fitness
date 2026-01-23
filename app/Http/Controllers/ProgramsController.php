<?php

namespace App\Http\Controllers;

use App\Models\Programs;
use Illuminate\Http\Request;

class ProgramsController extends Controller
{

    public function index()
    {
        $programs = Programs::all();

        return response()->json([
        'success' => true,
        'data' => $programs
        ]);
    }

    public function create(Request $request)
    {
        $validateData = $request->validate([
            'name'=>'required|max:255',
            'description'=>'nullable',
            'difficulty'=>'required|max:255',
            'duration_weeks'=>'required|integer',
            'goal'=>'required|max:255',
            'is_public'=>'required|boolean',
            'image'=>'nullable|string'
        ]);
        
        $validateData['coach_id'] = auth()->id();
        $program = Programs::create($validateData);

        return response()->json([
            'success' => true,
            'message' => 'Programme créé avec succès',
            'data' => [
                'name' => $program->name,
                'difficulty' => $program->difficulty,
                'duration_weeks'=> $program->duration_weeks,
                'goal'=> $program->goal,
                'is_public'=> $program->is_public
            ]
        ], 201);
    }


    public function show($id)
    {
        
        $program = Programs::findOrFail($id);

        return response()->json([
        'success' => true,
        'data' => $program
        ]);
    }

    public function edit(Request $request,$id)
    {
        $program= Programs::findOrFail($id);
        $validateData = $request->validate([
            'name'=>'nullable|max:255',
            'description'=>'nullable',
            'difficulty'=>'nullable|max:255',
            'duration_weeks'=>'nullable|integer',
            'goal'=>'nullable|max:255',
            'is_public'=>'nullable|boolean',
            'image'=>'nullable|string'
        ]);
        $program->update($validateData);

        return response()->json([
            'success' => true,
            'message' => 'Programme modifié avec succès',
            'data' => $program
        ], 200);
    }


    public function destroy($id)
    {
        $program= Program::findOrFail($id);
        $program->delete();
        return response()->json([
            'message' => 'Programme supprimé avec succès'
        ]);
    }
}
