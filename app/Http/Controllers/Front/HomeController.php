<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['dashboard']);
    }

    /**
     * Affiche la page d'accueil
     */
    public function index()
    {
        return view('front.welcome');
    }

    /**
     * Affiche la page de connexion
     */
    public function login(Request $request)
    {
        // Valider le paramètre role
        $role = $request->query('role');
        if (!in_array($role, ['agent', 'citizen'])) {
            return redirect()->route('choose.role');
        }

        return view('front.login', ['selectedRole' => $role]);
    }

    /**
     * Affiche la page d'inscription
     */
    public function register()
    {
        return view('front.register');
    }

    /**
     * Affiche le tableau de bord utilisateur
     */
    public function dashboard()
    {
        return view('front.dashboard');
    }

    /**
     * Affiche la page de sélection du rôle
     */
    public function chooseRole()
    {
        if (Auth::check()) {
            // Si l'utilisateur est déjà connecté, le rediriger selon son rôle
            if (Auth::user()->isAdmin()) {
                return redirect('/admin/dashboard');
            } elseif (Auth::user()->isAgent()) {
                return redirect('/agent/dashboard');
            }
            return redirect('/dashboard');
        }

        return view('front.choose-role');
    }

    /**
     * Traite l'authentification
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'], // Peut être email ou téléphone
            'password' => ['required'],
            'role' => ['required', 'string', 'in:agent,citizen'],
        ]);

        // Détecter si c'est un email ou un numéro de téléphone
        $login = $request->login;
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        
        // Rechercher l'utilisateur par email ou téléphone
        $user = User::where($field, $login)->first();

        if (!$user) {
            return back()->withErrors([
                'login' => 'Les informations d\'identification fournies ne correspondent pas à nos enregistrements.',
            ])->onlyInput('login');
        }

        // Vérifier si le rôle sélectionné correspond au rôle de l'utilisateur
        if ($user->role !== $request->role) {
            return back()->withErrors([
                'role' => 'Vous ne pouvez pas vous connecter avec ce rôle.',
            ])->onlyInput('login', 'role');
        }

        // Tentative d'authentification
        $authCredentials = [
            $field => $login,
            'password' => $request->password,
        ];

        if (Auth::attempt($authCredentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirection selon le rôle
            if (Auth::user()->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            } elseif (Auth::user()->isAgent()) {
                return redirect()->intended('/agent/dashboard');
            } else {
                // Rediriger les citoyens vers leur espace dédié
                return redirect()->intended(route('citizen.dashboard'));
            }
        }

        return back()->withErrors([
            'login' => 'Les informations d\'identification fournies ne correspondent pas à nos enregistrements.',
        ])->onlyInput('login');
    }

    /**
     * Traite l'inscription
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZÀ-ÿ\s\-\']+$/'],
            'prenoms' => ['required', 'string', 'max:155'],
            'date_naissance' => ['required', 'date', 'before:today'],
            'genre' => ['required', 'string', 'in:M,F,Autre'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:20', 'unique:users'], // Rendre le téléphone obligatoire
            'address' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:citizen'],
        ]);

        $user = User::create([
            'nom' => $request->nom,
            'prenoms' => $request->prenoms,
            'date_naissance' => $request->date_naissance,
            'genre' => $request->genre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role,
        ]);

        event(new Registered($user));

        // Envoyer une notification de bienvenue
        $notificationService = new NotificationService();
        $notificationService->sendWelcomeNotification($user);

        Auth::login($user);

        // Redirection selon le rôle
        if ($user->isAgent()) {
            return redirect('/agent/dashboard');
        }

        // Rediriger les citoyens vers leur espace dédié
        return redirect()->route('citizen.dashboard');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
