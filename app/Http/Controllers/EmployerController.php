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

        $query = Departement::where('entreprise_id', session('entrepriseId'))->where('id', $request->departement_id)->exists();

        if (!$query) {
            return redirect()->back()->with('error', 'Requete invalide.');
        }

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
        // $departements = Departement::where('entreprise_id', session(entrepriseId))->get;
        // $idsDepartements = $departements->pluck('id')->toArray();
        // $employerAppartient = in_array($employer->departement_id, $idsDepartements);

        $employerAppartient = Departement::where('id', $employer->departement_id)
                                            ->where('entreprise_id', session('entrepriseId'))
                                            ->exists();

        if (!$employerAppartient) {
            return redirect()->back()->with('error', 'Requete invalide.');
        }


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

        $employerAppartient = Departement::where('id', $employer->departement_id)
                                            ->where('entreprise_id', session('entrepriseId'))
                                            ->exists();

        if (!$employerAppartient) {
            return redirect()->back()->with('error', 'Requete invalide.');
        }

        $request->validate([
            'nomComplet' => 'required|string|max:100',
            'telephone' => 'required|string|max:20|unique:employers,telephone' . $employer->id,
            'genre' => 'required|string|max:1|in:M,F,A',
            'seuil' => 'required|integer',
            'pin' => 'required|integer',
        ]);
        
        $employer->update($request->all());

        return back()->with('success', 'Employé mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employer $employer)
    {
        $employerAppartient = Departement::where('id', $employer->departement_id)
                                            ->where('entreprise_id', session('entrepriseId'))
                                            ->exists();

        if (!$employerAppartient) {
            return redirect()->back()->with('error', 'Requete invalide.');
        }

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

        $employerAppartient = Departement::where('id', $employer->departement_id)
                                            ->where('entreprise_id', session('entrepriseId'))
                                            ->exists();

        if (!$employerAppartient) {
            return redirect()->back()->with('error', 'Requete invalide.');
        }

        $employer->reste = $employer->seuil;
        $employer->save();
        
        return back()->with('info', 'Opération effectuée avec succès.');
    }

    public function migreEmployer(Request $request, $id)
    {
        $departement = Departement::FindOrFail($request->departement_id);
        $employer = Employer::FindOrFail($id);

        $employerAppartient = Departement::where('id', $employer->departement_id)
                                            ->where('entreprise_id', session('entrepriseId'))
                                            ->exists();

        if (!$employerAppartient) {
            return redirect()->back()->with('error', 'Requete invalide.');
        }

        $employer->departement_id = $departement->id;
        $employer->save();
        
        return back()->with('info', 'Opération effectuée avec succès.');
    }

    public function disable(Request $request, $id)
    {
        $employer = Employer::FindOrFail($id);

        $employerAppartient = Departement::where('id', $employer->departement_id)
                                            ->where('entreprise_id', session('entrepriseId'))
                                            ->exists();

        if (!$employerAppartient) {
            return redirect()->back()->with('error', 'Requete invalide.');
        }

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
