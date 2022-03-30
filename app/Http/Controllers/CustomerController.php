<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use App\Models\Customer;
use App\Models\Contact;
use App\Models\Address;



use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index()
    {

        $pages = Customer::with('numbers' , 'address')->paginate(10);
        $archives = Customer::with('numbers' , 'address')->get();

        // var_dump($archives);
        return Inertia::render('Customers/Index', [
            'data' => $archives
        ]);
    }

    public function create()
    {
        return Inertia::render('Customers/Create');
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
        //changes
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
        

        return Redirect::route('customers')->with('success', 'Customer created.');
    }

    public function edit(Customer $organization)
    {

    }

    public function update(Customer $organization)
    {
    
    }

    public function destroy(Customer $organization)
    {

    }

    public function restore(Customer $organization)
    {
    
    }
}
