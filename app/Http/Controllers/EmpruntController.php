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
            $request->session()->regenerate();
            session([
                'employerId' => $employer->id
            ]);
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

    public function logout(Request $request){
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('emprunts.index');
    }
}
