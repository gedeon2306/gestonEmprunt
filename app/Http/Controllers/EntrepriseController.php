<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\Employer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EntrepriseController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entreprises = Entreprise::all();
        return view('gestionEntreprise', compact('entreprises'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomEntreprise' => 'required|string|max:255',
            'email' => 'required|email|unique:entreprises,email|max:255',
            'password' => 'required|string|confirmed',
        ]);

        // Hash le mot de passe
        $request->merge(['password' => Hash::make($request->password)]);

        Entreprise::create($request->all());

        return redirect()->route('departements.index')->with('success', 'Entreprise créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entreprise $entreprise)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entreprise $entreprise)
    {
        $request->validate([
            'nomEntreprise' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
        ]);

        $entreprise->update($request->all());

        return redirect()->route('entreprises.index')->with('success', 'Entreprise mise à jour avec succès.');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entreprise $entreprise)
    {
        $entreprise->delete();
        
        return redirect()->route('entreprises.index')->with('info', 'Entreprise supprimée avec succès.');

    }

    public function authForm()
    {
        return view('authForm');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $entreprise = Entreprise::where('email', $request->email)->first();

        if ($entreprise && Hash::check($request->password, $entreprise->password)) {
            $request->session()->regenerate();
            session([
                'entrepriseId' => $entreprise->id,
                'entrepriseNom' => $entreprise->nomEntreprise,
            ]);
            return redirect()->route('departements.index')->with('success', 'Connexion réussie.');
        }

        return redirect()->back()->withErrors(['email' => 'Identifiants incorrects.']);
    }

    // Déconnexion
    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('accueil')->with('Success', 'Vous avez été déconnecté avec succès.');
    }
}
