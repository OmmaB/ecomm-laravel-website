<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;


class LanguageController extends Controller
{
    public function setEnglish()
    {
        App::setLocale('en');
        return Redirect::back();
    }

    public function setFrench()
    {
        App::setLocale('fr');
        return Redirect::back();
    }
    
    public function __construct()
    {
    $this->middleware('web');
    }

}
