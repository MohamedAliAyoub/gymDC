<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <style>
        /* Your existing styles */
        /* Add styles to center the heading and make it bigger */
        .centered-heading {
            text-align: center;
            font-size: 3rem; /* Adjust the font size as needed */
        }
        /* Add styles to move the footer content to bottom right */
        .footer {
            position: absolute;
            bottom: 0;
            right: 0;
            padding: 1rem; /* Adjust padding as needed */
        }
    </style>
</head>
<body class="antialiased">
<div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
    @if (Route::has('login'))
        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
            <!-- Your existing login/register links -->
        </div>
    @endif
    <div class="max-w-7xl mx-auto p-6 lg:p-8">
        <!-- Centered heading -->
        <div class="centered-heading">
            <h4>D&C Canada</h4>
        </div>
    </div>
    <!-- Footer content moved to bottom right -->
    <div class="footer">
        <div class="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-right sm:ml-0">
            Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
        </div>
    </div>
</div>
</body>
</html>
