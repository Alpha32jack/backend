<?php

namespace Backend\App\Http\Controllers\Api;

use Backend\App\Http\Controllers\Controller;
use Backend\App\Models\Atributo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CharacterController extends Controller{
    public function index(){
        return response()->json(['characters' => Atributo::all(), 'status' => 200], 200);
    }
}

