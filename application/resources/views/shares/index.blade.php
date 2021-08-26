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
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <a href="{{route('shares.index',['orderRule'=>'byContactName'])}}" class="btn btn-outline-secondary btn-sm {{ $orderRule=='byContactName' ? 'active' : '' }}">By Contact</a>
                            <a href="{{route('shares.index',['orderRule'=>'byReceiverName'])}}" class="btn btn-outline-secondary btn-sm {{ $orderRule=='byReceiverName' ? 'active' : '' }}">By Receiver</a>
{{--                            @if($orderRule=='byContactName') {{'checked'}} @endif--}}
                        </div>
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
                                @foreach ($sharedData as $key=>$data)
                                    <tr>
                                        <th scope="row">{{ $key }}</th>
                                        <td>{{ $data->contact_name }}</td>
                                        <td>{{ $data->contact_phone }}</td>
                                        <td>{{ $data->receiver_name }}</td>
                                        <td scope="col">
                                            <form method="POST" action="{{ route('shares.destroy', [$data->share_id]) }}">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm">REMOVE</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
