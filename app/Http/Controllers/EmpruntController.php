<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\Employer;
use Illuminate\Http\Request;

class EmpruntController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('emprunt-login');
    }
    
    public function verif(Request $request)
    {
        $employer = Employer::where('telephone', $request->telephone)->first();

        if ($employer) {
            return redirect()->route('emprunts.redirection', ['id' => $employer->id]);
        }else {
            return back()->with('warning', 'Numéro de téléphone invalide');
        }
    }
    
    public function redirection($id, $montant = null)
    {
        $employer = Employer::FindOrFail($id);

        if ($montant) {
            return view('loan-confirmation', compact('employer', 'montant'));
        } else {
            return view('loan-request', compact('employer'));
        }

    }

    public function confirm(Request $request)
    {
        $employer = Employer::FindOrFail($request->employer_id);

        if ($employer->reste == 0) {
            return back()->with('warning', 'Vous ne pouvez plus emprunter pour le moment');
        }elseif ($employer->reste < $request->motant) {
            return back()->with('warning', 'Limite maximale depassée');
        }else {
            $montant = $request->motant;
            return redirect()->route('emprunts.redirection', ['id' => $employer->id, 'montant' => $montant]);
        }
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
        $employer = Employer::FindOrFail($request->employer_id);
        
        $request->validate([
            'motant' => 'required|integer',
        ]);

        if ($employer->counter != 2) {
            if ($employer->pin != $request->pin) {
                if ($employer->counter == 0) {
                    $employer->counter +=1;
                    $employer->save();
                    return back()->with('warning', 'PIN incorrect, il vous reste une tentative');
                } else {
                    $employer->counter +=1;
                    $employer->save();
                    return back()->with('warning', 'Deuxième tentatives échouées, votre compte à été bloqué, contactez votre superviseur');
                }
            }else {
                Emprunt::create([
                    'motant' => $request->motant,
                    'employer_id' => $employer->id,
                ]);

                $employer->counter = 0;
                $employer->reste -= $request->motant;
                $employer->save();

                return redirect()->route('emprunts.success', ['id' => $employer->id, 'montant' => $request->motant]);
            }
        } else {
            return back()->with('warning', 'Désolé, votre compte à été bloqué, contactez votre supperviseur');
        }
    }

    public function success($id, $montant)
    {
        $employer = Employer::FindOrFail($id);

        return view('loan-success', 
            [
                'montant' => $montant,
                'employer' => $employer,
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Emprunt $emprunt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Emprunt $emprunt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Emprunt $emprunt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Emprunt $emprunt)
    {
        //
    }
}
