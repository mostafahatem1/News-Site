<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:home')->only(['index']);
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('backend.index');
    }
}
