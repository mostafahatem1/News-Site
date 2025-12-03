<?php

namespace App\Http\Controllers\Backend\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:categories')->only(['index']);
        $this->middleware('can:categories_show')->only(['show']);
        $this->middleware('can:categories_create')->only(['create']);
        $this->middleware('can:categories_store')->only(['store']);
        $this->middleware('can:categories_edit')->only(['edit']);
        $this->middleware('can:categories_update')->only(['update']);
        $this->middleware('can:categories_delete')->only(['destroy']);
        $this->middleware('can:category_status')->only(['status']);
    }

    public function index()
    {
       $categories = Category::withCount('posts')
            ->when(\request()->has('sort_by') && \request()->has('order_by'), function ($query) {
                $query->orderBy(\request()->sort_by, \request()->order_by);
            })
            ->when(\request()->has('limit_by'), function ($query) {
                $query->paginate(\request()->limit_by);
            })
            ->
       when(\request()->keyword != null, function ($query) {
            $query->search(\request()->keyword);
        })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 5);
       return view('backend.categories.index', compact('categories'));
    }


    public function create()
    {
        return view('backend.categories.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        Category::create($request->all());
        Flasher::addSuccess('Category created successfully.');
        return redirect()->route('admin.categories.index');

    }


    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        return view('backend.categories.show', compact('category'));
    }


    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('backend.categories.edit', compact('category'));
    }


    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());
         Flasher::addSuccess('Category updated successfully.');
        return redirect()->route('admin.categories.index');
    }


    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        Flasher::addSuccess('Category deleted successfully.');
        return redirect()->route('admin.categories.index');
    }
    public function status($id)
    {
        $category = Category::findOrFail($id);
        $category->status = !$category->status;
        $category->save();
        Flasher::addSuccess('Category status updated successfully.');
        return redirect()->route('admin.categories.index');
    }
}
