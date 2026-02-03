<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HRIS App') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-slate-100 to-slate-200 dark:from-gray-900 dark:to-gray-800">
        <div class="min-h-screen flex flex-col justify-center items-center px-4">

            <!-- LOGO -->
            <div class="w-full sm:max-w-md mx-auto flex justify-center mt-10 mb-6">
                <a href="/" class="w-full flex justify-center">
                    <svg class="w-full max-w-xs sm:max-w-sm md:max-w-md h-auto" viewBox="0 0 240 80"
                        xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="grad1" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#3f52e3" />
                                <stop offset="100%" stop-color="#57caeb" />
                            </linearGradient>
                        </defs>
            
                        <!-- Icon circle -->
                        <circle cx="40" cy="40" r="28" fill="url(#grad1)" opacity="0.95" />
            
                        <!-- Icon people -->
                        <circle cx="40" cy="30" r="7" fill="#ffffff" />
                        <path d="M28,56 C28,48 52,48 52,56 L52,60 C52,64 48,68 40,68 C32,68 28,64 28,60 Z" fill="#ffffff" />
            
                        <!-- Icon chart -->
                        <rect x="62" y="26" width="8" height="28" rx="3" fill="url(#grad1)" />
                        <rect x="76" y="18" width="8" height="36" rx="3" fill="url(#grad1)" />
                        <rect x="90" y="34" width="8" height="20" rx="3" fill="url(#grad1)" />
            
                        <!-- Text -->
                        <text x="118" y="38" font-family="Arial, Helvetica, sans-serif" font-size="32" font-weight="800"
                            fill="#ffffff">
                            HRIS
                        </text>
                        <text x="118" y="62" font-family="Arial, Helvetica, sans-serif" font-size="20" font-weight="600"
                            fill="#aeb6ff">
                            App
                        </text>
                    </svg>
                </a>
            </div>

            <div
                class="w-full sm:max-w-md bg-white dark:bg-gray-800
                        shadow-xl rounded-2xl px-8 py-6
                        border border-gray-100 dark:border-gray-700">

                {{ $slot }}
            </div>
        </div>
    </body>
</html>
