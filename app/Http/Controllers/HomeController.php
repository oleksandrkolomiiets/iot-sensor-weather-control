<?php

namespace App\Http\Controllers;

use App\Http\Requests\SensorRequest;
use App\Http\Requests\UpdateHumidityRequest;
use App\Http\Requests\UpdateTemperatureRequest;
use App\Services\SensorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class HomeController extends Controller
{
    /**
     * @var SensorService $service
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @param SensorService $service
     */
    public function __construct(SensorService $service)
    {
        $this->service = $service;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sensorConfigure()
    {
        return view('configure');
    }

    /**
     * @param SensorRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws ValidationException
     */
    public function sensorUpdate(SensorRequest $request)
    {
        $address = $request->getAddress();

        $result = $this->service->validate($address);

        if (!$result) {
            throw ValidationException::withMessages([
                'address' => 'Address response is not valid! Reconfigure you device or choose another address.'
            ]);
        }

        if ($sensor = Auth::user()->sensor()->first()) {
            $sensor->update($request->validated());
        } else {
            Auth::user()->sensor()->create($result->validated());
        }

        return redirect('dashboard');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function sensorFetch()
    {
        return response()->json($this->service->fetch());
    }

    /**
     * @param UpdateTemperatureRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTemperature(UpdateTemperatureRequest $request)
    {
        return response()->json(
            $this->service->changeTemperature(Auth::user()->sensor, $request->getValue())
        );
    }

    /**
     * @param UpdateHumidityRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateHumidity(UpdateHumidityRequest $request)
    {
        return response()->json(
            $this->service->changeHumidity(Auth::user()->sensor, $request->getValue())
        );
    }
}
