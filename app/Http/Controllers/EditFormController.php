<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EditFormController extends Controller
{
    public function edit()
    {
        return response()->json(['message' => 'Edit form accessed']);
    }
}
