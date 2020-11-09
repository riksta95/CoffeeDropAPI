<?php

namespace Database\Seeders;

use App\Http\Controllers\LocationController;
use App\Models\CoffeeDropLocation;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Seeder;
use League\Csv\Exception;
use League\Csv\Reader;

class LocationSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * @throws Exception
     * @throws GuzzleException
     */
    public function run()
    {
        $csv = Reader::createFromPath(__DIR__ . '/csvs/location_data.csv', 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();

        $openingTimes = [];

        $daysOfTheWeek = [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday',
        ];

        foreach ($records as $record) {
            foreach ($daysOfTheWeek as $day) {
                $openingTimes[$day]   = $record ['open_' . $day];
                $closingTimes[$day]   = $record ['closed_' . $day];
            }

            $coordinates = (new LocationController())->coordinates($record['postcode'])->getData();

            CoffeeDropLocation::create([
                'postcode'      => $record['postcode'],
                'longitude'     => $coordinates->longitude,
                'latitude'      => $coordinates->latitude,
                'opening_times' => json_encode($openingTimes),
                'closing_times' => json_encode($closingTimes),

            ]);
        }
    }
}
