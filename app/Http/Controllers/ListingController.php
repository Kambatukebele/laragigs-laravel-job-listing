<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index(Request $request)
    {
       //dd(request('tag'));  
        return view('listings.index', [
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(6)
        ]);
    }

    public function show(Listing $listing)
    {
        return view('listings.show', ['listing' => $listing]);
    }

    public function create(Request $request)
    {
        return view('listings.create');
    }

    public function store (Request $request)
    {
        $formFields = $request->validate([
            'title' => 'required|max:255',
            'company' => 'required|unique:listings,company',
            'location' => 'required|max:255',
            'website' => 'required|max:255',
            'email' => 'required|email|max:255|unique:listings,email',
            'tags' => 'required|max:255',
            'description' => 'required'
        ]);

        if($request->hasFile('logo')){
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }
        $formFields['user_id'] = auth()->id();

        Listing::create($formFields); 

        return redirect('/')->with('message', 'Listing has been created!'); 
    }

    //show Edit Form

    public function edit(Listing $listing)
    {   
        return view('listings.edit', ['listing' => $listing]);
    }

    //update

    public function update (Request $request, Listing $listing)
    {
        $formFields = $request->validate([
            'title' => 'required|max:255',
            'company' => 'required',
            'location' => 'required|max:255',
            'website' => 'required|max:255',
            'email' => 'required|email|max:255|',
            'tags' => 'required|max:255',
            'description' => 'required'
        ]);

        if($request->hasFile('logo')){
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        

       $listing->update($formFields); 

        return back()->with('message', 'Listing has been Updated!'); 
    }

    public function destroy(Listing $listing)
    {
        $listing->delete();
        return redirect('/')->with('message', 'Listing has been deleted!'); 
    }
}