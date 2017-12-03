@extends('layouts.master')

@section('title', 'Today Appointment')
@section('appointments', 'active')
@section('sidebar_today', 'active')

@section('content')

    <div class="container mb-5">
        <div class="row">

            @include('partials.appointment.sidebar')

            <div class="col-sm-9 ml-sm-auto col-md-10 mt-5">
                <div class="card">
                    <div class="card-header">
                        <div class="float-right">
                            <form action="#" method="#" class="form-inline">
                                {{ csrf_field() }}
                                
                                <div class="form-group mr-2">
                                    <input type="text" name="search" class="form-control" placeholder="search coming soon..." required>
                                </div>
                            </form>
                        </div>
                        <h4 class="card-text">Today's Appointment</h4>
                    </div>

                    <div class="list-group list-group-flush"> 
                        @forelse($appointments as $appointment)
                            <a href="/appointments/{{ $appointment->id }}" class="list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">{{ $appointment->user->name }} <small class="text-muted"> an: {{ $appointment->appointment_number }} </small></h5>
                                    <p class="text-muted"><strong>{{ \Carbon\Carbon::parse($appointment->date_of_visit)->format('l, d F Y') }}</strong></p>
                                </div>
                                <div class="d-flex w-100 justify-content-between">
                                    <p class="mb-1 text-muted">
                                        <strong>
                                            {{ $appointment->doctor->name }} - {{ $appointment->doctor->polyclinic->name }}
                                        </strong>
                                    </p>
                                </div>
                            </a>
                        @empty
                            <a href="#" class="list-group-item text-center">
                                <h4 class="text-muted"><strong>No appointment today</strong></h4>
                                <h6 class="text-muted">when appointment available, it'll show up here.</h6>
                            </a>
                        @endforelse
                    </div>
                </div>
            </div>
        
        </div>
    </div>

@endsection