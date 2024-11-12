@extends('layouts.admin')

@section('header')
    Theme Settings
@endsection

@section('content')
<div x-data="themeBuilder()" class="space-y-6">
    <!-- Branding Section -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Branding Settings
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                Configure your site's logo and branding text
            </p>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700">
            <form @submit.prevent="updateBranding" class="p-6 space-y-6" enctype="multipart/form-data">
                @csrf
                <!-- Site Title -->
                <div>
                    <label for="site_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Site Title
                    </label>
                    <input type="text"
                           x-model="branding.site_title"
                           id="site_title"
                           name="site_title"
                           placeholder="KYC Queue Manager"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <!-- Logo Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Site Logo
                    </label>
                    <div class="mt-1 flex items-center space-x-4">
                        <div class="flex-shrink-0 h-12 w-12 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700">
                            <img x-show="branding.logo_preview" :src="branding.logo_preview" class="h-full w-full object-cover">
                            <div x-show="!branding.logo_preview" class="h-full w-full flex items-center justify-center text-gray-400">
                                No logo
                            </div>
                        </div>
                        <input type="file"
                               @change="handleLogoUpload($event)"
                               accept="image/*"
                               name="logo"
                               class="block w-full text-sm text-gray-500 dark:text-gray-400
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100
                                      dark:file:bg-gray-700 dark:file:text-gray-300">
                    </div>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Recommended size: 200x200 pixels. Max file size: 2MB.
                    </p>
                </div>

                <!-- Favicon Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Favicon
                    </label>
                    <div class="mt-1 flex items-center space-x-4">
                        <div class="flex-shrink-0 h-8 w-8 rounded overflow-hidden bg-gray-100 dark:bg-gray-700">
                            <img x-show="branding.favicon_preview" :src="branding.favicon_preview" class="h-full w-full object-cover">
                            <div x-show="!branding.favicon_preview" class="h-full w-full flex items-center justify-center text-gray-400">
                                No icon
                            </div>
                        </div>
                        <input type="file"
                               @change="handleFaviconUpload($event)"
                               accept="image/x-icon,image/png"
                               name="favicon"
                               class="block w-full text-sm text-gray-500 dark:text-gray-400
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100
                                      dark:file:bg-gray-700 dark:file:text-gray-300">
                    </div>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Recommended size: 32x32 pixels. Accepted formats: .ico, .png
                    </p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Save Branding Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview Section -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Live Preview
            </h3>
            <div class="flex space-x-2">
                <button @click="previewMode = 'desktop'"
                        :class="{'bg-blue-500 text-white': previewMode === 'desktop'}"
                        class="px-3 py-1 rounded">
                    Desktop
                </button>
                <button @click="previewMode = 'mobile'"
                        :class="{'bg-blue-500 text-white': previewMode === 'mobile'}"
                        class="px-3 py-1 rounded">
                    Mobile
                </button>
            </div>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div :class="{'max-w-sm mx-auto': previewMode === 'mobile'}"
                     class="border rounded-lg overflow-hidden">
                    <iframe id="preview-frame"
                            class="w-full transition-all duration-300"
                            :style="previewMode === 'mobile' ? 'height: 667px; width: 375px;' : 'height: 600px;'"
                            srcdoc="">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function themeBuilder() {
    return {
        previewMode: 'desktop',
        branding: {
            site_title: @json($settings->site_title ?? 'KYC Queue Manager'),
            logo: null,
            logo_preview: @json($settings->logo ? Storage::url($settings->logo) : ''),
            favicon: null,
            favicon_preview: @json($settings->favicon ? Storage::url($settings->favicon) : '')
        },

        handleLogoUpload(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    showToast('File size must be less than 2MB', 'error');
                    event.target.value = '';
                    return;
                }
                this.branding.logo = file;
                this.branding.logo_preview = URL.createObjectURL(file);
                showToast('Logo selected successfully');
            }
        },

        handleFaviconUpload(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 500 * 1024) {
                    showToast('File size must be less than 500KB', 'error');
                    event.target.value = '';
                    return;
                }
                this.branding.favicon = file;
                this.branding.favicon_preview = URL.createObjectURL(file);
                showToast('Favicon selected successfully');
            }
        },

        async updateBranding() {
            try {
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('site_title', this.branding.site_title);

                if (this.branding.logo instanceof File) {
                    formData.append('logo', this.branding.logo);
                }
                if (this.branding.favicon instanceof File) {
                    formData.append('favicon', this.branding.favicon);
                }

                const response = await fetch('{{ route("admin.settings.theme.branding") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    showToast('Branding settings updated successfully');

                    // Show specific messages for uploads
                    if (this.branding.logo instanceof File) {
                        showToast('Logo uploaded successfully');
                    }
                    if (this.branding.favicon instanceof File) {
                        showToast('Favicon uploaded successfully');
                    }

                    // Reload the page after a short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showToast(result.message || 'Failed to update branding settings', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error updating branding settings', 'error');
            }
        }
    }
}

// Toast notification function
function showToast(message, type = 'success') {
    Toastify({
        text: message,
        duration: 3000,
        gravity: "top",
        position: "right",
        backgroundColor: type === 'success' ? "#48BB78" : "#F56565",
        stopOnFocus: true,
    }).showToast();
}
</script>
@endpush
@endsection
