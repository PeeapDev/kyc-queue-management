@extends('layouts.admin')

@section('header')
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Add to Queue</h2>
@endsection

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form id="queueForm" class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                <input type="text" name="name" id="name" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number</label>
                <input type="tel" name="phone_number" id="phone_number" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Add to Queue
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('queueForm').addEventListener('submit', function(e) {
    e.preventDefault();

    fetch('{{ route('admin.queue.store') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            name: document.getElementById('name').value,
            phone_number: document.getElementById('phone_number').value
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

            // Reset form
            this.reset();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
</script>
@endpush
@endsection
