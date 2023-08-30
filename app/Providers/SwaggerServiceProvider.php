<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SwaggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */

    /**
     * @OA\Info(
     *     version="1.0.0",
     *     title="Line bot API",
     *     description="API for Line Bot",
     *     @OA\Contact(
     *         email="your-email@example.com",
     *         name="Your Name"
     *     )
     * )
     * @OA\Server(url="http://localhost:8000/api")
     *    @OA\Schema(
     *   schema="UserResource",
     *   type="object",
     *   @OA\Property(
     *     property="id",
     *     type="integer",
     *     description="The user ID"
     *   ),
     *   @OA\Property(
     *     property="name",
     *     type="string",
     *     description="The name of the user"
     *   ),
     *   @OA\Property(
     *     property="email",
     *     type="string",
     *     description="The email of the user"
     *   ),
     *   @OA\Property(
     *     property="deposit",
     *     type="number",
     *     format="float",
     *     description="The deposit amount for the user"
     *   )
     * )
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
