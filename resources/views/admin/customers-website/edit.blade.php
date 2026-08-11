<x-app-layout>
    <div class="flex mb-4 items-center justify-between py-5 lg:py-6">
        <div class="flex items-center space-x-4">
            <h2 class="text-xl font-medium text-slate-800 dark:text-navy-50 lg:text-2xl">{{ $modul_name }}</h2>
            <div class="hidden h-full py-1 sm:flex">
                <div class="h-full w-px bg-slate-300 dark:bg-navy-600"></div>
            </div>
            <ul class="hidden flex-wrap items-center space-x-2 sm:flex">
                <li class="flex items-center space-x-2">
                    <a class="text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                        href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <i class="fa-solid fa-angle-right text-xs"></i>
                </li>
                <li class="flex items-center space-x-2">
                    <a class="text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                        href="{{ route('admin.customers-website') }}">Customers Website</a>
                    <i class="fa-solid fa-angle-right text-xs"></i>
                </li>
                <li>Edit</li>
            </ul>
        </div>
    </div>

    <div class="max-w-3xl">
        <div class="card p-4 sm:p-5">
            <h3 class="text-base font-medium text-slate-700 dark:text-navy-100 mb-4">Edit Customer Website</h3>

            <form action="{{ route('admin.customers-website.update', $website->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Customer -->
                    <label class="block">
                        <span class="font-medium text-slate-700 dark:text-navy-100">Customer <span class="text-error">*</span></span>
                        <select name="customer_id" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white dark:bg-navy-700 px-3 py-2 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" required>
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id', $website->customer_id) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <span class="text-xs text-error mt-1">{{ $message }}</span>
                        @enderror
                    </label>

                    <!-- Template -->
                    <label class="block">
                        <span class="font-medium text-slate-700 dark:text-navy-100">Template <span class="text-error">*</span></span>
                        <select name="template_id" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white dark:bg-navy-700 px-3 py-2 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" required>
                            <option value="">Select Template</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}" {{ old('template_id', $website->template_id) == $template->id ? 'selected' : '' }}>
                                    {{ $template->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('template_id')
                            <span class="text-xs text-error mt-1">{{ $message }}</span>
                        @enderror
                    </label>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Title -->
                    <label class="block">
                        <span class="font-medium text-slate-700 dark:text-navy-100">Website Title <span class="text-error">*</span></span>
                        <input name="title" value="{{ old('title', $website->title) }}" placeholder="Enter website title"
                            class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            type="text" required>
                        @error('title')
                            <span class="text-xs text-error mt-1">{{ $message }}</span>
                        @enderror
                    </label>

                    <!-- Domain -->
                    <label class="block">
                        <span class="font-medium text-slate-700 dark:text-navy-100">Domain</span>
                        <input name="domain" value="{{ old('domain', $website->domain) }}" placeholder="e.g. mywebsite.com"
                            class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            type="text">
                        @error('domain')
                            <span class="text-xs text-error mt-1">{{ $message }}</span>
                        @enderror
                    </label>
                </div>

                <!-- Description -->
                <label class="block">
                    <span class="font-medium text-slate-700 dark:text-navy-100">Description</span>
                    <textarea name="description" rows="3" placeholder="Website description..."
                        class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('description', $website->description) }}</textarea>
                    @error('description')
                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                    @enderror
                </label>

                <!-- Is Active Status -->
                <div class="flex items-center justify-between pt-2">
                    <span class="font-medium text-slate-700 dark:text-navy-100">Status</span>
                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                        <input name="is_active" type="checkbox" value="1" {{ old('is_active', $website->is_active) ? 'checked' : '' }}
                            class="form-switch is-outline h-5 w-10 rounded-full border border-slate-400/70 bg-slate-100 transition-colors checked:bg-primary checked:border-primary dark:border-navy-400 dark:bg-navy-900 dark:checked:bg-accent dark:checked:border-accent">
                        <span class="text-xs font-medium text-slate-600 dark:text-navy-200">Active</span>
                    </label>
                </div>

                <div class="mt-6 flex justify-end space-x-2 pt-4">
                    <a href="{{ route('admin.customers-website') }}"
                        class="btn min-w-[7rem] border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500">
                        Cancel
                    </a>
                    <button type="submit"
                        class="btn min-w-[7rem] bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus">
                        Update Website
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
