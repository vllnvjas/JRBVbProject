<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculateController extends Controller
{
    public function add () {
    $a = 3;
    $b = 5;
    $sum = $a + $b;
    return "Sum is: " .$sum;
    }

    public function sub() {
    $a = 3;
    $b = 5;
    $sum = $a - $b;
    return "Difference is: " .$sum;
    }

    public function multiply () {
    $a = 3;
    $b = 5;
    $sum = $a * $b;
    return "Product is: " .$sum;
    }

    public function divide () {
    $a = 3;
    $b = 5;
    $sum = $a / $b;
    return "Qoutient is: " .$sum;
    }
    public function modulo () {
    $a = 3;
    $b = 5;
    $sum = $a % $b;
    return "Remainder is: " .$sum;
    }

}
