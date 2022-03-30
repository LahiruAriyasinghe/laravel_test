<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use App\Models\Customer;
use App\Models\Contact;
use App\Models\Address;



use Inertia\Inertia;

class OrganizationsController extends Controller
{
    public function index()
    {

        $pages = Customer::with('numbers' , 'address')->paginate(10);
        $archives = Customer::with('numbers' , 'address')->get();

        // var_dump($archives);
        return Inertia::render('Organizations/Index', [
            'data' => $archives
        ]);
    }

    public function create()
    {
        return Inertia::render('Organizations/Create');
    }

    public function store(Request $request)
    {

        Request::validate(
            [
                'name' => ['required', 'max:100'],
                'nic' => ['required', 'max:15'],
                'contacts.*.telephone' => ['nullable','regex:/[0-9]{9}/','size:10'],
                'address' => ['required', 'max:150'],
            ]
        );

        $customer = new Customer;
        $customer->name =  Request::get('name');
        $customer->nic =  Request::get('nic');
        $customer->save();

        $contacts = Request::get('contacts');
        
        foreach ($contacts as $cont) {
            $contact = new Contact;
            $contact->customer_id = $customer->id;
            $contact->number = $cont['telephone'];
            $contact->save();
        }

        $address = new Address;
        $address->customer_id = $customer->id;
        $address->address = Request::get('address');
        $address->save();
        

        return Redirect::route('organizations')->with('success', 'Customer created.');
    }

    public function edit(Organization $organization)
    {
        return Inertia::render('Organizations/Edit', [
            'organization' => [
                'id' => $organization->id,
                'name' => $organization->name,
                'email' => $organization->email,
                'phone' => $organization->phone,
                'address' => $organization->address,
                'city' => $organization->city,
                'region' => $organization->region,
                'country' => $organization->country,
                'postal_code' => $organization->postal_code,
                'deleted_at' => $organization->deleted_at,
                'contacts' => $organization->contacts()->orderByName()->get()->map->only('id', 'name', 'city', 'phone'),
            ],
        ]);
    }

    public function update(Organization $organization)
    {
        $organization->update(
            Request::validate([
                'name' => ['required', 'max:100'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::back()->with('success', 'Organization updated.');
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();

        return Redirect::back()->with('success', 'Organization deleted.');
    }

    public function restore(Organization $organization)
    {
        $organization->restore();

        return Redirect::back()->with('success', 'Organization restored.');
    }
}
