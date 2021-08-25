@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit contact</div>
                    <div class="card-body">

                        <form method="POST" action="{{route('contacts.update', [$contact])}}">
                            @method('PUT')
                            @csrf

                            <div class="form-group">
                                <label>Name</label>
                                <input class="form-control" type="text" name="name" value="{{old('name', $contact->name)}}">
                                <small class="form-text text-muted">Please enter contact name</small>
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input class="form-control" type="text" name="phone" value="{{old('phone', $contact->phone)}}">
                                <small class="form-text text-muted">Please enter contact phone</small>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="hidden" name="creator_id" value="{{ $creator_id }}">
                            </div>

                            <button type="submit" class="btn btn-primary">ADD</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
