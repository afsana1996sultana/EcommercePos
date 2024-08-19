<?php

namespace App\Http\Controllers\Backend;

use App\Models\Contact;

use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function index()
    {
        $contact = Contact::latest('id', 'asc')->get();
        return view('backend.contact.index', compact('contact'));
    } // end method

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id)->first();
        $contact->delete();
        return redirect()->back()->with('success', 'Contact deleted successfully');
    } // end method
}
