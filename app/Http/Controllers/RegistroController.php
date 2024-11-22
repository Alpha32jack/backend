<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegistroController extends Controller
{
    public function register(Request $request)
    {
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // Confirmación de la contraseña
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de imagen (opcional)
        ]);

        // Si la validación falla, devolver un error 422 con los detalles
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Si hay una imagen, manejarla
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // Crear un nuevo usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'image' => $imagePath,
        ]);

        // Devolver una respuesta exitosa con el usuario creado
        return response()->json(['message' => 'Usuario registrado con éxito', 'user' => $user], 201);
    }
    public function enviarCorreoRecuperacion(Request $request)
    {
        $email = $request->input('email');
        $codigo = rand(100000, 999999); // Generar un código de 6 dígitos
        
        // Lógica para guardar el código en la base de datos o en caché
        Cache::put("codigo_recuperacion_$email", $codigo, 300); // 5 minutos de validez
    
        // Enviar el correo (configura tu servicio de correo)
        Mail::to($email)->send(new CodigoRecuperacionMail($codigo));
    
        return response()->json(['message' => 'Correo enviado con éxito.']);
    }
    public function verificarCodigo(Request $request)
    {
        $email = $request->input('email');
        $codigo = $request->input('codigo');
    
        // Validar el código almacenado en caché
        $codigoGuardado = Cache::get("codigo_recuperacion_$email");
        if ($codigoGuardado && $codigoGuardado == $codigo) {
            return response()->json(['message' => 'Código verificado con éxito.']);
        }
    
        return response()->json(['message' => 'Código inválido o expirado.'], 400);
    }
    


}
