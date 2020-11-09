<?php

namespace App\Http\Controllers;

use App\Models\CoffeeDropLocation;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;

class LocationController
{
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Get longitude and latitude from provided postcode
     *
     * @param string $postcode
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function coordinates(string $postcode)
    {
        $userLocation = json_decode(
            $this->client->get('api.postcodes.io/postcodes/' . $postcode)->getBody()
        );

        $coordinates = [
            'longitude' => $userLocation->result->longitude,
            'latitude' => $userLocation->result->latitude,
        ];

        return response()->json($coordinates, 200);
    }

    /**
     * Get nearest location based on users coordinates
     * @param string $postcode
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function getNearestLocation(string $postcode)
    {
        $coordinates = $this->coordinates($postcode)->getData();

        $haversine = "(3959 * acos(cos(radians($coordinates->latitude))
            * cos(radians(latitude))
            * cos(radians(longitude)
            - radians($coordinates->longitude))
            + sin(radians($coordinates->latitude))
            * sin(radians(latitude))))";

        $nearestLocation = (new CoffeeDropLocation())->select('*')
            ->selectRaw("{$haversine} AS distance")
            ->whereRaw("{$haversine} < ?", 500)
            ->orderBy('distance')
            ->first();

        return response()->json($nearestLocation, 200);
    }

    /**
     * Create location based on postcode and opening times provided
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function createLocation()
    {
        $coordinates = $this->coordinates(request()->input('postcode'))->getData();

        $location = new CoffeeDropLocation();

        $saveData = [
            'postcode'      => request()->input('postcode'),
            'longitude'     => $coordinates->longitude,
            'latitude'      => $coordinates->latitude,
            'opening_times' => json_encode(request()->input('opening_times')),
            'closing_times' => json_encode(request()->input('closing_times')),
        ];

        $location->fill($saveData)->save();

        return response()->json($location, 200);
    }
}
