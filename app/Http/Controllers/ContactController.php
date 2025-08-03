<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mensaje' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Envía el correo (ajusta el destinatario)
        Mail::raw(
            "Mensaje de: {$request->nombre} <{$request->email}>\n\n{$request->mensaje}",
            function ($message) use ($request) {
                $message->to('medinattor@hotmail.com')
                        ->subject('Nuevo mensaje de contacto desde OKM.APP');
            }
        );

        return response()->json(['success' => true]);
    }
}
