# Laravel Response Cache Demo Application

This demo project demonstrates the usage of the `spatie/laravel-responsecache` package for caching responses in a Laravel application. The package helps improve application performance by caching responses to avoid redundant database queries and expensive operations.

## Prerequisites

-   PHP 8.0 or higher
-   Laravel 10.x
-   Composer

## Installation

1. Clone this repository:

    ```bash
    git clone <repository-url>
    cd response-cache-demo
    ```

2. Install dependencies:

    ```bash
    composer install
    ```

3. Copy the example environment file and configure it:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. Install the `spatie/laravel-responsecache` package:

    ```bash
    composer require spatie/laravel-responsecache
    ```

5. Publish the configuration file:

    ```bash
    php artisan vendor:publish --provider="Spatie\ResponseCache\ResponseCacheServiceProvider"
    ```

6. Configure your caching driver (e.g., `redis`, `file`, `database`) in your `.env` file:

    ```
    CACHE_DRIVER=file
    ```

## Usage

### Middleware Example

Add routes using the `responsecache` middleware to cache responses:

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Spatie\ResponseCache\ResponseCache;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('responsecache')->group(function () {
    Route::get('/demo', function () {
        $timestamp = now()->toDateTimeString();
        Log::info("Serving fresh response at: $timestamp");
        return response()->json([
            'message' => 'Hello, this is a cached response!',
            'timestamp' => $timestamp
        ]);
    });

    Route::get('/greet/{name}', function ($name) {
        return response()->json([
            'message' => "Hello, $name!",
            'timestamp' => now()->toDateTimeString(),
        ]);
    });

    Route::get('/products', function (Request $request) {
        $category = $request->query('category', 'all');
        return response()->json([
            'message' => "Showing products for category: $category",
            'timestamp' => now()->toDateTimeString(),
        ]);
    });
});

Route::get('/dynamic-data', function () {
    $timestamp = now()->toDateTimeString();
    Log::info("No cache for this response at: $timestamp");
    return response()->json([
        'data' => 'This response will not be cached.',
        'timestamp' => $timestamp,
    ]);
})->middleware('doNotCacheResponse');
```

### Explanation

-   `/demo`, `/greet/{name}`, and `/products` use the `responsecache` middleware, demonstrating how responses are cached.
-   `/dynamic-data` bypasses caching using the `doNotCacheResponse` middleware.

### Clearing the Cache

Clear all cached responses:

```bash
php artisan responsecache:clear
```

Clear cache by tag:

```bash
php artisan responsecache:clear --tag=custom-tag
```

## Configuration

You can customize the cache behavior in `config/responsecache.php`. For example, exclude specific routes:

```php
'doNotCachePattern' => [
    'admin/*',
    'api/secure/*',
],
```

## License

This project is open-source and available under the [MIT license](LICENSE).
