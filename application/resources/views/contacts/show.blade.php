@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        {{ $contact->name }}
                        <a href="{{route('contacts.edit',[$contact])}}" class="btn btn-outline-info btn-sm float-right ml-2">EDIT</a>
                        <a href="{{route('shares.create',[$contact])}}" class="btn btn-outline-secondary btn-sm float-right">SHARE</a>
                    </div>
                    <div class="card-body">

                        <div class="form-group font-weight-bold">
                            <label>Phone: {{ $contact->phone }}</label>
                        </div>
                        <div class="form-group">
                            <label>Sharing with:</label>
                            <ul class="list-group">
                                @foreach ($shares as $share)
                                    <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-action list-group-item-light">
                                        <span>
                                            {{ $share->sharedWith->name }}
                                        </span>
                                        <span class="badge badge-pill">
                                            <form method="POST" action="{{ route('shares.destroy', [$share]) }}">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success btn-sm">REMOVE</button>
                                            </form>
                                        </span>

                                    </li>
                                @endforeach
                            </ul>
                        </div>

                    </div>

                    <div class="card-header font-weight-bold">
                        <form method="POST" action="{{ route('contacts.destroy', [$contact]) }}">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">DELETE CONTACT</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
