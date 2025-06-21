<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\Employer;
use App\Models\Departement;
use App\Models\Emprunt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EntrepriseController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entreprise = Entreprise::where('id', session('entrepriseId'))->first();
        $departements = Departement::where('entreprise_id', session('entrepriseId'))->get();
        $employers = collect();

        // Données pour les graphiques dynamiques
        $labels = [];
        $loanData = [];
        $employeeData = [];

        foreach ($departements as $departement) {
            $labels[] = $departement->nomDepartement;
            // Nombre de salariés
            $nbEmployes = Employer::where('departement_id', $departement->id)->count();
            $employeeData[] = $nbEmployes;
            // Montant total emprunté par les salariés de ce département
            $employerIds = Employer::where('departement_id', $departement->id)->pluck('id');
            $totalEmprunt = 0;
            if ($employerIds->count() > 0) {
                $totalEmprunt = Emprunt::whereIn('employer_id', $employerIds)->sum('motant');
            }
            $loanData[] = $totalEmprunt;
        }

        foreach ($departements as $departement) {
            $employers = $employers->merge(Employer::where('departement_id', $departement->id)->get());
        }

        return view('dashboard', compact('entreprise', 'departements', 'employers', 'labels', 'loanData', 'employeeData'));
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

        $entreprise = Entreprise::create($request->all());

        $request->session()->invalidate();
        $request->session()->regenerate();
        session([
            'entrepriseId' => $entreprise->id,
            'entrepriseNom' => $entreprise->nomEntreprise,
        ]);

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
        if ($entreprise->id != session('entrepriseId')) {
            return redirect()->back()->with('error', 'Requete invalide.');
        }
        
        if (!$request->newPassword) {
            $request->validate([
                'nomEntreprise' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ]);

            if ($entreprise && Hash::check($request->password, $entreprise->password)) {
                $entreprise->update([
                    'nomEntreprise' => $request->nomEntreprise,
                    'email' => $request->email,
                ]);

                session()->put('entrepriseNom', $request->nomEntreprise);

                return redirect()->back()->with('success', 'Informations de l\'entreprise mises à jour !');
            }
            
            return redirect()->back()->with('error', 'Mot de passe incorrect');

        }else{
            $request->validate([
                'nomEntreprise' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'newPassword' => 'required|string|confirmed',
            ]);
            
            if (!Hash::check($request->newPassword, $entreprise->password)) {

                $request->merge(['newPassword' => Hash::make($request->newPassword)]);

                $entreprise->update([
                    'nomEntreprise' => $request->nomEntreprise,
                    'email' => $request->email,
                    'password' => $request->newPassword,
                ]);

                $request->session()->invalidate();
                
                return redirect()->route('entreprises.authentification')->with('info', 'Entreprise mise à jour avec succès, Reconnectez-vous !');
            }

            return redirect()->back()->with('error', 'Le mot de passe actuel et le nouveau son identique.');
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Entreprise $entreprise)
    {
        if ($entreprise->id != session('entrepriseId')) {
            return redirect()->back()->with('error', 'Requete invalide.');
        }

        $entreprise->delete();

        $request->session()->invalidate();
        
        return redirect()->route('entreprises.authentification')->with('info', 'Entreprise supprimée avec succès.');

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

        return redirect()->back()->with('error','Identifiants incorrects.');
    }

    // Déconnexion
    public function logout(Request $request)
    {
        $request->session()->invalidate();

        return redirect()->route('entreprises.authentification')->with('success', 'Vous avez été déconnecté avec succès.');
    }
}
