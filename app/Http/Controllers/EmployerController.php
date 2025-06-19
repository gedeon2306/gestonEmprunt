<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\Emprunt;
use App\Models\Departement;
use Illuminate\Http\Request;

class EmployerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 
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

        $departement = Departement::FindOrFail($request->departement_id);

        $request->validate([
            'nomComplet' => 'required|string|max:100',
            'telephone' => 'required|string|max:20|unique:employers,telephone',
            'genre' => 'required|string|max:1|in:M,F,A',
            'seuil' => 'required|integer',
        ]);

        $pin = rand(1000, 9999);

        Employer::create([
            'nomComplet' => $request->nomComplet,
            'telephone' => $request->telephone,
            'genre' => $request->genre,
            'seuil' => $request->seuil,
            'reste' => $request->seuil,
            'pin' => $pin,
            'departement_id' => $departement->id
        ]);

        $nb = Employer::where('departement_id', $departement->id)->count();
        $departement->nbSalarier = $nb;
        $departement->save();

        return redirect()->route('departements.show', $departement->id)->with('success', 'Employé créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employer $employer)
    {
        $emprunts = Emprunt::where('employer_id', $employer->id)->get();
        $totalEmprunt = 0;
        foreach ($emprunts as $emprunt){
            $totalEmprunt += $emprunt->motant;
        }

        return view('historique', compact('emprunts', 'employer', 'totalEmprunt'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employer $employer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employer $employer)
    {
        $request->validate([
            'nomComplet' => 'required|string|max:100',
            'telephone' => 'required|string|max:20|unique:employers,telephone' . $employer->id,
            'genre' => 'required|string|max:1|in:M,F,A',
            'seuil' => 'required|integer',
            'pin' => 'required|integer',
        ]);
        // dd($request->all());
        $employer->update($request->all());

        return back()->with('success', 'Employé mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employer $employer)
    {
        $departementId = $employer->departement_id;
        $employer->delete();

        $departement = Departement::FindOrFail($departementId);
        $nb = Employer::where('departement_id', $departement->id)->count();
        $departement->nbSalarier = $nb;
        $departement->save();
        
        return back()->with('info', 'Employé supprimé avec succès.');
    }

    public function reset($id)
    {
        $employer = Employer::FindOrFail($id);
        $employer->reste = $employer->seuil;
        $employer->save();
        
        return back()->with('info', 'Opération effectuée avec succès.');
    }

    public function migreEmployer(Request $request, $id)
    {
        $departement = Departement::FindOrFail($request->departement_id);
        $employer = Employer::FindOrFail($id);
        $employer->departement_id = $departement->id;
        $employer->save();
        
        return back()->with('info', 'Opération effectuée avec succès.');
    }

    public function disable(Request $request, $id)
    {
        $employer = Employer::FindOrFail($id);
        if ($employer->counter != 2) {
            $employer->counter = 2;
            $employer->save();
            return back()->with('info', 'Compte désactivé avec succès.');
        }else {
            $employer->counter = 0;
            $employer->save();
            return back()->with('info', 'Compte réactivé avec succès.');
        }
    }
}
