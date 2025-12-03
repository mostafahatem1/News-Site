<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\Frontend\NewSubscriberMail;
use App\Models\NewSebscriber;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewSebscriberController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:new_sebscribers,email',
        ]);

        NewSebscriber::create([
            'email' => $request->email,
        ]);

        Mail::to($request->email)->send(new NewSubscriberMail());

         // Use Flasher to display a success message
        Flasher::addSuccess('Subscription successful!');

        return redirect()->back();
    }
}
