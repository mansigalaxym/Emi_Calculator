<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontController extends Controller
{

    public function index()
    {
        return view('index');
    }

    public function interest(Request $request)
    {
        $rate = $request->input('rate');
        $amount = $request->input('amount');
        $time = $request->input('time');
        /*************************************************/
        $p = $amount;
        $r = $rate / 12 / 100;
        $n = $time * 12;

        $E = ($p * $r * pow((1 + $r), $n)) / (pow((1 + $r), $n) - 1);
        $TPA = $E * $n;  //(TPA means total payable amount)
        $TIPA = $TPA - $amount;        //(TPA means total interest payable amount)

        /*************************************************/
        return response()->json(['rate' => $rate, 'EMI' => $E, 'TPA' => $TPA, 'TIPA' => $TIPA]);
    }

    public function amount(Request $request)
    {
        $rate = $request->input('rate');
        $amount = $request->input('amount');
        $time = $request->input('time');
        /*************************************************/
        $p = $amount;
        $r = $rate / 12 / 100;
        $n = $time * 12;

        $E = ($p * $r * pow((1 + $r), $n)) / (pow((1 + $r), $n) - 1);
        $TPA = $E * $n;  //(TPA means total payable amount)
        $TIPA = $TPA - $amount;        //(TPA means total interest payable amount)

        return response()->json(['amount' => $amount, 'EMI' => $E, 'TPA' => $TPA, 'TIPA' => $TIPA]);
    }

    public function time(Request $request)
    {
        $rate = $request->input('rate');
        $amount = $request->input('amount');
        $time = $request->input('time');
        /*************************************************/
        $p = $amount;
        $r = $rate / 12 / 100;
        $n = $time * 12;

        $E = ($p * $r * pow((1 + $r), $n)) / (pow((1 + $r), $n) - 1);
        $TPA = $E * $n;  //(TPA means total payable amount)
        $TIPA = $TPA - $amount;        //(TPA means total interest payable amount)

        return response()->json(['time' => $time, 'months' => $n, 'EMI' => $E, 'TPA' => $TPA, 'TIPA' => $TIPA]);
    }
}
