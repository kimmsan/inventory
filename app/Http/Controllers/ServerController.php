<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Artisan;

class ServerController extends Controller
{

    public function runCommand(Request $request)
    {
        $validated = $request->validate([
            'command' => ['required', Rule::in(['optimize:clear'])]
        ]);

        $call = Artisan::call($validated['command']);

        if ($call === 0) {
            return response()->json([
                'message' => 'Success',
            ]);
        }

        return response()->json([
            'message' => 'Failed',
        ]);
    }
}
