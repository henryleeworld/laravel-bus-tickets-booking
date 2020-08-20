@extends('layouts.front')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ trans('front.upcoming_rides') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('rides.book') }}" style="{{ $errors->isEmpty() ? 'display:none;' : '' }}">
                        @csrf
                        <input type="hidden" id="ride" name="ride_id" value="{{ old('ride_id') }}">

                        @error('alert')
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="form-group">
                            <label for="name">{{ trans('front.name') }}</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">{{ trans('front.email') }}</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">{{ trans('front.phone_number') }}</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                            @error('phone')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('front.submit') }}</button>
                        </div>
                    </form>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">{{ trans('front.route') }}</th>
                                <th scope="col">{{ trans('front.time') }}</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ridesDates as $date => $rides)
                                <tr>
                                    <td class="text-center" colspan="3">
                                        <strong>{{ $date }}</strong>
                                    </td>
                                </tr>
                                @foreach ($rides as $ride)
                                    <tr>
                                        <td>
                                            {{ $ride->route }}
                                        </td>
                                        <td>
                                            {{ Carbon\Carbon::parse($ride->departure_time)->format('H:i') }}
                                            -
                                            {{ Carbon\Carbon::parse($ride->arrival_time)->format('H:i') }}
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary" onclick="$('#ride').val({{ $ride->id }}); $('form').show()">
                                                {{ trans('front.book_now') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td class="text-center" colspan="3">
                                        There are no upcoming rides.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
