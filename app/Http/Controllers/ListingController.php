<?php


namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ListingController extends Controller {
    // show all listings
    public function index() {
        //dd(request('tag'));
        // SHOW THE OBJECT PROPERTY AVAILABLE, SUCH AS HOW MANY PAGES ARE AVAILABLE
        //dd(Listing::latest()->filter(request(['tag', 'search']))->paginate(2));

        return view('listings.index', [

            // ALL
            //'listings' => Listing::all() // getting data by using the App\Models\Listing; and the all() moethod

            // sort by the latest and filter whatever the tag PARAM value is
            //simplePaginate() is also available
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(6) // getting data by using the App\Models\Listing; and the all() moethod

        ]);
    }
    // show single listing
    public function show(Listing $listing) {
        return view('listings.show', [
            'listing' => $listing // passing the MYSQL data from App\Models\Listing
        ]);
    }

    //NewMethods

    public function manage() {
        return view('listings.manage', ['listings' => auth()->user()->listings()->get()]);
    }

    public function edit(Listing $listing) {
        // dd($listing->title); // post title upload1
        return view('listings.edit', [
            'listing' => $listing // passing the MYSQL data from App\Models\Listing
        ]);
    }

    public function update(Request $request, Listing $listing) {
        // MAKE SURE LOGGED IN USRE IS OWNER OF ITEM
        if($listing->user_id != auth()->id()){
            abort(403, 'Unathorized Action');
        }

        // dd($request->all()); // dubug
        // dd($request->file('logo'));
        // VALIDATION: https://laravel.com/docs/9.x/validation
        $requiredFieldsArr = ['title', 'company', 'location', 'tags', 'description', 'website',];
        $rq = [];
        foreach ($requiredFieldsArr as $field) {
            $rq[$field] = 'required';
        }
        //dd($rq);
        // create variable to be used to enter into database
        $formFields = $request->validate([
            //https://laravel.com/docs/9.x/validation#rule-unique
            'email' => ['required', 'email'],
            ...$rq

        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        //dd($formFields);
        // insert form fields into mysql data
        $listing->update($formFields);

        return back()->with('message', 'Listing updated successfuly!');
    }


    // DELETE
    public function destroy(Listing $listing) {
                // MAKE SURE LOGGED IN USRE IS OWNER OF ITEM
                if($listing->user_id != auth()->id()){
                    abort(403, 'Unathorized Action');
                }

        $listing->delete();
        return redirect('/')->with('message', 'Listing deleted successfuly!');
    }





    public function store(Request $request) {
        // dd($request->all()); // dubug
        // dd($request->file('logo'));
        // VALIDATION: https://laravel.com/docs/9.x/validation
        $requiredFieldsArr = ['title', 'location', 'tags', 'description', 'website',];
        $rq = [];
        foreach ($requiredFieldsArr as $field) {

            $rq[$field] = 'required';
        }
        //dd($rq);
        // create variable to be used to enter into database
        $formFields = $request->validate([
            //https://laravel.com/docs/9.x/validation#rule-unique
            'company' => ['required', Rule::unique('listings', 'company')],
            'email' => ['required', 'email'],
            ...$rq

        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        // get the current logged in user id and assigned to $formFields['user_id']
        $formFields['user_id'] = auth()->id();

        //dd($formFields);
        // insert form fields into mysql data
        Listing::create($formFields);

        return redirect('/')->with('message', 'Listing created successfuly!');
    }
    public function create() {
        return view('listings.create');
    }
}
