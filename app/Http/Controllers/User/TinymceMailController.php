<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TinymceMailController extends UserController
{


    public function send(Request $request)
    {
        Mail::raw($request->sample, function ($message) {
            $message->to('fujisawareon@yahoo.co.jp')
                ->subject('テストメール');
        });

        return back();
    }

}
