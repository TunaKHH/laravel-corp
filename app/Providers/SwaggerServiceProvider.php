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
