<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Asegúrate de que el modelo User esté en la ruta correcta
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;    // Para hashear contraseñas
use Illuminate\Validation\Rule;         // Para reglas de validación como unique() e in()
use Illuminate\Validation\Rules;        // Para reglas de validación más complejas como Password::defaults()
// Si planeas usar Logs para errores, también necesitarías: use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los usuarios. Podrías paginar si son muchos.
        // O filtrar solo por rol 'asesor' si solo quieres gestionar esos desde aquí.
        $users = User::orderBy('name')->paginate(10); // Obtiene 10 usuarios por página, ordenados por nombre

        return view('admin.users.index', compact('users'));
        // Esto buscará una vista en resources/views/admin/users/index.blade.php
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Simplemente mostramos la vista con el formulario de creación
        return view('admin.users.create');
        // Esto buscará una vista en resources/views/admin/users/create.blade.php
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validación de los datos del formulario
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)], // Asegura que el email sea único en la tabla users
            'password' => ['required', 'confirmed', Rules\Password::defaults()], // 'confirmed' busca un campo password_confirmation
                                                                                // Rules\Password::defaults() aplica reglas de complejidad por defecto de Laravel
            'role' => ['required', Rule::in(['admin', 'asesor'])], // Asegura que el rol sea uno de los permitidos
        ]);

        // 2. Crear el nuevo usuario
        try {
            User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']), // Hashear la contraseña
                'role' => $validatedData['role'],
                'email_verified_at' => now(), // Opcional: marcar como verificado al crear desde admin
            ]);

            // 3. Redirigir con un mensaje de éxito
            return redirect()->route('admin.users.index')
                             ->with('success', 'Usuario creado exitosamente.');

        } catch (\Exception $e) {
            // Manejar cualquier error durante la creación (ej. problema de BD)
            // \Illuminate\Support\Facades\Log::error('Error al crear usuario: ' . $e->getMessage()); // Podrías loguear el error
            return back()->withInput()->with('error', 'Hubo un problema al crear el usuario. Por favor, inténtalo de nuevo.');
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {

        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user) // Laravel inyecta automáticamente la instancia del User
    {
        // Pasamos el usuario que se va a editar a la vista
        return view('admin.users.edit', compact('user'));
        // Esto buscará una vista en resources/views/admin/users/edit.blade.php
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user) // Laravel inyecta el User a actualizar
    {
        // 1. Validación de los datos del formulario
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Para el email, la regla unique debe ignorar el email del usuario actual
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            // La contraseña es opcional en la edición
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()], // 'nullable' permite que esté vacío
            'role' => ['required', Rule::in(['admin', 'asesor'])],
        ]);

        // 2. Actualizar los datos del usuario
        try {
            $userDataToUpdate = [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'role' => $validatedData['role'],
            ];

            // Solo actualizar la contraseña si se proporcionó una nueva
            if (!empty($validatedData['password'])) {
                $userDataToUpdate['password'] = Hash::make($validatedData['password']);
            }

            $user->update($userDataToUpdate);

            // 3. Redirigir con un mensaje de éxito
            return redirect()->route('admin.users.index')
                             ->with('success', 'Usuario actualizado exitosamente.');

        } catch (\Exception $e) {
            // \Illuminate\Support\Facades\Log::error('Error al actualizar usuario: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Hubo un problema al actualizar el usuario. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user) // Laravel inyecta el User a eliminar
    {
        // Opcional: Prevenir que un administrador se elimine a sí mismo
        // if (Auth::id() === $user->id) {
        //     return redirect()->route('admin.users.index')
        //                      ->with('error', 'No puedes eliminar tu propia cuenta de administrador.');
        // }

        try {
            $userName = $user->name; // Guardar el nombre para el mensaje
            $user->delete(); // Elimina el usuario de la base de datos

            return redirect()->route('admin.users.index')
                             ->with('success', "Usuario '{$userName}' eliminado exitosamente.");

        } catch (\Exception $e) {
            // \Illuminate\Support\Facades\Log::error('Error al eliminar usuario: ' . $e->getMessage());
            return redirect()->route('admin.users.index')
                             ->with('error', 'Hubo un problema al eliminar el usuario. Por favor, inténtalo de nuevo.');
        }
    }
}
