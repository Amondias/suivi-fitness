<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ClientController extends Controller
{
    public function index(Request $request){
        $query = User::where('role', 'client');
         
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
                });
                return response()->json($query->get());
            }


            if ($request->filled('gender')) {
                $query->where('gender', $request->gender);
                return response()->json($query->get());
            }

            if ($request->filled('status')) {
                $query->whereHas('subscriptions', function ($q) use ($request) {
                    if ($request->status === 'active') {
                        $q->where('end_date', '>=', now());
                    }

                    if ($request->status === 'expired') {
                        $q->where('end_date', '<', now());
                    }
                });
                return response()->json($query->get());
            }


        $clients = $query->with('subscriptions')->get();
        $total = $clients->count();
        $activeSubscriptions = $clients->filter(function ($client) {
            return $client->subscriptions->first() && $client->subscriptions->first()->end_date >= now();
        })->count();
        $expiredSubscriptions = $clients->filter(function ($client) {
            return $client->subscriptions->first() && $client->subscriptions->first()->end_date < now();
        })->count();

        $data = $clients->map(function ($client) {
            $subscription = $client->subscriptions->first();
            return [
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone,
                'gender' => $client->gender,
                'subscription' => $subscription ? [
                    'plan' => $subscription->subscriptionPlan->name ?? 'N/A',
                    'status' => $subscription->end_date >= now() ? 'active' : 'expired',
                    'end_date' => $subscription->end_date->format('Y-m-d')
                ] : null
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'total' => $total,
                'active_subscriptions' => $activeSubscriptions,
                'expired_subscriptions' => $expiredSubscriptions
            ]
        ]);
    }

    public function store(Request $request){
        $validateData = $request->validate([
            'name'=>'required|max:255',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8',
            'phone'=>'required|max:255',
            'address'=>'required|max:255',
            'date_of_birth'=>'required|date',
            'gender'=>'required|in:male,female'
        ]);
        
        $validateData['role'] = 'client';
        $validateData['password'] = bcrypt($validateData['password']);

        $user = User::create($validateData);

        return response()->json([
            'success' => true,
            'message' => 'Client créé avec succès',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'gender' => $user->gender,
                'date_of_birth' => $user->date_of_birth,
                'role' => $user->role
            ]
        ], 201);
    }

    public function show($id){
        $client = User::where('role', 'client')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'message' => 'Données du client',
            'data' => [
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone,
                'address' => $client->address,
                'gender' => $client->gender,
                'date_of_birth' => $client->date_of_birth,
                'role' => $client->role
            ]
        ], 200);
    }

    public function update(Request $request, $id){
        $client = User::where('role','client')->findOrFail($id);
        $validateData = $request->validate([
            'name'=>'nullable|max:255',
            'email'=>'nullable|email|unique:users,email,'.$id,
            'password'=>'nullable|min:8',
            'phone'=>'nullable|max:255',
            'address'=>'nullable|max:255',
            'date_of_birth'=>'nullable|date',
            'gender'=>'nullable|in:male,female'
        ]);
        
        // Hashage du password en cas de remplissement
        if($request->filled('password')) {
            $validateData['password'] = bcrypt($validateData['password']);
        } else {
            unset($validateData['password']);
        }

        $client->update($validateData);

        return response()->json([
            'success' => true,
            'message' => 'Client modifié avec succès',
            'data' => [
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone,
                'address' => $client->address,
                'gender' => $client->gender,
                'date_of_birth' => $client->date_of_birth,
                'role' => $client->role
            ]
        ], 200);
    }
    public function destroy($id){
        $client = User::where('role','client')->findOrFail($id);
        $client->delete();
        return response()->json([
            'message' => 'Client supprimé avec succès'
        ]);
    }

    public function subscriptions($id){
       $client = User::where('role','client')->findOrFail($id);
        return $client->subscriptions;
    }

    public function payments($id){
       $client = User::where('role','client')->findOrFail($id);
        return $client->payments;
    }


    public function active(){
        $query = User::where('role','client');

        $query->whereHas('subscriptions', function($q){
            $q->where('end_date', '>=', now());
        });

        return response()->json($query->get());
    }

    public function expired(){
        $query = User::where('role','client');

        $query->whereHas('subscriptions', function($q){
            $q->where('end_date', '<', now());
        });

        return response()->json($query->get());
    }
}
