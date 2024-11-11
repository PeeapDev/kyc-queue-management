<div x-data="{ show: true }" x-show="show" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-2xl leading-6 font-bold text-gray-900 dark:text-white mb-8">
                            Welcome aboard, {{ auth()->guard('admin')->user()->name }}!
                        </h3>

                        <p class="text-gray-600 dark:text-gray-300 mb-8">
                            Your account has been successfully setup with {{ config('app.name') }}. You can update these settings anytime.
                        </p>

                        <!-- Default Counters Section -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Default Counters</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                We have created two default counters to ease your setup process. These can be edited anytime.
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h5 class="text-lg font-medium text-gray-900 dark:text-white">Counter 1</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h5 class="text-lg font-medium text-gray-900 dark:text-white">Counter 2</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Default Categories Section -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Default Categories</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                We have created two default categories to ease your setup process. These can be edited anytime.
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h5 class="text-lg font-medium text-gray-900 dark:text-white">Account</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h5 class="text-lg font-medium text-gray-900 dark:text-white">Payment</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Default Staff Section -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Default Staff</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                We have created two default staff members to ease your setup process. These can be edited anytime.
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center">
                                        <img class="h-12 w-12 rounded-full" src="https://ui-avatars.com/api/?name=Jack" alt="Jack">
                                        <div class="ml-4">
                                            <h5 class="text-lg font-medium text-gray-900 dark:text-white">Jack</h5>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Counter Staff</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center">
                                        <img class="h-12 w-12 rounded-full" src="https://ui-avatars.com/api/?name=Harry" alt="Harry">
                                        <div class="ml-4">
                                            <h5 class="text-lg font-medium text-gray-900 dark:text-white">Harry</h5>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Counter Staff</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button"
                    @click="completeSetup()"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Continue!
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function completeSetup() {
        fetch('{{ route("admin.complete-setup") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                // Hide the modal first
                const modal = document.querySelector('[x-data]');
                if (modal && modal.__x) {
                    modal.__x.$data.show = false;
                }
                // Then reload the page after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 300);
            } else {
                console.error('Error completing setup');
                alert('There was an error completing the setup. Please try again.');
            }
        }).catch(error => {
            console.error('Error:', error);
            alert('There was an error completing the setup. Please try again.');
        });
    }
</script>
@endpush
