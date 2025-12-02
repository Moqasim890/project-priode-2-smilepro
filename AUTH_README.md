# SmilePro - Authenticatie & Rollensysteem

## Overzicht
Compleet authenticatiesysteem met rollenbeheer voor SmilePro.

## Geïmplementeerde Features

### 1. Database Structuur
- **roles table**: Opslag van rollen (admin, medewerker, klant)
- **role_user pivot table**: Many-to-many relatie tussen users en roles

### 2. Models
- **User Model**: Uitgebreid met role relaties en helper methods
  - `hasRole($role)`: Check of user specifieke rol heeft
  - `hasAnyRole($roles)`: Check of user één van de rollen heeft
  - `assignRole($role)`: Wijs rol toe aan user
  - `removeRole($role)`: Verwijder rol van user

- **Role Model**: Voor rollenbeheer met user relatie

### 3. Controllers
- **LoginController**: Handles login functionaliteit
- **RegisterController**: Handles registratie (nieuwe users krijgen automatisch 'klant' rol)
- **LogoutController**: Handles logout functionaliteit

### 4. Middleware
- **CheckRole**: Controleert of gebruiker juiste rol heeft voor toegang
  - Gebruik: `->middleware('role:admin')`
  - Meerdere rollen: `->middleware('role:admin,medewerker')`

### 5. Routes
```php
// Guest routes
GET  /login      - Login formulier
POST /login      - Login verwerking
GET  /register   - Registratie formulier
POST /register   - Registratie verwerking

// Authenticated routes
POST /logout     - Logout

// Admin routes (role:admin)
GET /admin/dashboard

// Medewerker routes (role:admin,medewerker)
GET /medewerker/dashboard
```

### 6. Views
- `resources/views/auth/login.blade.php` - Login pagina
- `resources/views/auth/register.blade.php` - Registratie pagina
- `resources/views/home.blade.php` - Home pagina met navigatie
- `resources/views/admin/dashboard.blade.php` - Admin dashboard
- `resources/views/medewerker/dashboard.blade.php` - Medewerker dashboard

## Installatie & Setup

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed Database met Rollen
```bash
php artisan db:seed --class=RoleSeeder
```

Of volledige database seed:
```bash
php artisan db:seed
```

### 3. Standaard Rollen
- **admin**: Volledige toegang tot systeem
- **medewerker**: Toegang tot medewerker functies
- **klant**: Standaard gebruikerstoegang

## Gebruik

### Nieuwe gebruiker registreren
1. Ga naar `/register`
2. Vul formulier in
3. Gebruiker krijgt automatisch 'klant' rol

### Rol toewijzen aan gebruiker
```php
$user = User::find(1);
$user->assignRole('admin');
```

### Check gebruiker rol in code
```php
if (auth()->user()->hasRole('admin')) {
    // Admin functionaliteit
}

if (auth()->user()->hasAnyRole(['admin', 'medewerker'])) {
    // Admin of medewerker functionaliteit
}
```

### Check gebruiker rol in Blade
```blade
@if(auth()->user()->hasRole('admin'))
    <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
@endif
```

### Routes beveiligen met middleware
```php
// Enkele rol
Route::get('/admin/users', [UserController::class, 'index'])
    ->middleware('role:admin');

// Meerdere rollen
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('role:admin,medewerker');
```

## Beveiliging
- Wachtwoorden worden gehashed met bcrypt
- CSRF bescherming op alle formulieren
- Session regeneratie bij login
- Guest middleware voorkomt dubbele login
- Auth middleware vereist login
- Role middleware controleert autorisatie

## Volgende Stappen
- Implementeer wachtwoord reset functionaliteit
- Voeg email verificatie toe
- Maak gebruikersbeheer interface voor admins
- Implementeer activity logging
- Voeg permissions toe naast rollen (optioneel)
