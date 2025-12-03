<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Authorization;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admins')->only(['index']);
        $this->middleware('can:admins_show')->only(['show']);
        $this->middleware('can:admins_create')->only(['create']);
        $this->middleware('can:admins_store')->only(['store']);
        $this->middleware('can:admins_edit')->only(['edit']);
        $this->middleware('can:admins_update')->only(['update']);
        $this->middleware('can:admins_delete')->only(['destroy']);
    }

    public function index()
    {
        $admins = Admin::where('id','!=',auth('admin')->user()->id)->when(\request()->keyword != null, function ($query) {
            $query->search(\request()->keyword);
        })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 5);
        return view('backend.admin.index', compact('admins'));
    }


    public function create()
    {
        $authorizations = Authorization::all();
        return view('backend.admin.create', compact('authorizations'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admins',
            'email' => 'required|email|unique:admins',
            'role_id' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

       Admin::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => bcrypt($request->password),
        ]);


        Flasher::addSuccess('Admin created successfully.');
        return redirect()->route('admin.admin.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $authorizations = Authorization::all();
        $admin = Admin::findOrFail($id);
        return view('backend.admin.edit', compact('admin', 'authorizations'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([

            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admins,username,' . $id,
            'email' => 'required|email|unique:admins,email,' . $id,
            'role_id' => 'required|string',
            'password' => 'nullable|string|min:8|confirmed',

        ]);

        $admin = Admin::findOrFail($id);
        $admin->name = $request->name;
        $admin->username = $request->username;
        $admin->email = $request->email;
        $admin->role_id = $request->role_id;

        if ($request->filled('password')) {
            $admin->password = bcrypt($request->password);
        }

        $admin->save();

        Flasher::addSuccess('Admin updated successfully.');
        return redirect()->route('admin.admin.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {


        $admin = Admin::findOrFail($id);
        $admin->delete();
        Flasher::addSuccess('Admin deleted successfully.');
        return redirect()->route('admin.admin.index');
    }
}
