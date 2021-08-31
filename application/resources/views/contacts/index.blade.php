@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">

                        <h2>Contacts</h2>

                    </div>

                    <div class="card-body">

                        <div class="form-group">
                            <a href="{{ route('contacts.create') }}" class="btn btn-outline-secondary btn-sm">New contact</a>
                        </div>

                        <table class="table table-hover">
                            <thead class="">
                            <tr>
                                <th scope="col" class="align-self-sm-start">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">phone</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody id="myTable">
{{--                            @for($row = 0;$row < 1;)--}}
                                @foreach ($contacts as $key=>$contact)
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>
                                        <td>{{$contact->name}}</td>
                                        <td>{{$contact->phone}}</td>
                                        <td scope="col" >
                                            @if(auth()->user()->id == $contact->creator_id)
                                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                    <label class="btn btn-secondary btn-sm active">
                                                        {{$contact->receivers()->count()}}
                                                    </label>
                                                    <a href="{{ route('shares.create',[$contact]) }}" class="btn btn-outline-secondary btn-sm">SHARE</a>
                                                </div>
                                            @else
                                                <div class="btn btn-dark btn-sm">
                                                    from {{$contact->creator->name}}
                                                </div>
                                            @endif
                                        </td>
                                        <td scope="col" >
                                            @if(auth()->user()->id == $contact->creator_id)
                                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                    <a href="{{route('contacts.show',[$contact])}}" class="btn btn-secondary btn-sm">SHOW</a>
                                                </div>
                                            @else
                                            @endif
                                        </td>
                                        <td scope="col">
                                            @if(auth()->user()->id == $contact->creator_id)
                                                <form method="POST" action="{{ route('contacts.destroy', [$contact]) }}">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">DELETE</button>
                                                </form>
                                            @else
                                                @foreach($contact->contactShares as $share)
                                                    @if($share->user_id == $user_id)
                                                        <form method="POST" action="{{ route('shares.destroy', [$share]) }}">
                                                            @method('DELETE')
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-success btn-sm">REMOVE</button>
                                                        </form>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
{{--                            @endfor--}}
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
