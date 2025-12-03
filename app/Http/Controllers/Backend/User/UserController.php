<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\backend\UserRequest;
use App\Models\User;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:users')->only(['index']);
        $this->middleware('can:users_show')->only(['show']);
        $this->middleware('can:users_create')->only(['create']);
        $this->middleware('can:users_store')->only(['store']);
        $this->middleware('can:users_edit')->only(['edit']);
        $this->middleware('can:users_update')->only(['update']);
        $this->middleware('can:users_delete')->only(['destroy']);
    }

    public function index()
    {
        // Fetch users from the database and return a view
        // For example:
        $users = User::when(\request()->keyword != null, function ($query) {
            $query->search(\request()->keyword);
        })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 5);

        return view('backend.users.index', compact('users'));
    }


    public function create()
    {
        return view('backend.users.create');
    }


    public function store(UserRequest $request)
    {
        // Validate and store the user data
        $data = $request->validated();

        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Generate a unique image name
            $imageName = null;
            if (isset($data['image']) && $data['image']->isValid()) {
                $imageName = time() . '_' . Str::slug($data['username']) . '.' . $data['image']->getClientOriginalExtension();
                $destinationPath = public_path('frontend/img/user');
                // Ensure the destination path exists
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                // Move the image to the destination path
                $data['image']->move($destinationPath, $imageName);
                $data['image'] = $imageName; // Store the image name in the data array
            }
        }


        // Create the user
        User::create($data);

        Flasher::addSuccess('User created successfully!');
        // Redirect back with a success message
        return redirect()->route('admin.users.index');
    }


    public function show(string $id)
    {
        // Fetch a single user by ID and return a view
        $user = User::findOrFail($id);
        return view('backend.users.show', compact('user'));
    }


    public function edit(string $id)
    {
        // Fetch a single user by ID for editing
        $user = User::findOrFail($id);
        return view('backend.users.edit', compact('user'));
    }


    public function update(UserRequest $request, string $id)
    {
        // Validate and update the user data
        $data = $request->validated();

        // Update the user
        $user = User::findOrFail($id);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Delete old image if exists and not default
            if ($user->image && $user->image != 'default.jpg') {
                $oldImagePath = public_path('frontend/img/user/' . $user->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            // Generate a unique image name
            $imageName = null;
            if (isset($data['image']) && $data['image']->isValid()) {
                $imageName = time() . '_' . Str::slug($data['username']) . '.' . $data['image']->getClientOriginalExtension();
                $destinationPath = public_path('frontend/img/user');
                // Ensure the destination path exists
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                // Move the image to the destination path
                $data['image']->move($destinationPath, $imageName);
                $data['image'] = $imageName; // Store the image name in the data array
            }
        }
        // If password is not provided, do not update it
          if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        Flasher::addSuccess('User updated successfully!');
        // Redirect back with a success message
        return redirect()->route('admin.users.index');
    }


    public function destroy(string $id)
    {
        // Delete a user by ID
        $user = User::findOrFail($id);

        // Delete user image if exists and not default
        if ($user->image && $user->image != 'default.jpg') {
            $imagePath = public_path('frontend/img/user/' . $user->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $user->delete();

        // Redirect back with a success message
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
