<x-app-layout>
    @include('components.forms.tittle')

    <form action="{{ route('admin.' . $modul . '.update', $article_category) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card p-4 sm:p-5">
            <div class="col-span-12 flex-1 flex flex-col" style="min-width:0;">
                <div class="card flex flex-col space-y-6 h-full p-6">
                    
                    {{-- Customer & Name --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Website Select --}}
                        <label class="block space-y-1.5">
                            <span>Website <span class="text-red-500">*</span></span>
                            <select name="customers_website_id" required class="form-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                <option value="">-- Select Website --</option>
                                @foreach($customers_websites as $cust)
                                    <option value="{{ $cust->id }}" {{ old('customers_website_id', $article_category->customers_website_id) == $cust->id ? 'selected' : '' }}>
                                        {{ $cust->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customers_website_id')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        {{-- Name --}}
                        <label class="block space-y-1.5">
                            <span>Name <span class="text-red-500">*</span></span>
                            <x-input name="name" value="{{ old('name', $article_category->name) }}" placeholder="Enter Article Category Name" autocomplete="off" required />
                            @error('name')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    {{-- Description --}}
                    <div class="grid grid-cols-1 gap-4">
                        <label class="block space-y-1.5">
                            <span>Description</span>
                            <textarea
                                name="description"
                                rows="4"
                                placeholder="Enter Article Category Description"
                                class="form-textarea w-full rounded-lg border border-slate-300 bg-transparent p-2.5 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            >{{ old('description', $article_category->description) }}</textarea>
                            @error('description')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                </div>
            </div>
        </div>

        @include('components.forms.update')
    </form>
</x-app-layout>
