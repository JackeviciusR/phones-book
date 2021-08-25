@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Share your cantact</div>
                    <div class="card-body">

                        <form method="POST" action="{{route('shares.store')}}">
                            @csrf

                            <div class="form-group">
                                <label>Name: {{ $contact->name }}</label>
                            </div>
                            <div class="form-group">
                                <label>Phone: {{ $contact->phone }}</label>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="hidden" name="contact_id" value="{{ $contact->id }}">
                            </div>
                            <div class="form-group">
                                <label>Share with</label>
                                <select class="form-control" name="user_id">
                                    <option value="null" selected> ------- select menu ------- </option>
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}"
                                            @if($user->id == old('user_id'))
                                                selected
                                            @endif >
                                            {{$user->name}}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Please select user</small>
                            </div>

                            <button type="submit" class="btn btn-primary">SHARE</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
