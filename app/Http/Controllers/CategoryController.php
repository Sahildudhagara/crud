<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
{
    if ($request->ajax()) {
        // Initialize pagination and search parameters
        $totalData = Category::where('user_id', Auth::id())->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        
        // Base query with user-specific filtering
        $categoriesQuery = Category::where('user_id', Auth::id());

        // Search functionality
        if (!empty($request->input('search.value'))) {
            $searchTerm = $request->input('search.value');
            $categoriesQuery = $categoriesQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });

            $totalFiltered = Category::where('user_id', Auth::id())->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
            })->count();
        }

        // Order functionality (Dynamic order by column and direction)
        if ($request->has('order')) {
            $orderColumnIndex = $request->input('order.0.column'); // index of the column to sort by
            $orderDirection = $request->input('order.0.dir'); // 'asc' or 'desc'

            // Get column name for sorting (can be expanded to include other fields)
            $columns = ['id', 'name', 'description', 'status', 'created_at'];
            $orderByColumn = $columns[$orderColumnIndex] ?? 'created_at'; // default to 'created_at'

            // Apply ordering to the query
            $categoriesQuery = $categoriesQuery->orderBy($orderByColumn, $orderDirection);
        }

        // Fetch paginated categories
        $categories = $categoriesQuery->offset($start)->limit($limit)->get();

        // Prepare data for AJAX response
        $data_val = [];
        foreach ($categories as $category) {
            $data_val[] = [
                'id' => $category->id,  // Ensure the 'id' is included
                'name' => $category->name,
                'description' => $category->description,
                'status' => $category->status ? 'Active' : 'Inactive',
                'created_at' => $category->created_at->format('m-d-Y'),
                'image' => $category->image ? asset('storage/' . $category->image) : 'https://dummyimage.com/400x200',
            ];
        }

        // Send response as JSON
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => intval($totalData),
            'recordsFiltered' => intval($totalFiltered),
            'data' => $data_val,
        ]);
    }

    // Return view if it's not an AJAX request
    $categories = Category::where('user_id', Auth::id())->paginate(10);
    return view('category.index', [
        'categories' => $categories,
        'userEmail' => Auth::user()->email
    ]);
}


    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        // Validate input fields
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'status' => 'nullable|boolean',
        ]);

        // Handle image upload (if exists)
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // Create a new category
        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
            'status' => $request->status ?? 0, // Default to 0 if status is null
            'user_id' => Auth::id(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'Category Created Successfully',
                'category' => $category,
            ]);
        }

        return redirect()->route('category.index');
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);

        if ($category->user_id !== Auth::id()) {
            return response()->json(['error' => 'You do not have permission to view this category.'], 403);
        }

        return response()->json($category);
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        if ($category->user_id !== Auth::id()) {
            return response()->json(['error' => 'You do not have permission to edit this category.'], 403);
        }

        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            return response()->json(['error' => 'You do not have permission to edit this category.'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'status' => 'nullable|boolean',
        ]);

        $imagePath = $category->image;
        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::delete('public/' . $category->image);
            }

            $imagePath = $request->file('image')->store('images', 'public');
        }

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        // Return a success response for AJAX   
        if ($request->ajax()) {
            return response()->json([
                'status' => 'Category Updated Successfully',
                'category' => $category,
            ]);
        }

        return redirect()->route('category.index');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->user_id !== Auth::id()) {
            return response()->json(['error' => 'You do not have permission to delete this category.'], 403);
        }

        if ($category->image) {
            Storage::delete('public/' . $category->image);
        }

        $category->delete();

        if (request()->ajax()) {
            return response()->json([
                'status' => 'Category Deleted Successfully',
                'categoryId' => $id,
            ]);
        }

        return redirect()->route('category.index');
    }
}