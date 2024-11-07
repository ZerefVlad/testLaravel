<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class LinkController extends Controller
{
    public function __invoke($token)
    {
        $linkData = DB::table('links')->where('token', $token)->first();

        if (!$linkData || Carbon::now()->greaterThan($linkData->expires_at)) {
            return redirect()->route('register')->withErrors(['link' => 'The link is invalid or has expired.']);
        }

        return view('link_page', ['linkData' => $linkData]);
    }

    public function generateNewLink(Request $request)
    {
        Redis::connection()->del('link:' . $request->phone);
        $existingLink = DB::table('links')->where('PhoneNumber', $request->phone)->first();

        if ($existingLink) {
            DB::table('links')->where('PhoneNumber', $request->phone)
                ->update(['expires_at' => Carbon::now()]);

            $token = Str::random(16);
            $expiresAt = Carbon::now()->addDays(7);

            DB::table('links')->insert([
                'token' => $token,
                'Username' => $existingLink->Username,
                'PhoneNumber' => $request->phone,
                'expires_at' => $expiresAt,
            ]);

            Redis::connection()->set('link:' . $request->phone, $token);

            return redirect()->route('link.page', ['token' => $token])
                ->with('message', 'New link was generated. Current link: ' . route('link.page', ['token' => $token]));
        }

        return redirect()->route('register')->withErrors(['link' => 'For this phone not found active link.']);
    }

    public function deactivateLink(Request $request)
    {
        $phone = $request->phone;
        Redis::connection()->del('link:' . $phone);

        DB::table('links')->where('PhoneNumber', $phone)->update(['expires_at' => Carbon::now()]);
        $message = 'Link for this number ' . $phone . ' was deactivated';
        return view('register')->with([
            'message' => $message,
            'showForm' => true,
        ]);
    }

    public function drop(Request $request)
    {
        $randomNumber = rand(1, 1000);
        $isWin = $randomNumber % 2 === 0;

        if ($randomNumber > 900) {
            $winningAmount = $randomNumber * 0.70;
        } elseif ($randomNumber >= 600) {
            $winningAmount = $randomNumber * 0.50;
        } elseif ($randomNumber >= 300) {
            $winningAmount = $randomNumber * 0.30;
        } else {
            $winningAmount = $randomNumber * 0.10;
        }

        $result = $isWin ? 'Win' : 'Lose';

        DB::table('game_results')->insert([
            'token' => $request->token,
            'random_number' => $randomNumber,
            'result' => $result,
            'winning_amount' => $winningAmount,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('link.page', ['token' => $request->token])
            ->with(compact('randomNumber', 'result', 'winningAmount'));
    }

    public function history(Request $request)
    {
        $history = DB::table('game_results')
            ->where('token', $request->token)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return redirect()->route('link.page', ['token' => $request->token])
            ->with(compact('history'));
    }
}
