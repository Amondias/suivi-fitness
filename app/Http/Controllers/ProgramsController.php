<?php

namespace App\Http\Controllers;

use App\Models\Programs;
use App\Models\UserPrograms;
use Illuminate\Http\Request;
use App\Models\ProgramExercises;

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

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name'=>'required|max:255',
            'description'=>'nullable',
            'difficulty'=>'required|max:255|in:beginner, intermediate, advanced',
            'duration_weeks'=>'required|integer',
            'goal'=>'required|max:255|in:weight_loss,muscle_gain,endurance, flexibility, general_fitness',
            'is_public'=>'required|boolean|default:false',
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

    public function update(Request $request,$id)
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
        $program= Programs::findOrFail($id);
        $program->delete();
        return response()->json([
            'message' => 'Programme supprimé avec succès'
        ]);
    }
    public function addExercise(Request $request, $id){

        $program = Programs::findOrFail($id);

        $validateData = $request->validate([
            'exercise_id' => 'required|exists:exercises,id',
            'sets' => 'required|integer|min:1',
            'reps' => 'nullable|integer|min:1',
            'rest_seconds' => 'nullable|integer|min:0',
            'order'  => 'nullable|integer|min:0',
            'day_of_week' =>'nullable|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'
        ]);

        $programExercise = ProgramExercises::create([
            'program_id'   => $program->id,
            'exercise_id' => $validateData['exercise_id'],
            'sets' => $validateData['sets'],
            'reps' => $validateData['reps'],
            'rest_seconds' => $validateData['rest_seconds'] ?? null,
            'order' => $validateData['order'] ?? null,
            'day_of_week' => $validateData['day_of_week'] ?? null,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Exercice ajouté au programme',
            'data' => $programExercise
        ], 201);
    }   


   public function subscribe($id){
        $user = auth()->user();

        $program = Programs::findOrFail($id);

        $alreadySubscribed = UserPrograms::where('user_id', $user->id)
            ->where('program_id', $program->id)
            ->exists();

        if ($alreadySubscribed) {
            return response()->json([
                'success' => false,
                'message' => 'Déjà inscrit à ce programme'
            ], 409);
        }

        UserPrograms::create([
            'user_id' => $user->id,
            'program_id' => $program->id,
            'started_at' => now(),
            'status' => 'active'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Inscription réussie'
        ], 201);
    }

}
