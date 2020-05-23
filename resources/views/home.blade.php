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

                    @if (Auth::user()->sensor)
                        <h3>
                            Sensor: {{ Auth::user()->sensor->name }}
                        </h3>
                        <sensor-component></sensor-component>
                    @else
                        You have to <a href="{{ route('sensor.configure') }}">configure</a> a sensor
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
