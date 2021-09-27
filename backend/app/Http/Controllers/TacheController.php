<?php

namespace App\Http\Controllers;

use App\Models\Etat;
use App\Models\Tache;
use Carbon\Carbon;
use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TacheController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        Tache::majBDD();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Tache::orderBy('created_at', 'desc')
                            ->get()
                            ->toArray();
    }

    public function indexEnCours()
    {
        $taches = Tache::orderBy('created_at', 'desc')
                            ->where('statut', 'En cours')
                            ->where('date_fin', '>=' , Carbon::today())
                            ->get()
                            ->toArray();

        $array = [];
        foreach($taches as $row => $tache){
            $tache = new Tache($tache);
            $array[] = Tache::array($tache);
        }
        return $array;
    }

    public function indexAchevees()
    {
        $taches = Tache::orderBy('created_at', 'desc')
                            ->where('statut', 'Achevee')
                            ->where('date_fin', '>=' , Carbon::today())
                            ->get()
                            ->toArray();

        $array = [];
        foreach($taches as $row => $tache){
            $tache = new Tache($tache);
            $array[] = Tache::array($tache);
        }
        return $array;
    }

    public function indexFaites()
    {
        $taches = Tache::orderBy('created_at', 'desc')
                            ->where('statut', 'Faite')
                            ->where('date_fin', '>=' , Carbon::today())
                            ->get()
                            ->toArray();

        $array = [];
        foreach($taches as $row => $tache){
            $tache = new Tache($tache);
            $array[] = Tache::array($tache);
        }
        return $array;
    }

    public function historique(Carbon $date)
    {
        $taches = Tache::orderBy('created_at', 'desc')
                            ->where('date_enreg', '<=', $date)
                            ->get()
                            ->toArray();
        $array = [];
        foreach($taches as $row => $tache){
            $tache = new Tache($tache);
            $array[] = Tache::toHistoriqueString($tache, $date);
        }
        return $array;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate data
        $data = $request->only('taches');
        $validator = Validator::make($data, [
            'taches' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 500);
        }

        //Request is valid, create new role
        $taches = [];
        foreach($request->taches as $row => $tache){
            $taches[] = [
                'titre' => $tache['titre'],
                'description' => $tache['description'],
                'date_fin' => $tache['date_fin'],
                'date_enreg' => Carbon::now(),
                'user_id' => $this->user->id
            ];
        }

        Tache::insert($taches);
        //Activite created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Taches ajoutées',
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tache  $tache
     * @return \Illuminate\Http\Response
     */
    public function show(Tache $tache)
    {
        if (!$tache) {
            return response()->json([
                'success' => false,
                'message' => 'Désolé, tâche introuvable.'
            ], 400);
        }

        return $tache;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tache  $tache
     * @return \Illuminate\Http\Response
     */
    public function edit(Tache $tache)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tache  $tache
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tache $tache)
    {
        //Validate data
        $data = $request->only('titre', 'description','date_fin');
        $validator = Validator::make($data, [
            'titre' => 'required|string',
            'description' => 'required|string',
            'date_fin' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 500);
        }

        if (!$tache) {
            return response()->json([
                'success' => false,
                'message' => 'Désolé, tâche introuvable.'
            ], 400);
        }

        $tache = $tache->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'date_fin' => $request->date_fin
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tâche mise à jour'
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tache  $tache
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tache $tache)
    {
        if (!$tache) {
            return response()->json([
                'success' => false,
                'message' => 'Désolé, tâche introuvable.'
            ], 400);
        }

        $tache->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tâche supprimée de la liste.'
        ], Response::HTTP_OK);
    }

    public function done(Tache $tache)
    {
        if (!$tache) {
            return response()->json([
                'success' => false,
                'message' => 'Désolé, tâche introuvable.'
            ], 400);
        }

        $tache->update([
            'statut' => 'Faite'
        ]);

        if($tache){
            $tache->etats()->create([
                'date_enreg' => Carbon::now(),
                'tache_id' => $tache['id'],
                'user_id' => $this->user->id,
                'statut' => 'Faite'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tâche mise à jour'
        ], Response::HTTP_OK);
    }

    public function cloture(){
        $taches = Tache::orderBy('created_at', 'desc')
                            ->where('statut', 'En cours')
                            ->where('date_fin', '>' , Carbon::now())
                            ->get()
                            ->toArray();
        $etats = [];
        foreach($taches as $row => $tache){
            $etats[] = [
                'date_enreg' => Carbon::now(),
                'tache_id' => $tache['id'],
                'user_id' => $this->user->id,
                'statut' => $tache['statut']
            ];
        }
        Etat::insert($etats);

        return response()->json([
            'success' => true,
            'message' => 'Sauvregarde effectué'
        ], Response::HTTP_OK);
    }
}
