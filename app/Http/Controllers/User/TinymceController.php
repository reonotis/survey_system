<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\UserController;

class TinymceController extends UserController
{

    /**
     */
    public function upload(\Illuminate\Http\Request $request)
    {
        if ($request->hasFile('file')) {

            $path = $request->file('file')->store('tinymce/mail', 'public');

            return response()->json([
                'location' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['error' => 'Upload failed'], 400);
    }
}
