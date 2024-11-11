@extends('layouts.admin')

@section('header')
    Category Management
@endsection

@section('content')
<div x-data="categoryManagement()" class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
    <div class="p-6">
        <!-- Level Tabs -->
        <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                <li class="mr-2">
                    <button @click="currentLevel = 'Level 1'" :class="{'border-blue-600 text-blue-600': currentLevel === 'Level 1'}" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300">Level 1</button>
                </li>
                <li class="mr-2">
                    <button @click="currentLevel = 'Level 2'" :class="{'border-blue-600 text-blue-600': currentLevel === 'Level 2'}" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300">Level 2</button>
                </li>
                <li class="mr-2">
                    <button @click="currentLevel = 'Level 3'" :class="{'border-blue-600 text-blue-600': currentLevel === 'Level 3'}" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300">Level 3</button>
                </li>
            </ul>
        </div>

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4 md:mb-0">Category</h2>
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

        <!-- Categories Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">S No.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Img</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acronym</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Display ON</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    <template x-for="(category, index) in filteredCategories" :key="category.id">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white" x-text="startIndex + index + 1"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img x-show="category.image_path" :src="category.image_path" class="h-8 w-8 rounded-full" :alt="category.name">
                                <span x-show="!category.image_path">-</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white" x-text="category.name"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white" x-text="category.acronym"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <span x-text="category.display_on_transfer ? 'Transfer & ' : ''"></span>
                                <span x-text="category.display_on_ticket ? 'Ticket Screen' : ''"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button @click="editCategory(category)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600 mr-3">Edit</button>
                                <button @click="deleteCategory(category.id)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600">Delete</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-between items-center">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Showing <span x-text="startIndex + 1"></span> to <span x-text="Math.min(startIndex + perPage, totalCategories)"></span> of <span x-text="totalCategories"></span> entries
            </div>
            <div class="flex space-x-2">
                <button @click="previousPage" :disabled="currentPage === 1" class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 disabled:opacity-50">Previous</button>
                <button @click="nextPage" :disabled="currentPage >= totalPages" class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 disabled:opacity-50">Next</button>
            </div>
        </div>
    </div>

    <!-- Add/Edit Category Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Modal content -->
    </div>

    <!-- Add Category Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form @submit.prevent="submitCategory">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white" id="modal-title">
                                    Add Level 1 Category
                                </h3>
                                <div class="mt-6 space-y-4">
                                    <!-- Category Name -->
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Enter Category Name
                                        </label>
                                        <input type="text"
                                               x-model="newCategory.name"
                                               id="name"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    </div>

                                    <!-- Category Name (Other Language) -->
                                    <div>
                                        <label for="name_other" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Category Name (Other Language)
                                        </label>
                                        <input type="text"
                                               x-model="newCategory.name_other"
                                               id="name_other"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    </div>

                                    <!-- Category Description -->
                                    <div>
                                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Category Description
                                        </label>
                                        <textarea x-model="newCategory.description"
                                                  id="description"
                                                  rows="3"
                                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                                    </div>

                                    <!-- Acronym -->
                                    <div>
                                        <label for="acronym" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Enter Acronym
                                        </label>
                                        <input type="text"
                                               x-model="newCategory.acronym"
                                               id="acronym"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    </div>

                                    <!-- Display Options -->
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <input type="checkbox"
                                                   x-model="newCategory.display_on_transfer"
                                                   id="display_on_transfer"
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <label for="display_on_transfer" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                Display on Transfer Screen
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox"
                                                   x-model="newCategory.display_on_ticket"
                                                   id="display_on_ticket"
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <label for="display_on_ticket" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                Display on Ticket Screen
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox"
                                                   x-model="newCategory.display_on_backend"
                                                   id="display_on_backend"
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <label for="display_on_backend" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                Backend Screen
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Category Priority -->
                                    <div>
                                        <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Category Priority
                                        </label>
                                        <input type="number"
                                               x-model="newCategory.priority"
                                               id="priority"
                                               min="1"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            OK
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

    @push('scripts')
    <script>
    function categoryManagement() {
        return {
            categories: @json($categories),
            currentLevel: 'Level 1',
            perPage: 10,
            currentPage: 1,
            search: '',
            showModal: false,
            newCategory: {
                name: '',
                name_other: '',
                description: '',
                acronym: '',
                display_on_transfer: true,
                display_on_ticket: true,
                display_on_backend: true,
                priority: 1,
                level: 'Level 1'
            },

            get filteredCategories() {
                return this.categories.filter(category =>
                    category.level === this.currentLevel &&
                    (category.name.toLowerCase().includes(this.search.toLowerCase()) ||
                     category.acronym.toLowerCase().includes(this.search.toLowerCase()))
                );
            },

            async submitCategory() {
                try {
                    // Validate acronym length before submission
                    if (this.newCategory.acronym && this.newCategory.acronym.length > 10) {
                        this.newCategory.acronym = this.newCategory.acronym.substring(0, 10);
                    }

                    // If no acronym is provided, generate one from the name
                    if (!this.newCategory.acronym) {
                        this.newCategory.acronym = this.newCategory.name
                            .split(' ')
                            .map(word => word[0])
                            .join('')
                            .toUpperCase()
                            .substring(0, 10);
                    }

                    const response = await fetch('{{ route("admin.categories.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            name: this.newCategory.name,
                            name_other: this.newCategory.name_other,
                            description: this.newCategory.description,
                            acronym: this.newCategory.acronym,
                            level: this.currentLevel,
                            priority: this.newCategory.priority || 1,
                            display_on_transfer: this.newCategory.display_on_transfer,
                            display_on_ticket: this.newCategory.display_on_ticket,
                            display_on_backend: this.newCategory.display_on_backend
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Add the new category to the list
                        this.categories.unshift(data.category);
                        this.showModal = false;
                        // Reset form
                        this.newCategory = {
                            name: '',
                            name_other: '',
                            description: '',
                            acronym: '',
                            display_on_transfer: true,
                            display_on_ticket: true,
                            display_on_backend: true,
                            priority: 1,
                            level: this.currentLevel
                        };
                    } else {
                        alert(data.message || 'Error creating category. Please try again.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error creating category. Please try again.');
                }
            }
        }
    }
    </script>
    @endpush
</div>
@endsection
