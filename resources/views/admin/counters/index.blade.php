@extends('layouts.admin')

@section('header')
    Counter Management
@endsection

@section('content')
<div x-data="counterManagement()" class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
    <div class="p-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4 md:mb-0">Counter</h2>
            <div class="flex items-center space-x-4">
                <button @click="showModal = true" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add
                </button>
            </div>
        </div>

        <!-- Table Controls -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-4">
            <div class="flex items-center mb-4 md:mb-0">
                <span class="mr-2 text-gray-600 dark:text-gray-400">Show</span>
                <select x-model="perPage" @change="updateTable()" class="form-select rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                    <option>100</option>
                </select>
                <span class="ml-2 text-gray-600 dark:text-gray-400">entries</span>
            </div>

            <div class="flex items-center">
                <label class="mr-2 text-gray-600 dark:text-gray-400">Search:</label>
                <input type="search" x-model="search" @input="updateTable()" class="form-input rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Search...">
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Counters Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            S No.
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Created
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700" id="countersTableBody">
                    <template x-if="filteredCounters.length === 0">
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                No counters found
                            </td>
                        </tr>
                    </template>
                    <template x-for="(counter, index) in paginatedCounters" :key="counter.id">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white" x-text="startIndex + index + 1"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white" x-text="counter.name"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white" x-text="formatDate(counter.created_at)"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a :href="`/admin/counters/${counter.id}/edit`" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600 mr-3">Edit</a>
                                <button @click="deleteCounter(counter.id)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-between items-center">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Showing <span x-text="startIndex + 1"></span> to <span x-text="Math.min(startIndex + perPage, totalCounters)"></span> of <span x-text="totalCounters"></span> entries
            </div>
            <div class="flex space-x-2">
                <button @click="previousPage" :disabled="currentPage === 1" class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 disabled:opacity-50">Previous</button>
                <button @click="nextPage" :disabled="currentPage >= totalPages" class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 disabled:opacity-50">Next</button>
            </div>
        </div>
    </div>

    <!-- Add Counter Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Add Counter
                            </h3>
                            <div class="mt-4">
                                <label for="counterName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Enter Counter Name
                                </label>
                                <input type="text"
                                       x-model="newCounter.name"
                                       id="counterName"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            <div class="mt-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox"
                                           x-model="newCounter.showOnDisplay"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Show on display screen</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                            @click="submitCounter()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        OK
                    </button>
                    <button type="button"
                            @click="showModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function counterManagement() {
    return {
        counters: @json($counters),
        perPage: 10,
        currentPage: 1,
        search: '',
        showModal: false,
        newCounter: {
            name: '',
            showOnDisplay: false
        },

        get totalCounters() {
            return this.filteredCounters.length;
        },

        get totalPages() {
            return Math.ceil(this.totalCounters / this.perPage);
        },

        get startIndex() {
            return (this.currentPage - 1) * this.perPage;
        },

        get filteredCounters() {
            return this.counters.filter(counter =>
                counter.name.toLowerCase().includes(this.search.toLowerCase())
            );
        },

        get paginatedCounters() {
            return this.filteredCounters.slice(this.startIndex, this.startIndex + this.perPage);
        },

        formatDate(date) {
            return new Date(date).toLocaleDateString('en-GB');
        },

        updateTable() {
            this.currentPage = 1;
        },

        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },

        async submitCounter() {
            try {
                const response = await fetch('{{ route("admin.counters.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: this.newCounter.name,
                        show_on_display: this.newCounter.showOnDisplay
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Add the new counter to the list
                    this.counters.unshift(data.counter);
                    this.showModal = false;
                    this.newCounter.name = '';
                    this.newCounter.showOnDisplay = false;
                }
            } catch (error) {
                console.error('Error:', error);
            }
        },

        async deleteCounter(id) {
            if (!confirm('Are you sure you want to delete this counter?')) {
                return;
            }

            try {
                const response = await fetch(`/admin/counters/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (response.ok) {
                    this.counters = this.counters.filter(counter => counter.id !== id);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    }
}
</script>
@endpush
@endsection
