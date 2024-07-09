<?php

namespace App\Providers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('success', function(?string $message, array|Collection|JsonResource $data = [], $httpCode = JsonResponse::HTTP_OK) {
            return Response::json([ 'error' => false, 'message' => $message, 'data' => $data ], $httpCode);
        });

        Response::macro('failed', function(?string $message, array|Collection|JsonResource $data = [], $httpCode = JsonResponse::HTTP_INTERNAL_SERVER_ERROR) {
            return Response::json([ 'error' => true, 'message' => $message, 'data' => $data ], $httpCode);
        });
    }
}
