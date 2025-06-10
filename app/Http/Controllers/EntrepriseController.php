<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\Employer;
use Illuminate\Http\Request;

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
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
        ]);

        Entreprise::create($request->all());

        return redirect()->route('entreprises.index')->with('success', 'Entreprise créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $employers = Employer::where('entreprise_id', $id)->get();
        $entreprise = Entreprise::where('id', $id)->first();
        $allEntreprises = Entreprise::all('id', 'nomEntreprise');

        return view('gestionEmployer', compact('employers', 'entreprise', 'allEntreprises'));
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
}
