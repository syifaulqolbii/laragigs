<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    // show all listings
    public function index()
    {
        return view('listings.index', [
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->simplePaginate(4)
        ]);
    }

    // show a single listing
    public function show(Listing $listing)
    {
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    // show create form
    public function create()
    {
        return view('listings.create');
    }

    // store a new listing
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'description' => 'required',
            'tags' => 'required',
            'email' => 'required|email',
            'website' => 'required',
        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }
        Listing::create($formFields);

        return redirect('/')->with('message', 'Your listing has been added!');
    }

    // show edit form
    public function edit(Listing $listing)
    {
        return view('listings.edit', [

            'listing' => $listing
        ]);
    }

    // update a new listing
    public function update(Request $request, Listing $listing)
    {
        $formFields = $request->validate([
            'title' => 'required',
            'company' => 'required',
            'location' => 'required',
            'description' => 'required',
            'tags' => 'required',
            'email' => 'required|email',
            'website' => 'required',
        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }
        $listing->update($formFields);

        return back()->with('message', 'Your listing has been updated!');
    }

    // delete a listing
    public function destroy(Listing $listing)
    {
        $listing->delete();

        return redirect('/')->with('message', 'Your listing has been deleted!');
    }
}
