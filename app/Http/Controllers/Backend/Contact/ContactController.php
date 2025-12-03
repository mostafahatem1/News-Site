<?php

namespace App\Http\Controllers\Backend\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:contact')->only(['contact']);
        $this->middleware('can:contact_create')->only(['create']);
        $this->middleware('can:contact_store')->only(['store']);
    }

    public function contact()
    {
        $contacts = Contact::

       when(\request()->keyword != null, function ($query) {
            $query->search(\request()->keyword);
        })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 5); // Fetch contacts if needed
        return view('backend.contact.index', compact('contacts'));
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        // Mark notification as read for this contact (if exists)
        $admin = auth()->guard('admin')->user();
        if ($admin) {
            $notifications = $admin->unreadNotifications()->where('data->contact_id', $contact->id)->get();
            $notifications->markAsRead();
        }

        // Return a view to show the contact details
        return view('backend.contact.show', compact('contact')); // Return a view for creating a contact
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'name',
            'email',
            'title',
            'body',
            'phone',
        ]);
        $data['ip_address'] = $request->ip();

        Contact::create($data);
        Flasher::addSuccess('Contact message sent successfully.');
        return redirect()->back();
    }
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        Flasher::addSuccess('Contact message deleted successfully.');
        return redirect()->back();
    }
}

