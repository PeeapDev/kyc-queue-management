<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        @if($settings->logo)
                            <img src="{{ $settings->logo }}" alt="{{ $settings->site_title }}" class="block h-9 w-auto">
                        @else
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        @endif
                    </a>
                </div>

                <!-- Navigation Links -->
                <!-- ... rest of the navigation ... -->
            </div>

            <!-- ... rest of the navigation bar ... -->
        </div>
    </div>

    <!-- ... responsive menu ... -->
</nav>
