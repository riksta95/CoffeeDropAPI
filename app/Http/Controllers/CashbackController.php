<?php

namespace App\Http\Controllers;

use App\Models\Cashback;
use Illuminate\Http\JsonResponse;

class CashbackController
{
    /**
     * @var array
     */
    private $coffeeTypes = [
        'Ristretto',
        'Espresso',
        'Lungo',
    ];

    /**
     * @var array
     */
    private $cashbackValues = [
        'Ristretto' => [2, 3, 5],
        'Espresso' => [4, 6, 10],
        'Lungo' => [6, 9, 15],
    ];

    /**
     * Get cashback based on coffee pods purchased by user request
     * @return JsonResponse
     */
    public function getCashback()
    {
        $cashback = 0;

        foreach ($this->coffeeTypes as $coffeeType) {
            if (request()->exists($coffeeType)) {
                $amount = request()->input($coffeeType);

                switch (true) {
                    case $amount >= 50 && $amount <= 500:
                        $cashback += $amount * $this->cashbackValues[$coffeeType][1];
                        break;
                    case $amount >= 501:
                        $cashback += $amount * $this->cashbackValues[$coffeeType][2];
                        break;
                    default:
                        $cashback += $amount * $this->cashbackValues[$coffeeType][0];
                        break;
                }
            }
        }

        $cashback = number_format($cashback / 100, 2);

        $cashback = Cashback::create([
            'user_id'               => auth()->user()->id,
            'coffee_pods_purchased' => json_encode(request()->input()),
            'cashback_generated'    => $cashback,
        ]);

        return response()->json('£' . $cashback->cashback_generated, 200);
    }

    /**
     * Get last 5 cashback information from database for marketing team
     * @return JsonResponse
     */
    public function marketing()
    {
        $cashback = Cashback::limit(5)->orderBy('created_at')->get();

        return $cashback;
    }
}
