<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRideRequest;
use App\Models\Booking;
use App\Models\Ride;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RideController extends Controller
{
    public function index()
    {
        $ridesDates = Ride::where('departure_time', '>', now())
            ->where('is_booking_open', 1)
            ->orderBy('departure_time', 'asc')
            ->get()
            ->groupBy(function ($ride) {
                return Carbon::parse($ride->departure_time)->format('Y-m-d');
            });

        return view('front.rides', compact('ridesDates'));
    }

    public function book(BookRideRequest $request)
    {
        $data = array_merge($request->validated(), ['status' => 'processing']);
        $ride = Ride::with('bus')
            ->withCount('confirmedBookings as bookings_count')
            ->find($request->input('ride_id'));

        if (
            !optional($ride)->bus ||
            !$ride->is_booking_open ||
            $ride->bus->places_available <= $ride->bookings_count ||
            now()->lessThanOrEqualTo($ride->depart_time)
        ) {
            return redirect()->back()->withErrors(['alert' => trans('front.this_ride_is_no_longer_available')]);
        }

        Booking::create($data);

        return redirect()->back()->withStatus(trans('front.the_ride_has_been_successfully_booked'));
    }
}
