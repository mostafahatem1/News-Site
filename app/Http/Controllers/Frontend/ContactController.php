<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ContactRequest;
use App\Models\Admin;
use App\Models\Contact;
use App\Notifications\NewContactNotification;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification ;

class ContactController extends Controller
{
    /**
     * Show the contact form.
     */
    public function show()
    {
        return view('frontend.contact-us');
    }

    /**
     * Store the contact form submission.
     */
    public function store(ContactRequest $request)
    {
        $data = $request->validated();
        $data['ip_address'] = $request->ip();
        $contact = Contact::create($data);

        // send notification to admin
        $admins = Admin::all();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new NewContactNotification($contact));
        }


        Flasher::addSuccess('Your message has been sent successfully! We will get back to you soon.');
        return redirect()->back();

    }
}

