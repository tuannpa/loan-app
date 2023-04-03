<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Repayment;
use Illuminate\Database\Eloquent\Factories\Factory;

class RepaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Repayment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'state' => Repayment::PENDING,
            'repaid_date' => $this->faker->date
        ];
    }
}
