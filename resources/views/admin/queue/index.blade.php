@extends('layouts.admin')

@section('header')
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Queue Management</h2>
@endsection

@section('content')
<div class="space-y-6" x-data="{ showAddModal: false }">
    <!-- Queue Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Average Wait Time -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-500/10">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Average Wait Time</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">15 mins</p>
                </div>
            </div>
        </div>

        <!-- People in Queue -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-500/10">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">People in Queue</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">23</p>
                </div>
            </div>
        </div>

        <!-- Served Today -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-500/10">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Served Today</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">45</p>
                </div>
            </div>
        </div>

        <!-- Active Counters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-500/10">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Counters</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">4/5</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-between items-center">
        <div>
            <a href="{{ route('admin.queue.list') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors duration-200">
                Queue List
            </a>
        </div>

        <div class="flex space-x-2">
            <button @click="showAddModal = true"
                   class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors duration-200">
                Add to Queue
            </button>
            <a href="{{ route('admin.queue.tracking') }}"
               class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 transition-colors duration-200">
                Queue Tracking
            </a>
        </div>
    </div>

    <!-- Queue List Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-6 text-gray-900 dark:text-white">Queue List</h3>

            <!-- Queue Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b dark:border-gray-700">
                            <th class="text-left py-3 px-4 text-gray-500 dark:text-gray-400 text-sm font-medium">
                                Queue Number
                            </th>
                            <th class="text-left py-3 px-4 text-gray-500 dark:text-gray-400 text-sm font-medium">
                                Name
                            </th>
                            <th class="text-left py-3 px-4 text-gray-500 dark:text-gray-400 text-sm font-medium">
                                Phone Number
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($queueItems as $item)
                            <tr class="border-b dark:border-gray-700">
                                <td class="py-4 px-4 text-gray-900 dark:text-gray-300">
                                    {{ $item->queue_number }}
                                </td>
                                <td class="py-4 px-4 text-gray-900 dark:text-gray-300">
                                    {{ $item->name }}
                                </td>
                                <td class="py-4 px-4 text-gray-900 dark:text-gray-300">
                                    {{ $item->phone_number }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 px-4 text-center text-gray-500 dark:text-gray-400">
                                    No queue items found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add to Queue Modal -->
    <div x-show="showAddModal"
         class="fixed inset-0 z-50 overflow-y-auto"
         @click.self="showAddModal = false"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-black/50" @click="showAddModal = false"></div>

            <!-- Modal Container -->
            <div class="relative" x-data="{ isSubmitting: false }">
                <!-- Rotating Rings Container -->
                <div class="absolute inset-0" style="transform: scale(1.2);">
                    <!-- Outer Ring -->
                    <div class="absolute inset-0 rounded-full border-[4px] border-blue-400/50"
                         style="animation: spin 6s linear infinite, breathe 3s ease-in-out infinite;">
                    </div>
                    <!-- Inner Ring -->
                    <div class="absolute inset-0 rounded-full border-[4px] border-blue-300/40"
                         style="transform: scale(0.95); animation: spin-reverse 6s linear infinite, breathe 3s ease-in-out infinite 1.5s;">
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="bg-white dark:bg-gray-800 rounded-full w-96 h-96 relative z-10 flex flex-col items-center justify-center"
                     @click.stop>
                    <!-- Form -->
                    <div class="w-2/3">
                        <h3 class="text-xl font-semibold mb-6 text-center text-gray-900 dark:text-white">Queue</h3>
                        <form id="queueForm" @submit.prevent="submitForm">
                            <div class="space-y-4">
                                <input type="text"
                                       name="phone_number"
                                       id="phone_number"
                                       required
                                       placeholder="Enter phone number or name"
                                       class="w-full px-4 py-2 text-center text-lg rounded-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       x-bind:disabled="isSubmitting"
                                       autocomplete="off">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update the style section -->
<style>
@keyframes spin {
    from {
        transform: rotate(0deg) scale(1);
    }
    to {
        transform: rotate(360deg) scale(1);
    }
}

@keyframes spin-reverse {
    from {
        transform: rotate(360deg) scale(0.95);
    }
    to {
        transform: rotate(0deg) scale(0.95);
    }
}

@keyframes breathe {
    0%, 100% {
        opacity: 0.5;
        transform: scale(1.0);
    }
    50% {
        opacity: 0.3;
        transform: scale(1.15);
    }
}

/* Add this for smoother animations */
.absolute {
    backface-visibility: hidden;
    transform-style: preserve-3d;
    will-change: transform, opacity;
}
</style>

@push('scripts')
<script>
function submitForm() {
    const form = document.getElementById('queueForm');
    const phoneNumber = document.getElementById('phone_number').value;

    // Set submitting state
    this.isSubmitting = true;

    fetch('{{ route('admin.queue.store') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            phone_number: phoneNumber
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            Toastify({
                text: data.message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#4CAF50",
            }).showToast();

            // Reset form and close modal
            form.reset();
            this.showAddModal = false;

            // Refresh the queue list
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Toastify({
            text: "Error adding to queue",
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "#EF4444",
        }).showToast();
    })
    .finally(() => {
        this.isSubmitting = false;
    });
}
</script>
@endpush
@endsection

