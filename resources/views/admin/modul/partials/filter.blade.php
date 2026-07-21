<div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
    <div class="sm:col-span-2">
        <label class="block">
            <span>Parent</span>
            <div class="relative">
                <select class="filter-parent filter-item form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                    <option value="">Choose Parent</option>
                    @foreach ($parents as $parent)
                        <option value="{{ $parent->id }}">
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </label>
    </div>

    <div class="sm:col-span-2">
        <label class="block">
            <span>Name</span>
            <x-input class="filter-name" placeholder="Enter Modul Name" autocomplete="off"/>
        </label>
    </div>

    <div class="sm:col-span-2">
        <label class="block space-y-1.5">
            <span>Code</span>
            <x-input class="filter-kode" placeholder="Enter Modul Code" autocomplete="off"/>
        </label>
    </div>
</div>
       