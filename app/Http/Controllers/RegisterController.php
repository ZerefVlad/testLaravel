<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class RegisterController extends Controller
{
    public function __invoke()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'phone' => 'required|string|max:15', // Измените на нужный вам формат
        ]);

        $phone = $request->phone;
        $now = Carbon::now();

        $cachedLink = Redis::get('link:' . $phone);

        if ($cachedLink) {
            return view('register')->with([
                'existingLink' => $cachedLink,
                'showForm' => false,
            ]);
        } else {
            $existingLink = DB::table('links')
                ->where('PhoneNumber', $phone)
                ->where('expires_at', '>', $now)
                ->first();

            if ($existingLink) {
                $link = route('link.page', ['token' => $existingLink->token]);

                return view('register')->with([
                    'existingLink' => $link,
                    'showForm' => false,
                ]);
            }
        }

        $token = Str::random(16);
        $expiresAt = Carbon::now()->addDays(7);
        $link = route('link.page', ['token' => $token]);

        DB::table('links')->insert([
            'token' => $token,
            'Username' => $request->username,
            'PhoneNumber' => $phone,
            'expires_at' => $expiresAt,
        ]);

        Redis::connection()->set('link:' . $request->phone, $token);

        return view('register')->with([
            'generatedLink' => $link,
            'showForm' => false,
        ]);
    }
}
