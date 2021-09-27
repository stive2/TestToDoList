<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tache extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'titre', 'description', 'date_fin', 'user_id', 'statut', 'date_enreg'
    ];

    /**
     * The states of task at different time.
     *
     * @var array
     */
    public function etats()
    {
        return $this->hasMany(Etat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    static function array(Tache $tache)
    {
        $user = User::find($tache->user_id);
        $array = [
            'id' => $tache->id,
            'titre' => $tache->titre,
            'description' => $tache->description,
            'date_fin' => $tache->date_fin,
            'user' => $user->nom,
            'statut' => $tache->statut,
            'date_enreg' => $tache->date_enreg
        ];
        return $array;
    }

    static function toHistoriqueString(Tache $tache, Carbon $date)
    {
        $etat = $tache->etats()->where('date_enreg', $date)->first();
        $user = User::find($tache->user_id);
        if($etat){
            $tache = [
                'titre' => $tache->titre,
                'description' => $tache->description,
                'date_enreg' => $tache->date_enreg,
                'date_fin' => $tache->date_fin,
                'statut' => $etat->statut,
                'user' => $user->nom,
            ];
        } else {
            $tache = [
                'titre' => $tache->titre,
                'description' => $tache->description,
                'date_enreg' => $tache->date_enreg,
                'date_fin' => $tache->date_fin,
                'statut' => $tache->statut,
                'user' => $user->nom,
            ];
        }

        return $tache;
    }

    static function majBDD(){
        $taches = Tache::orderBy('created_at', 'desc')
                            ->where('statut', 'En cours')
                            ->where('date_fin', '<' , Carbon::now())
                            ->get()
                            ->toArray();

        foreach($taches as $row => $tache){
            $tache = Tache::find($tache['id']);
            $tache = $tache->update([
                'statut' => 'Achevée'
            ]);
        }
    }
}
