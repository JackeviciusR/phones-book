<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShareRequest;
use App\Managers\ShareManager;
use App\Models\Contact;
use App\Models\Share;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShareController extends Controller
{
    public function __construct(private ShareManager $shareManager)
    {
    }

    /**
     * Display a listing of the resource.
     *
//     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orderRule = $request->orderRule ?? 'byContactName';
        $sharedData = $this->shareManager->getSharedData(auth()->user()->id, $orderRule);

        return view('shares.index', [
            'sharedData'=>$sharedData,
            'orderRule' => $orderRule,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
//     * @return \Illuminate\Http\Response
     */
    public function create(Contact $contact)
    {
        $contactById = $this->shareManager->getContactById($contact->id);
        if (auth()->user()->id != $contactById->creator->id) {
            return redirect()->back();
        }

        $users = $this->shareManager->getUsersWithoutContact($contact);

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
        $this->shareManager->createModel($request);

        $contact = $this->shareManager->getContactById($request->contact_id);
        $receiver = $this->shareManager->getReceiverById($request->user_id);

        return redirect()->route('shares.index')
                         ->with('message', 'Success, You are sharing \'' . $contact->name . '\' contact with \'' . $receiver->name . '\'!');
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

        $isDeleted = $this->shareManager->deleteModel($share);

        if(!$isDeleted) {
            return redirect()->route('contacts.index')
                ->withErrors('Something wrong!, try delete again!');
        }

        if($creator_id != auth()->user()->id) {
            return redirect()->route('contacts.index')
                ->with('message', 'Success! Your contact \'' . $contactName .'\' is removed!');
        }

        return redirect()->route('shares.index')
            ->with('message', 'Success! Your contact \'' . $contactName .'\' sharing with \''. $user .'\' is removed!');
    }
}
