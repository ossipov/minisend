<?php

namespace Database\Factories;

use App\Models\Mail;
use Illuminate\Database\Eloquent\Factories\Factory;

class MailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Mail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => rand(1,7),
            'status' => $this->faker->randomElement(['posted', 'sent', 'failed']),
            'from_name' => $this->faker->name,
            'from_email' => $this->faker->unique()->safeEmail,
            'to_name' => $this->faker->name,
            'to_email' => $this->faker->unique()->safeEmail,
            'subject' => $this->faker->sentence,
            'text' => $this->faker->text(),
            'html' => $this->faker->randomHtml(),
            'attachments' => '[]',
        ];
    }
}
