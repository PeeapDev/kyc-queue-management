@extends('layouts.admin')

@section('header')
    User Management
@endsection

@section('content')
<div x-data="userManagement()" class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
    <div class="p-6">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Users List</h2>
            <button @click="showModal = true" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add New User
            </button>
        </div>

        <!-- User Type Filters -->
        <div class="mb-6 flex items-center space-x-6">
            <label class="inline-flex items-center">
                <input type="checkbox"
                       x-model="filters.onlineRegistration"
                       class="form-checkbox h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                <span class="ml-2 text-gray-700 dark:text-gray-300">Online Registration</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox"
                       x-model="filters.staffCreated"
                       class="form-checkbox h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                <span class="ml-2 text-gray-700 dark:text-gray-300">Staff Created</span>
            </label>
        </div>

        <!-- Users Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Registration Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Joined Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    <template x-if="filteredUsers.length === 0">
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                No users found
                            </td>
                        </tr>
                    </template>
                    <template x-for="user in filteredUsers" :key="user.id">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="user.name"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400" x-text="user.email"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                      :class="user.registration_type === 'online' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'"
                                      x-text="user.registration_type === 'online' ? 'Online Registration' : 'Staff Created'">
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="formatDate(user.created_at)"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600 mr-3">Edit</a>
                                <a href="#" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600">Delete</a>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Showing <span x-text="paginationInfo.from"></span> to <span x-text="paginationInfo.to"></span> of <span x-text="paginationInfo.total"></span> entries
                </div>
                <div class="flex space-x-2">
                    <button @click="previousPage"
                            :disabled="currentPage === 1"
                            class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 disabled:opacity-50">Previous</button>
                    <button @click="nextPage"
                            :disabled="currentPage >= totalPages"
                            class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 disabled:opacity-50">Next</button>
                </div>
            </div>
        </div>

        <!-- Add User Modal -->
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form @submit.prevent="submitUser">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white" id="modal-title">
                                        Add New User
                                    </h3>
                                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                                        <!-- First Name -->
                                        <div>
                                            <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                First Name
                                            </label>
                                            <input type="text"
                                                   x-model="newUser.first_name"
                                                   id="first_name"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                   required>
                                        </div>

                                        <!-- Last Name -->
                                        <div>
                                            <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Last Name
                                            </label>
                                            <input type="text"
                                                   x-model="newUser.last_name"
                                                   id="last_name"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                   required>
                                        </div>

                                        <!-- Email -->
                                        <div>
                                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Email
                                            </label>
                                            <input type="email"
                                                   x-model="newUser.email"
                                                   id="email"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                   required>
                                        </div>

                                        <!-- Phone Number -->
                                        <div>
                                            <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Phone Number
                                            </label>
                                            <input type="tel"
                                                   x-model="newUser.phone_number"
                                                   id="phone_number"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                   required>
                                        </div>

                                        <!-- Location -->
                                        <div>
                                            <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Location
                                            </label>
                                            <input type="text"
                                                   x-model="newUser.location"
                                                   id="location"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                   required>
                                        </div>

                                        <!-- Age -->
                                        <div>
                                            <label for="age" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Age
                                            </label>
                                            <input type="number"
                                                   x-model="newUser.age"
                                                   id="age"
                                                   min="18"
                                                   max="120"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                   required>
                                        </div>

                                        <!-- Notification Preferences -->
                                        <div class="col-span-2 space-y-4">
                                            <div class="flex items-center">
                                                <input type="checkbox"
                                                       x-model="newUser.notifications_email"
                                                       id="notifications_email"
                                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <label for="notifications_email" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                    Receive Email Notifications
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="checkbox"
                                                       x-model="newUser.notifications_sms"
                                                       id="notifications_sms"
                                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <label for="notifications_sms" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                    Receive SMS Notifications
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Create User
                            </button>
                            <button type="button"
                                    @click="showModal = false"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function userManagement() {
    return {
        users: @json($users),
        showModal: false,
        newUser: {
            first_name: '',
            last_name: '',
            email: '',
            phone_number: '',
            location: '',
            age: '',
            notifications_email: true,
            notifications_sms: true
        },
        filters: {
            onlineRegistration: true,
            staffCreated: true
        },

        get filteredUsers() {
            return this.users.filter(user => {
                const matchesSearch = user.name.toLowerCase().includes(this.search.toLowerCase()) ||
                                    user.email.toLowerCase().includes(this.search.toLowerCase());

                const matchesFilters = (this.filters.onlineRegistration && user.registration_type === 'online') ||
                                     (this.filters.staffCreated && user.registration_type === 'staff');

                return matchesSearch && matchesFilters;
            });
        },

        get paginatedUsers() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredUsers.slice(start, start + this.perPage);
        },

        get totalPages() {
            return Math.ceil(this.filteredUsers.length / this.perPage);
        },

        get paginationInfo() {
            const total = this.filteredUsers.length;
            const from = Math.min(((this.currentPage - 1) * this.perPage) + 1, total);
            const to = Math.min(this.currentPage * this.perPage, total);
            return { from, to, total };
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

        async submitUser() {
            try {
                const response = await fetch('{{ route("admin.users.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        first_name: this.newUser.first_name,
                        last_name: this.newUser.last_name,
                        email: this.newUser.email,
                        phone_number: this.newUser.phone_number,
                        location: this.newUser.location,
                        age: this.newUser.age,
                        registration_type: 'staff',
                        notifications_email: this.newUser.notifications_email,
                        notifications_sms: this.newUser.notifications_sms,
                        send_email_notification: this.newUser.notifications_email,
                        send_sms_notification: this.newUser.notifications_sms
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Add the new user to the list
                    this.users.unshift(data.user);
                    this.showModal = false;
                    // Reset form
                    this.newUser = {
                        first_name: '',
                        last_name: '',
                        email: '',
                        phone_number: '',
                        location: '',
                        age: '',
                        notifications_email: true,
                        notifications_sms: true
                    };
                } else {
                    alert(data.message || 'Error creating user');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error creating user. Please try again.');
            }
        }
    }
}
</script>
@endpush
@endsection