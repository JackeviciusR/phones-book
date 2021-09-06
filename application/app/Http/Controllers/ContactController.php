<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Managers\ContactManager;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function __construct(private ContactManager $contactManager) {

    }

    /**
     * Display a listing of the resource.
     *
//     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $contacts = $this->contactManager->getUserAllContacts($user);

        return view('contacts.index',[
            'user_id'=>$user->id,
            'contacts'=>$contacts,
            'count'=>$contacts->count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
//     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contacts.create',[
            'creator_id'=>auth()->user()->id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\Response
     */
    public function store(ContactRequest $request)
    {
        $this->contactManager->createModel($request);

        return redirect()->route('contacts.index')
            ->with('message', 'Success, new records added to contact!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contact  $contact
//     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        $contactById = $this->contactManager->getContactById($contact->id);
        if (auth()->user()->id != $contactById->creator->id) {
            return redirect()->back();
        }

        $orderedShares = $this->contactManager->getContactSharesByReceivers($contact->id);

        return view('contacts.show', [
            'contact'=>$contact,
            'shares'=>$orderedShares,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contact $contact
//     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        $contactById = $this->contactManager->getContactById($contact->id);
        if (auth()->user()->id != $contactById->creator->id) {
            return redirect()->back();
        }

        return view('contacts.edit', [
            'contact'=>$contact,
            'creator_id'=>$contact->creator_id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact  $contact
//     * @return \Illuminate\Http\Response
     */
    public function update(ContactRequest $request, Contact $contact)
    {
        $this->contactManager->updateModel($request, $contact);

        return redirect()->route('contacts.index')
            ->with('message', 'Success, contacts record is updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact  $contact
//     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $contactName = $contact->name;

        $isDeleted = $this->contactManager->deleteModel($contact);

        if(!$isDeleted) {
            return redirect()->route('contacts.index')
                ->withErrors('Something wrong!, try delete again!');
        }

        return redirect()->route('contacts.index')
            ->with('message', 'Success! Contact \'' . $contactName . '\' record is deleted!');
    }
}
