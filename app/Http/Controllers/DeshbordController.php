<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeshbordController extends Controller
{
    
    public function index(){
        return view('deshbord');

    }

}
