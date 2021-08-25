@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">

                        <h2>Shared Contacts</h2>

                    </div>

                    <div class="card-body">

                        <table class="table table-hover">
                            <thead class="">
                            <tr>
                                <th scope="col" class="align-self-sm-start">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">phone</th>
                                <th scope="col">shared with</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody id="myTable">
                            @for($row = 0; $row < 1;)
                                @foreach ($contacts as $key=>$contact)
                                    @foreach($contact->contactShares as $share)
                                        <tr>
                                            <th scope="row">{{ ++$row }}</th>
                                            <td>{{$contact->name}}</td>
                                            <td>{{$contact->phone}}</td>
                                            <td>{{$share->sharedWith->name}}</td>
                                            <td scope="col">
                                                <form method="POST" action="{{ route('shares.destroy', [$share]) }}">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">REMOVE</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endfor
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
