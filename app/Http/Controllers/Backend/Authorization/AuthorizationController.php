<?php

namespace App\Http\Controllers\Backend\Authorization;

use App\Http\Controllers\Controller;
use App\Models\Authorization;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Http\Request;

class AuthorizationController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:authorizations')->only(['index']);
        $this->middleware('can:authorizations_show')->only(['show']);
        $this->middleware('can:authorizations_create')->only(['create']);
        $this->middleware('can:authorizations_store')->only(['store']);
        $this->middleware('can:authorizations_edit')->only(['edit']);
        $this->middleware('can:authorizations_update')->only(['update']);
        $this->middleware('can:authorizations_delete')->only(['destroy']);
    }

    public function index()
    {
        // Fetch all authorizations and return the view
        $authorizations = Authorization::
         when(\request()->keyword != null, function ($query) {
            $query->search(\request()->keyword);
        })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 5);
        return view('backend.authorizations.index', compact('authorizations'));

    }




    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required|array|min:1',
        ]);

        Authorization::create([
            'role' => $request->name,
            'permissions' => json_encode($request->permissions),
        ]);
       Flasher::addSuccess('Authorization created successfully.');
       return redirect()->route('admin.authorizations.index')->withErrors('errors', 'Authorization created Failed.');
    }


    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required|array|min:1',
        ]);

        $authorization = Authorization::findOrFail($id);
        $authorization->update([
            'role' => $request->name,
            'permissions' => json_encode($request->permissions),
        ]);
        Flasher::addSuccess('Authorization updated successfully.');
        return redirect()->route('admin.authorizations.index')->withErrors('errors', 'Authorization updated Failed.');
    }


    public function destroy(string $id)
    {
         $role = Authorization::findOrFail($id);

        if ($role->admin->count() > 0) {
            Flasher::addError('This role is assigned to admins and cannot be deleted.');
            return redirect()->route('admin.authorizations.index');
        }

        $role->delete();
        Flasher::addSuccess('Authorization deleted successfully.');
        return redirect()->route('admin.authorizations.index')->withErrors('errors', 'Authorization deleted Failed.');
    }
}
