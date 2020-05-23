@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Sensor configuration</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('sensor.update') }}">
                        @csrf

                        <div class="line col-md-8">
                            <label for="name">Sensor name</label>
                            <input
                                id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Sensor Name"
                                name="name"
                                type="text"
                                required
                                value="{{ Auth::user()->sensor->name ?? old('name') }}"
                            >
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="line col-md-8">
                            <label for="address">Sensor address</label>
                            <input
                                id="address"
                                class="form-control @error('address') is-invalid @enderror"
                                placeholder="Sensor IP address"
                                name="address"
                                type="text"
                                required
                                value="{{ Auth::user()->sensor->address ?? old('address') }}"
                            >
                            @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="line col-md-8">
                            <button class="btn btn-primary" type="submit">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
