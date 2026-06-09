<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    /**
     * Display home page with all pets
     */
    public function index()
    {
        $pets = Pet::orderBy('id', 'desc')->get();
        $search = '';
        return view('home', compact('pets', 'search'));
    }

    /**
     * Display dogs page
     */
    public function dogs()
    {
        $pets = Pet::dogs()->orderBy('id', 'desc')->get();
        $search = '';
        return view('dog', compact('pets', 'search'));
    }

    /**
     * Display cats page
     */
    public function cats()
    {
        $pets = Pet::cats()->orderBy('id', 'desc')->get();
        $search = '';
        return view('cat', compact('pets', 'search'));
    }

    /**
     * Display pokemon page
     */
    public function pokemon()
    {
        $pets = Pet::pokemon()->orderBy('id', 'desc')->get();
        $search = '';
        return view('pokemon', compact('pets', 'search'));
    }

    /**
     * Display all pets in table view
     */
    public function viewAll()
    {
        $pets = Pet::orderBy('id', 'desc')->get();
        $search = '';
        return view('view_pets', compact('pets', 'search'));
    }

    /**
     * Show the form for creating a new pet
     */
    public function create()
    {
        return view('add_pet');
    }

    /**
     * Store a newly created pet in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|in:Dog,Cat,Pokemon',
            'age' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads'), $imageName);

                Pet::create([
                    'name' => $validated['name'],
                    'species' => $validated['species'],
                    'age' => $validated['age'],
                    'status' => 'Available',
                    'image' => $imageName
                ]);

                return redirect()->back()->with('success', '✅ Pet added successfully!');
            }

            return redirect()->back()->with('error', 'Image upload failed');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error adding pet: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified pet
     */
    public function edit($id)
    {
        $pet = Pet::findOrFail($id);
        return view('update_pet', compact('pet'));
    }

    /**
     * Update the specified pet in database
     */
    public function update(Request $request, $id)
    {
        $pet = Pet::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|in:Dog,Cat,Pokemon',
            'age' => 'required|integer|min:0',
            'status' => 'required|in:Available,Adopted',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'name' => $validated['name'],
            'species' => $validated['species'],
            'age' => $validated['age'],
            'status' => $validated['status']
        ];

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($pet->image && file_exists(public_path('uploads/' . $pet->image))) {
                unlink(public_path('uploads/' . $pet->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads'), $imageName);
            $data['image'] = $imageName;
        }

        $pet->update($data);

        return redirect()->route('home')->with('success', '✅ Pet updated successfully!');
    }

    /**
     * Remove the specified pet from database
     */
    public function destroy($id)
    {
        $pet = Pet::findOrFail($id);

        // Delete image file
        if ($pet->image && file_exists(public_path('uploads/' . $pet->image))) {
            unlink(public_path('uploads/' . $pet->image));
        }

        $pet->delete();

        return redirect()->back()->with('success', 'Pet deleted successfully!');
    }

    /**
     * Search for pets (AJAX)
     */
    public function search(Request $request)
    {
        $search = $request->input('search', '');

        if (empty($search)) {
            $pets = Pet::orderBy('id', 'desc')->get();
        } else {
            $pets = Pet::search($search)->orderBy('id', 'desc')->get();
        }

        // For AJAX requests
        if ($request->ajax()) {
            return response()->json($pets);
        }

        // For regular form submission
        return view('home', compact('pets', 'search'));
    }
}