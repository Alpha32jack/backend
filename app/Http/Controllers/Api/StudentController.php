<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        return response()->json(['students' => Student::all(), 'status' => 200], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:10',
            'email' => 'required|email|unique:student',
            'password' => 'required|max:16',
            'profile_picture' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'profile_picture' => $request->profile_picture
        ]);

        if (!$student) {
            return response()->json(['message' => 'Error al crear el estudiante', 'status' => 500], 500);
        }

        return response()->json(['student' => $student, 'status' => 201], 201);
    }

    public function show($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Estudiante no encontrado', 'status' => 404], 404);
        }

        return response()->json(['student' => $student, 'status' => 200], 200);
    }

    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Estudiante no encontrado', 'status' => 404], 404);
        }

        $student->delete();

        return response()->json(['message' => 'Estudiante eliminado', 'status' => 200], 200);
    }

    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Estudiante no encontrado', 'status' => 404], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:students',
            'password' => 'required|max:16',
            'profile_picture' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        $student->name = $request->name;
        $student->email = $request->email;
        $student->password = $request->password;
        $student->profile_picture = $request->profile_picture;

        $student->save();

        return response()->json(['message' => 'Estudiante actualizado', 'student' => $student, 'status' => 200], 200);
    }

    public function assignSala(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Estudiante no encontrado', 'status' => 404], 404);
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

        return response()->json(['message' => 'Sala asignada exitosamente', 'student' => $student, 'status' => 200], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|max:16',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        $student = Student::where('email', $request->email)
            ->where('password', $request->password)
            ->first();

        if (!$student) {
            return response()->json(['message' => 'Credenciales incorrectas', 'status' => 401], 401);
        }

        return response()->json(['student' => $student, 'status' => 200], 200);
    }



    public function verifyAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_or_password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        // Buscar por email o password
        $student = Student::where('email', $request->email_or_password)
            ->orWhere('password', $request->email_or_password)
            ->first();

        if (!$student) {
            return response()->json(['message' => 'Cuenta no encontrada', 'status' => 404], 404);
        }

        return response()->json(['message' => 'Cuenta verificada con éxito', 'student' => $student, 'status' => 200], 200);
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
            return response()->json(['message' => 'Cuenta no encontrada', 'status' => 404], 404);
        }

        $student->password = $request->new_password;
        $student->save();

        return response()->json(['message' => 'Contraseña actualizada con éxito', 'status' => 200], 200);
    }
}