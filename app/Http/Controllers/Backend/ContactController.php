<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {

        $data = Contact::orderBy('id', 'desc')->get();

        return Inertia::render('Admin/Contact/Index', compact('data'));

    }

    public function delete(Contact $contact)
    {
        $contact->update([
            'deleted_at' => now(),
        ]);

        // $contact->delete();

        return redirect()->back()->with('message', 'Contact deleted Successfully.');

    }
}
