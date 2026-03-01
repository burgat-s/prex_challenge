# 🚀 API REST GIPHY - Progreso de Desarrollo

[cite_start]Este documento detalla el progreso y la configuración inicial de la API REST construida para integrarse con GIPHY, cumpliendo con los requisitos de utilizar PHP v8.3 o superior [cite: 23][cite_start], Laravel Framework v11 o superior [cite: 24] [cite_start]y Docker[cite: 27].

## 🛠️ 1. Entorno y Configuración Base (Docker)
El proyecto se inicializó utilizando **Laravel Sail** sobre WSL2, lo que nos provee un entorno Dockerizado completo con PHP 8.4 y MySQL.

**Comandos iniciales:**
```bash
# Creación del proyecto
curl -s "[https://laravel.build/api-giphy-challenge?with=mysql](https://laravel.build/api-giphy-challenge?with=mysql)" | bash

# Levantar contenedores
./vendor/bin/sail up -d



🔐 2. Autenticación OAuth 2.0 (Laravel Passport)
Se implementó Laravel Passport para cumplir con el requisito de autenticación OAuth2.0.

Configuración de expiración de token:
Se configuró globalmente la expiración del token a 30 minutos  en app/Providers/AppServiceProvider.php:

PHP
public function boot(): void
{
    Passport::tokensExpireIn(now()->addMinutes(30));
    Passport::personalAccessTokensExpireIn(now()->addMinutes(30));
}
📦 3. Estandarización de Respuestas y Peticiones (DRY)
Para mantener un código limpio y cumplir con los principios de diseño, se crearon clases base para estandarizar entradas y salidas.
+1

DTO de Respuesta (app/Http/Responses/ApiResponse.php):
Clase helper para devolver siempre la misma estructura JSON tanto en casos de éxito (success) como en errores (error).

Base API Request (app/Http/Requests/BaseApiRequest.php):
Clase abstracta que sobrescribe el manejo de errores de validación de Laravel para inyectar nuestro ApiResponse:

PHP
abstract class BaseApiRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::error(
                message: 'Error de validación en los datos enviados',
                code: 422,
                errors: $validator->errors()
            )
        );
    }
}
🚪 4. Endpoint de Login
Se desarrolló el servicio de Login  separando la validación de la lógica de negocio.


Request (app/Http/Requests/LoginRequest.php): Hereda de BaseApiRequest y valida que email y password sean obligatorios.

Controlador (app/Http/Controllers/AuthController.php):

PHP
public function login(LoginRequest $request)
{
    if (Auth::attempt($request->validated())) {
        $token = Auth::user()->createToken('PrexChallengeToken')->accessToken;
        return ApiResponse::success(['token' => $token], 'Login exitoso');
    }
    return ApiResponse::error('Credenciales inválidas', 401);
}
Rutas (routes/api.php):

PHP
Route::post('/login', [AuthController::class, 'login']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
🌍 5. Localización (Idioma Español)
Se instaló el paquete laravel-lang/common y se configuró APP_LOCALE=es en el archivo .env para que todos los mensajes de error de validación se devuelvan nativamente en español.

🌱 6. Database Seeder
Se configuró el DatabaseSeeder.php para automatizar la creación de usuarios de prueba según el entorno, facilitando las pruebas de la API:

PHP
public function run(): void
{
    if (app()->environment('local')) {
        User::updateOrCreate(
            ['email' => 'test@test.com'],
            ['name' => 'Usuario Test', 'password' => Hash::make('12345678')]
        );
    }
}

