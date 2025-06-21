<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\Entreprise;
use App\Models\Employer;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departements = Departement::where('entreprise_id', session('entrepriseId'))->get();
        return view('gestionDepartement', compact('departements'));
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
            'nomDepartement' => 'required|string|max:100',
            'chefDepartement' => 'required|string|max:100',
        ]);
        $entrepriseId = session('entrepriseId');
        
        Departement::create([
            'nomDepartement' => $request->nomDepartement,
            'chefDepartement' => $request->chefDepartement,
            'entreprise_id' => $entrepriseId,
        ]);

        return redirect()->route('departements.index')->with('success', 'Entreprise créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $query = Departement::where('entreprise_id', session('entrepriseId'))->where('id', $id)->exists();

        if (!$query) {
            return redirect()->back()->with('error', 'Requete invalide.');
        }

        $employers = Employer::where('departement_id', $id)->get();
        $departement = Departement::where('id', $id)->first();
        $allDepartements = Departement::where('entreprise_id', session('entrepriseId'))->get();
        // $allDepartements = Departement::all('id', 'nomDepartement');

        return view('gestionEmployer', compact('employers', 'departement', 'allDepartements'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departement $departement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Departement $departement)
    {
        $query = Departement::where('entreprise_id', session('entrepriseId'))->where('id', $departement->id)->exists();

        if (!$query) {
            return redirect()->back()->with('error', 'Requete invalide.');
        }

        $request->validate([
            'nomDepartement' => 'required|string|max:100',
            'chefDepartement' => 'required|string|max:100',
        ]);

        $departement->update($request->all());

        return redirect()->route('departements.index')->with('success', 'Département mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departement $departement)
    {
        $query = Departement::where('entreprise_id', session('entrepriseId'))->where('id', $departement->id)->exists();

        if (!$query) {
            return redirect()->back()->with('error', 'Requete invalide.');
        }

        $departement->delete();
        
        return redirect()->route('departements.index')->with('info', 'Département supprimé avec succès.');
    }
}
