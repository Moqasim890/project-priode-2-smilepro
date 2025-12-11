<?php

namespace App\Http\Controllers;

use App\Models\AdminUserModel as User;

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
}
