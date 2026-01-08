<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\AdminUserModel as User;
use PHPUnit\Event\Code\IssueTrigger\SelfTrigger;

/**
 * AdminController
 * 
 * Controller voor admin functionaliteit.
 * Alleen toegankelijk voor gebruikers met admin rol.
 */
class AdminController extends Controller
{
    /**
     * Toon admin dashboard.
     * 
     * Hoofdpagina voor admins met overzicht van beschikbare beheer functies.
     * 
     * @return \Illuminate\View\View Admin dashboard view
     */
    function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Toon gebruikersoverzicht met paginering.
     * 
     * Haalt gebruikers op via stored procedure met aanpasbare
     * paginagrootte en pagina nummer via query parameters.
     * 
     * @return \Illuminate\View\View Users index view met gebruikers lijst en paginering data
     */
    function users()
    {
        // Haal paginering parameters op uit query string, met defaults
        $perPage = (int) request()->query('per_page', 10);
        $page = max(1, (int) request()->query('page', 1));
        
        // Bereken offset voor database query
        $offset = ($page - 1) * $perPage;

        // Haal gebruikers op voor huidige pagina
        $users = User::GetAllUsers($perPage, $offset);

        // Tel totaal aantal gebruikers voor paginering berekening
        $total = User::SP_CountAllUsers();

        // Bouw paginering data voor view
        $pagination = [
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => (int) ceil($total / $perPage),
        ];

        // Stuur data naar view
        return view('admin.users.index', compact('users', 'pagination'));
    }

    /**
     * Toon patienten overzicht.
     * 
     * Haalt patienten op via stored procedure.
     */
    function patienten()
    {
        $patienten = User::SP_GetAllPatienten();
        // dd($patienten);
        // Stuur data naar view
        return view('admin.patienten.index', [
            'patienten' => $patienten
        ]);
    }


    /**
     * Toon feedback overzicht.
     * 
     * Haalt feedback op via stored procedure.
     */
    function feedback()
    {
        $feedback = User::SP_GetAllfeedback();
        // dd($feedback);
        // Stuur data naar view
        return view('admin.feedback.index', [
            'feedback' => $feedback
        ]);
    }

    function berichten()
    {
        $berichten = User::SP_GetAllBerichten();
        $naam      = Auth::user()->name;
        // dd($name);
        // Stuur data naar view
        return view('admin.berichten.index', [
            'berichten' => $berichten,
            'naam'      => $naam
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function createPatient()
    {
        return view('admin.patienten.create');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function storePatient(Request $request)
    {
        $data = $request->validate([
             'persoonid'     => 'required'
            ,'nummer'        => 'required'
            ,'medischdosier' => 'nullable|file'
            ,'opmerking'     => 'nullable|string|max:255'
        ]);

        User::SP_CreatePatient($data);

        return redirect()
                ->route('admin.patienten.create')
                ->with('success', 'Patient succesvol aangemaakt.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.berichten.create');
    }

    static public function GetPatientidByEmail($email)
    {
        $id = User::SP_GetPatientidByEmail($email);
        
        if(!empty($id)) {
            return $id;
        } else {
            return NULL;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /**
         * Om de bericht te verzenden naar de juiste medewerkerId
         * gaan we de email moeten matchen met een id in de database
         * dit doen we door @var string $validated['Email']
         * eerst te zoeken in de database met @method FindEmail($Email)
         * daarna kijken we in de row naar de persoonid
         * en dan checken we welke gebruikerid/userid daarbij hoort
         * en dat is de juis
         */
        $data = $request->validate([
             'email'        => 'required|string|max:80' 
            ,'bericht'      => 'required|string|max:1500'
        ]);

        $data['medewerkerid'] = NULL; // Moet eigenlijk -> Auth::Id(); maar er zijn geen medewerkers en praktijkmanagement is blijkbaar geen medewerker

        $data['patientid'] = Self::GetPatientidByEmail($data['email'])->id;


        // dd($data['patientid']);

        if (empty($data['medewerkerid'])) {
            $data['medewerkerid'] = NULL;
        }

        if ($data['patientid'] === NULL) {
            return redirect()
                ->route('admin.berichten.index')
                ->with('error', 'Deze gebruiker bestaat niet, check of je de email wel correct hebt ingevoerd.');
        }

        // dd($data);

        User::SP_CreateBericht($data);

        return redirect()->route('admin.berichten.index')->with('success', 'Bericht succesvol verzonden!');
    }
}
