<?php

namespace App\Http\Controllers;

use App\Models\Members_arc;
use App\Models\Team_arc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MembersArcController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ['eventName' => 'ARC 2023'];
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
    public function store(Request $request): array
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:55',
            'telephone' => 'required|string|max:15',
            'lien_linked_in' => 'nullable|url|max:255',
            'lien_git_hub' => 'nullable|url|max:255',
            'photo_carte_identite' => 'required|image',
            'email' => 'required|string|email|max:255|unique:members_arc',
            'password' => 'required|string|min:8',
            'etudiant' => 'required|boolean',
            'residence' => 'required',
        ]);

        $memberarc = new Members_arc();
        $nbr_team = 1;
        // if request has team id
        if ($request->has('id_team')) {
            $tid = $request->get('id_team');

            // inscription N mmbr , N != 1

            $team = Team_arc::where('tid', (int) $request->input('id_team'))->first();
            // var_dump($team );
            if ($team === null) {
                return abort(404, 'Team not found'); // 404 not found
            }
            if ((int) $team->nbr_team === 5) {
                return abort(401, 'le nombre maximal de participants de cette team a ete atteint');
            }
            $team->nbr_team = $team->nbr_team + 1;
            $team->save();
            $memberarc->id_team = $team->tid;
        } else {
            // inscription mmbr 1
            // generate team id
            generate_id:
            $tid = rand(1, 9999999); // Random int
            if (Team_arc::where('tid', $tid)->get()->count() > 0) {
                goto generate_id;
            }
            $memberarc->id_team = $tid;

            // create team

            Team_arc::create([
                'nom_team' => $request->input('nom_team'),
                'region_team' => $request->input('region_team'),
                'nbr_team' => $nbr_team,
                'accepted_team' => $request->input('accepted_team'),
                'tid' => $tid,
            ]);

            $memberarc->id_team = $tid;
        }

        $memberarc->full_name = $request->input('full_name');
        // $memberarc->id_team = $request->input('id_team');
        $memberarc->email = $request->input('email');
        $memberarc->password = Hash::make($request->input('password'));
        $memberarc->telephone = $request->input('telephone');
        $memberarc->etudiant = $request->input('etudiant');
        $memberarc->fonction = $request->input('fonction');
        $memberarc->lien_git_hub = $request->input('lien_git_hub');
        $memberarc->lien_linked_in = $request->input('lien_linked_in');
        $memberarc->skills = $request->get('skills');
        $memberarc->proj = $request->input('proj');
        $memberarc->motivation = $request->input('motivation');
        $memberarc->residence = $request->get('residence');

        // photo_carte_identite : asem a file
        // arc_users_ids : asem dossier li yploadi lih
        // public : c'est le disk (kayn public, private , wkayn fel cloud nrmlm (exmpl: amazon s3))

        $finaImagePath = $request->file('photo_carte_identite')->store('arc_users_ids', 'public');

        $memberarc->photo_carte_identite = $finaImagePath;

        $result = $memberarc->save();

        return  ['team_code' => $tid];
        // return $memberarc;
        // return redirect()->action('${App\Http\Controllers\HomeController@index}', ['parameterKey' => 'value']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
