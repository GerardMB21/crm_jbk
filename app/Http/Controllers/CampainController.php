<?php

namespace App\Http\Controllers;

use App\Models\Campain;

class CampainController extends Controller
{
    public function list()
    {
        $campain = Campain::get();
        return $campain;
    }
}
