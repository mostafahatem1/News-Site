<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ContactRequest;
use App\Models\Admin;
use App\Models\Contact;
use App\Notifications\NewContactNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification ;

class ContactController extends Controller
{
    public function contactStore(ContactRequest $request){
         $request;

        $data = $request->validated();
        $data['ip_address'] = $request->ip();
        $contact = Contact::create($data);

         // send notification to admin
        $admins = Admin::all();
        if ($admins->isNotEmpty()) {

            Notification::send($admins, new NewContactNotification($contact));
        }
        return api_response('Your message has been sent successfully! We will get back to you soon.', 200, null);
    }

}
