<?php

namespace App\Services;

use App\UserSensor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SensorService
{
    /**
     * @return bool
     */
    public function fetch()
    {
        $address = Auth::user()->sensor->address ?? false;

        if (!$address) {
            return false;
        }

        try {
            $response = Http::get($address);

            if ($response->status() === 200) {
                $result = $response->json();

                if (isset($result['temperature_celsius'])
                    && isset($result['temperature_fahrenheit'])
                    && isset($result['temperature_humidity'])
                ) {
                    $tempCelsius = (float)$result['temperature_celsius'];
                    $tempFahrenheit = (float)$result['temperature_fahrenheit'];
                    $humidity = (float)$result['temperature_humidity'];

                    Auth::user()->sensor()->update([
                        'last_celsius' => $tempCelsius,
                        'last_fahrenheit' => $tempFahrenheit,
                        'last_humidity' => $humidity,
                    ]);

                    return Auth::user()->sensor;
                }
            }
        } catch (\Exception $exception) {
        }

        return false;
    }

    /**
     * @param UserSensor $sensor
     * @param int $value
     * @return bool
     */
    public function changeTemperature(UserSensor $sensor, int $value)
    {
        $response = Http::get($sensor->address . '/change/temperature');

        if ($response->status() === 200) {
            return true;
        }

        return false;
    }

    /**
     * @param UserSensor $sensor
     * @param int $value
     * @return bool
     */
    public function changeHumidity(UserSensor $sensor, int $value)
    {
        $response = Http::get($sensor->address . '/change/humidity');

        if ($response->status() === 200) {
            return true;
        }

        return false;
    }

    /**
     * @param string $address
     * @return bool
     */
    public function validate(string $address)
    {
        try {
            $response = Http::get($address);

            if ($response->status() === 200) {
                $result = $response->json();

                if (isset($result['temperature_celsius'])) {
                    return true;
                }
            }
        } catch (\Exception $exception) {
        }

        return false;
    }
}
