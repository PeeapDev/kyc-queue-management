<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $settings->site_title ?? config('app.name', 'QCell KYC') }}</title>
        @if($settings && $settings->favicon)
            <link rel="icon" type="image/png" href="{{ asset('storage/' . $settings->favicon) }}">
        @endif
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <!-- Header/Navigation -->
        <header class="fixed w-full bg-white dark:bg-gray-800 shadow-md z-50">
            <nav class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        @if($settings && $settings->logo)
                            <img src="{{ asset('storage/' . $settings->logo) }}"
                                 alt="{{ $settings->site_title }}"
                                 class="h-12 w-auto">
                        @else
                            <span class="text-2xl font-bold text-blue-600">{{ config('app.name', 'QCell KYC') }}</span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-full transition duration-300 ease-in-out transform hover:scale-105">
                            Login
                        </a>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <section class="pt-24 bg-gradient-to-br from-blue-600 to-blue-800 text-white min-h-screen">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-20">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div class="space-y-8">
                        <h1 class="text-4xl md:text-6xl font-bold leading-tight">
                            {{ $settings->hero_title ?? 'Streamline Your KYC Process' }}
                        </h1>
                        <p class="text-xl md:text-2xl text-blue-100">
                            {{ $settings->hero_subtitle ?? 'Fast, Secure, and Efficient Customer Verification' }}
                        </p>
                        <div class="flex space-x-4">
                            <a href="#features" class="bg-white text-blue-600 hover:bg-blue-50 px-8 py-3 rounded-full font-semibold transition duration-300 ease-in-out transform hover:scale-105">
                                Learn More
                            </a>
                            <a href="#contact" class="border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-3 rounded-full font-semibold transition duration-300 ease-in-out">
                                Contact Us
                            </a>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        @if($settings && $settings->hero_image)
                            <img src="{{ Storage::disk('public')->url($settings->hero_image) }}" alt="Hero" class="w-full h-auto rounded-lg shadow-xl">
                        @else
                            <img src="{{ asset('images/hero-image.png') }}" alt="Hero" class="w-full h-auto rounded-lg shadow-xl">
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-white dark:bg-gray-900">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-gray-900 dark:text-white">
                    Why Choose Our KYC Solution?
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                        <div class="text-blue-600 mb-4">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Fast Processing</h3>
                        <p class="text-gray-600 dark:text-gray-400">Quick and efficient KYC verification process with minimal wait times.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                        <div class="text-blue-600 mb-4">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Secure System</h3>
                        <p class="text-gray-600 dark:text-gray-400">Advanced security measures to protect your sensitive data.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                        <div class="text-blue-600 mb-4">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">24/7 Support</h3>
                        <p class="text-gray-600 dark:text-gray-400">Round-the-clock customer support assistance whenever you need it.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="py-20 bg-gray-50 dark:bg-gray-800">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-gray-900 dark:text-white">Get in Touch</h2>
                <div class="max-w-3xl mx-auto text-center">
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
                        Have questions about our KYC solution? We're here to help!
                    </p>
                    <a href="mailto:contact@qcell.sl" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-full text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10">
                        Contact Us
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p class="text-gray-400">
                        © {{ date('Y') }} {{ $settings->site_title ?? config('app.name', 'QCell KYC') }}. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>

        <!-- Sierra Mind AI Chatbot -->
        @if($settings && $settings->chatbot_script)
            {!! $settings->chatbot_script !!}
        @endif
    </body>
</html>
