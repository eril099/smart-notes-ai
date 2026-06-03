<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class quizController extends Controller
{
    public function index(){
        return view('quiz.index');
    }

    public function show(){
        return view('quiz.show');
    }
}
