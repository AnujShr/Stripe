@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <subscribe-component :plans="{{$plans}}"></subscribe-component>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Payments</div>

                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($users = auth()->user()->options_paginated as $payment)
                                <li class="list-group-item">{{$payment->created_at->diffForHumans()}} :
                                    <strong>{{$payment->inDollarFormat()}}</strong>
                                    @if ($payment->plan)
                                        for {{ucfirst($payment->plan)}} plan.
                                    @endif
                                </li>
                            @endforeach
                                {{ $users->links() }}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
