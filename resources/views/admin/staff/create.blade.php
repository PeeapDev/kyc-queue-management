@extends('layouts.admin')

@section('header')
    Add Staff
@endsection

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
    <div class="p-6">
        <form action="{{ route('admin.staff.store') }}" method="POST" class="space-y-6" id="staffForm">
            @csrf

            <!-- Add this right after the form opening tag -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <strong class="font-bold">Error!</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 required">
                        Name *
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact -->
                <div>
                    <label for="contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Contact
                    </label>
                    <input type="text"
                           name="contact"
                           id="contact"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('contact')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Email
                    </label>
                    <input type="email"
                           name="email"
                           id="email"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 required">
                        Username *
                    </label>
                    <input type="text"
                           name="username"
                           id="username"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           required>
                    @error('username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 required">
                        Password *
                    </label>
                    <input type="password"
                           name="password"
                           id="password"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 required">
                        Confirm Password *
                    </label>
                    <input type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           required>
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Address
                    </label>
                    <textarea name="address"
                              id="address"
                              rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unique ID (Auto-generated) -->
                <div>
                    <label for="unique_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Staff ID (Auto-generated)
                    </label>
                    <input type="text"
                           name="unique_id"
                           id="unique_id"
                           value="{{ str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           readonly>
                    @error('unique_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Categories Dropdown -->
                <div>
                    <label for="categories" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Assign Categories
                    </label>
                    <select name="categories[]"
                            id="categories"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            multiple>
                        <option value="">Select Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div class="mt-2">
                        <button type="button"
                                id="selectAllCategories"
                                class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            Select All Categories
                        </button>
                    </div>
                    @error('categories')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- User Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        User Role
                    </label>
                    <select name="role"
                            id="role"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Select User Role</option>
                        <option value="counter_staff">Counter Staff</option>
                        <option value="supervisor">Supervisor</option>
                        <option value="manager">Manager</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Counter Selection -->
                <div>
                    <label for="counter_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Choose Counter
                    </label>
                    <select name="counter_id"
                            id="counter_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Select Counter</option>
                        @foreach($counters as $counter)
                            <option value="{{ $counter->id }}">{{ $counter->name }}</option>
                        @endforeach
                    </select>
                    @error('counter_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Show Next Button -->
            <div class="mt-6">
                <label class="inline-flex items-center">
                    <input type="checkbox"
                           name="show_next_button"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Show Next Button - Call Screen</span>
                </label>
            </div>

            <!-- Desktop Notifications -->
            <div class="mt-6">
                <label class="inline-flex items-center">
                    <input type="checkbox"
                           name="desktop_notifications"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Enable Desktop Notifications</span>
                </label>
            </div>

            <div class="flex justify-end mt-6 space-x-3">
                <a href="{{ route('admin.staff.index') }}"
                   class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                    Create Staff
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for categories dropdown
    $('#categories').select2({
        placeholder: 'Select Categories',
        allowClear: true,
        theme: 'default'
    });

    // Handle select all categories
    document.getElementById('selectAllCategories').addEventListener('click', function() {
        const options = document.querySelectorAll('#categories option');
        const currentlySelected = $('#categories').val() || [];

        if (currentlySelected.length === options.length - 1) { // -1 for the placeholder option
            // If all are selected, deselect all
            $('#categories').val(null).trigger('change');
        } else {
            // Select all except the placeholder
            const allValues = Array.from(options)
                .filter(option => option.value !== '') // Exclude the placeholder
                .map(option => option.value);
            $('#categories').val(allValues).trigger('change');
        }
    });

    // Auto-generate staff ID when the form loads
    document.getElementById('unique_id').value = String(Math.floor(100000 + Math.random() * 900000));
});
</script>

<style>
/* Custom styles for Select2 dropdown */
.select2-container--default .select2-selection--multiple {
    background-color: inherit;
    border-color: inherit;
}

.dark .select2-container--default .select2-selection--multiple {
    background-color: rgb(55, 65, 81);
    border-color: rgb(75, 85, 99);
    color: white;
}

.dark .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: rgb(75, 85, 99);
    border-color: rgb(85, 95, 109);
    color: white;
}

.dark .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: rgb(200, 200, 200);
}

.dark .select2-dropdown {
    background-color: rgb(55, 65, 81);
    border-color: rgb(75, 85, 99);
    color: white;
}

.dark .select2-container--default .select2-results__option[aria-selected=true] {
    background-color: rgb(75, 85, 99);
}

.dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: rgb(85, 95, 109);
}
</style>
@endpush
@endsection
