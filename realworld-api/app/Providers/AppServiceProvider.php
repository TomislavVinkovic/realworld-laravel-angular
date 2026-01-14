<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Tymon\JWTAuth\Http\Parser\QueryString;
use Tymon\JWTAuth\Http\Parser\AuthHeaders;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Parser\InputSource;

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
        // 1. Create a new AuthHeaders parser
        $headerParser = new AuthHeaders();
        
        // 2. Set the prefix to "Token" (instead of the default "Bearer")
        $headerParser->setHeaderPrefix('Token');

        // 3. Set the chain of parsers on the JWTAuth instance
        // This tells JWTAuth to use your custom header parser first
        JWTAuth::parser()->setChain([
            $headerParser,
            new QueryString(),
            new InputSource()
        ]);
    }
}
