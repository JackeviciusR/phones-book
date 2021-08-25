<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
//     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth()->user()) {
            return redirect()->route('login');
        }

        $contacts = Contact::query()->where('creator_id','=', auth()->user()->id)->get();

        $receivedContacts = auth()->user()->receivedContacts()->get();

        foreach ($receivedContacts as $receivedContact) {
            $contacts->push($receivedContact);
        }

        $contacts = $contacts->sortBy('name');

        return view('contacts.index',[
            'user_id'=>auth()->user()->id,
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
//        dd(auth()->user()->id);
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
//dd('hello');
        Contact::create($request->validated());

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
        $shares = $contact->contactShares()->get();
        $receivers = $contact->receivers()
            ->orderBy('name')
            ->get();

        $orderedShares = collect();
        foreach ($receivers as $receiver) {
            foreach ($shares as $share) {
                if ($receiver->id == $share->user_id) {
                    $orderedShares->push($share);
                }
            }
        }

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
        $contact->update($request->validated());

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
        $shares = $contact->contactShares()->get();

        DB::beginTransaction();

        try {
//            foreach ($shares as $share) {
//                $share->delete();
//            }

            $contact->delete();
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('contacts.index')
                ->withErrors('Something wrong!, try delete again!');
        }

        return redirect()->route('contacts.index')
            ->with('message', 'Success! Contact \'' . $contactName . '\' record is deleted!');
    }
}
