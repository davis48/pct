<?php

namespace App\Http\Controllers\Front;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        return view('front.profile.edit');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Log les données soumises pour débogage
        \Log::info('Données du formulaire de mise à jour de profil:', $request->all());

        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prenoms' => ['required', 'string', 'max:155'],
            'date_naissance' => ['required', 'date', 'before:today'],
            'genre' => ['required', 'string', 'in:M,F,Autre'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'profile_photo' => ['nullable', 'image', 'max:2048'], // max 2MB
        ]);

        // Log les données validées
        \Log::info('Données validées:', $validated);

        if ($request->hasFile('profile_photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($user->profile_photo) {
                Storage::delete('public/' . $user->profile_photo);
            }

            // Stocker la nouvelle photo
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo = $path;
        }

        $user->nom = $validated['nom'];
        $user->prenoms = $validated['prenoms'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->address = $validated['address'] ?? null;
        
        // Correction pour date_naissance et genre
        $user->date_naissance = $validated['date_naissance'];
        $user->genre = $validated['genre'];
        
        // Log les données avant enregistrement
        \Log::info('Données utilisateur avant sauvegarde:', $user->toArray());

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();
        
        // Log après sauvegarde
        \Log::info('Utilisateur sauvegardé avec ID: ' . $user->id);

        return redirect()->route('profile.edit')
            ->with('success', 'Profil mis à jour avec succès.');
    }
}
