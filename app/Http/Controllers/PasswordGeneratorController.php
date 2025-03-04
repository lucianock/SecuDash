<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasswordGeneratorController extends Controller
{
    public function index()
    {
        return view('password-generator');
    }

    public function generate(Request $request)
    {
        $length = $request->input('length', 12); // Longitud predeterminada de la contraseña
        $includeSymbols = $request->input('include_symbols', false);

        $password = $this->generatePassword($length, $includeSymbols);

        return response()->json(['password' => $password]);
    }

    private function generatePassword($length, $includeSymbols)
    {
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*()-_=+[{]}|;:,.<>?';

        $characters = $letters . $numbers;
        if ($includeSymbols) {
            $characters .= $symbols;
        }

        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $password;
    }
}
