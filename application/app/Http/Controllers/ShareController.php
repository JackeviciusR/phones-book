<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShareRequest;
use App\Models\Contact;
use App\Models\Share;
use App\Models\User;
use Illuminate\Http\Request;

class ShareController extends Controller
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
        $createdContactsIds = Contact::query()
            ->where('creator_id', '=', auth()->user()->id)
            ->select('id')
            ->get();

        $shares = Share::query()
            ->whereIn('contact_id', $createdContactsIds)
            ->get();

        $sharedContacts = collect([]);
        $contacts = auth()->user()->contacts()->get();

        foreach($contacts as $_ => $contact) {

            if($contact->receivers()->count()) {

//                $users = $contact->users();
                foreach( $contact->receivers()->get() as $user) {
//                    dd([$user->name, $contact->name]);
                }
                    $sharedContacts->push($contact);
//                dd($sharedContacts);

            }
        }

        $sharedContacts = $sharedContacts->sortBy('name');

        return view('shares.index', [
            'contacts'=>$sharedContacts,
            'shares'=>$shares,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
//     * @return \Illuminate\Http\Response
     */
    public function create(Contact $contact)
    {
        $existedReceiversIds = ($contact->receivers()->get())
            ->map
            ->only(['id']);

        $users = User::query()
            ->where('id', '!=', auth()->user()->id)
            ->whereNotIn('id', $existedReceiversIds)
            ->orderBy('name')
            ->get();

        return view('shares.create', [
            'creator_id'=>auth()->user()->id,
            'contact'=>$contact,
            'users'=>$users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\Response
     */
    public function store(ShareRequest $request)
    {

        Share::create($request->validated());

        return redirect()->route('shares.index')
                         ->with('message', 'Success, You are sharing contact!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Share  $share
     * @return \Illuminate\Http\Response
     */
    public function show(Share $share)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Share  $share
     * @return \Illuminate\Http\Response
     */
    public function edit(Share $share)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Share  $share
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Share $share)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Share  $share
//     * @return \Illuminate\Http\Response
     */
    public function destroy(Share $share)
    {
        $user = $share->sharedWith->name;
        $creator_id = $share->sharedContact->creator_id;
        $contactName = $share->sharedContact->name;

        $share->delete();

        if($creator_id != auth()->user()->id) {
            return redirect()->route('contacts.index')
                ->with('message', 'Success! Your contact \'' . $contactName .'\' is removed!');
        }

        return redirect()->route('shares.index')
            ->with('message', 'Success! Your contact \'' . $contactName .'\' sharing with \''. $user .'\' is removed!');
    }
}
