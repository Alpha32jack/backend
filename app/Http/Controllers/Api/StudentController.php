<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class studentController extends Controller
{
    public function index()
    {
        $students = Student::all();

        /* if ($students->isEmpty()) {
            $data =[
                'message'=>'no se encontraron estudiantes',
                'status'=> 200
            ];
            return response()->json($data,404);
        }*/

        $data = [
            'students' => $students,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:10',
            'email' => 'required|email|unique:student',
            'password' => 'required|digits:16',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'error en la validacion de los datos ',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'language' => $request->language
        ]);

        if (!$student) {
            $data = [
                'message' => 'Error al crear el estudiante',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'student' => $student,
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    public function show($id)
    {
        $student = Student::find($id);

        if (!$student) {
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'student' => $student,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $student->delete();

        $data = [
            'student' => $student,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:student',
            'phone' => 'required|digits:10',
            'language' => 'required|in:English,Spanish,French'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'error en la validacion de los datos ',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->language = $request->language;

        $student->save();

        $data = [
            'message' => 'Estudiante actualizado',
            'student' => $student,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function assignSala(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'message' => 'Estudiante no encontrado',
                'status' => 404
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'salaId' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        $student->salaId = $request->salaId;
        $student->save();

        return response()->json([
            'message' => 'Sala asignada exitosamente',
            'student' => $student,
            'status' => 200
        ], 200);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'phone' => 'required|digits:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        $student = Student::where('email', $request->email)
            ->where('phone', $request->phone)
            ->first();

        if (!$student) {
            return response()->json([
                'message' => 'Credenciales incorrectas',
                'status' => 401
            ], 401);
        }

        return response()->json([
            'student' => $student,
            'status' => 200
        ], 200);
    }

    public function uploadProfilePicture(Request $request, $id)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Limita el tamaño y tipo de imagen
        ]);

        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }

        // Manejo de la imagen
        if ($request->hasFile('profile_picture')) {
            // Eliminar la imagen anterior si existe
            if ($student->profile_picture) {
                Storage::delete($student->profile_picture);
            }

            // Guardar la nueva imagen
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $student->profile_picture = $path;
            $student->save();
        }

        return response()->json(['message' => 'Imagen de perfil actualizada', 'student' => $student], 200);
    }

    public function verifyAccount(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email_or_phone' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Error en la validación',
            'errors' => $validator->errors(),
            'status' => 400
        ], 400);
    }

    // Buscar por email o phone
    $student = Student::where('email', $request->email_or_phone)
        ->orWhere('phone', $request->email_or_phone)
        ->first();

    if (!$student) {
        return response()->json([
            'message' => 'Cuenta no encontrada',
            'status' => 404
        ], 404);
    }

    return response()->json([
        'message' => 'Cuenta verificada con éxito',
        'student' => $student, // Devuelve los datos básicos
        'status' => 200
    ], 200);
}


    public function updatePassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'new_password' => 'required|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        $student = Student::find($id);
        if (!$student) {
            return response()->json([
                'message' => 'Cuenta no encontrada',
                'status' => 404
            ], 404);
        }

        $student->phone = $request->new_password;
        $student->save();

        return response()->json([
            'message' => 'Contraseña actualizada con éxito',
            'status' => 200
        ], 200);
    }
}