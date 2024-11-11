@extends('layouts.admin')

@section('header')
    Edit Staff
@endsection

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
    <div class="p-6">
        <form action="{{ route('admin.staff.update', $staff) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <x-label for="name" value="{{ __('Name') }}" />
                    <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $staff->name)" required autofocus />
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact -->
                <div>
                    <x-label for="phone" value="{{ __('Contact') }}" />
                    <x-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $staff->phone)" />
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $staff->email)" required />
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username -->
                <div>
                    <x-label for="username" value="{{ __('Username') }}" />
                    <x-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username', $staff->username)" required />
                    @error('username')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <x-label for="password" value="{{ __('Password (leave blank to keep current)') }}" />
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" />
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                    <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
                </div>

                <!-- Rest of the fields remain the same as create.blade.php but with :value="old('field', $staff->field)" -->
                <!-- ... -->

            </div>

            <div class="flex items-center justify-end mt-6">
                <x-button>
                    {{ __('Update Staff') }}
                </x-button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Same JavaScript as create.blade.php
</script>
@endpush
@endsection
