<div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
    <div class="sm:col-span-2">
        <label class="block space-y-3">
            <span>Province</span>
            <select name="province" id="province-select" class="filter-province filter-item form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                <option value="">-- Select Province --</option>
                @foreach($provinces as $prov)
                    <option value="{{ $prov->name }}" {{ old('province') == $prov->name ? 'selected' : '' }}>{{ $prov->name }}</option>
                @endforeach
            </select>
        </label>
    </div>

    <div class="sm:col-span-2">
        <label class="block space-y-3">
            <span>City</span>
            <select name="city" id="city-select" class="filter-city filter-item form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                <option value="">-- Select City --</option>
            </select>
        </label>
    </div>

    <div class="sm:col-span-2">
        <label class="block">
            <span>Source</span>
            <select name="source" class="filter-source filter-item form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                <option value="">-- Select Source --</option>
                <option value="Website">Website</option>
                <option value="WhatsApp">WhatsApp</option>
                <option value="Instagram">Instagram</option>
                <option value="Tiktok">Tiktok</option>
                <option value="Facebook">Facebook</option>
                <option value="Internal">Internal</option>
            </select>
        </label>
    </div>

    <div class="sm:col-span-2">
        <label class="block space-y-1.5">
            <span>Status</span>
            <select name="status" class="filter-status filter-item form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                <option value="">-- Select Status --</option>
                <option value="New">New</option>
                <option value="Contacted">Contacted</option>
                <option value="Qualified">Qualified</option>
                <option value="Rejected">Rejected</option>
            </select>
        </label>
    </div>
</div>

