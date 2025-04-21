<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class registrationControll extends Controller
{
    public function registration(){
        return view("registration");
    }

    public function login(Request $request){
        
    }
}
