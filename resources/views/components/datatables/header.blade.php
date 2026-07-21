<div class="flex">
    <div class="table-search-wrapper flex items-center">
        <label class="block">
            <input type="text" class="table-search-input filter-item form-input w-0 bg-transparent px-2 text-right
                        transition-all duration-150 placeholder:text-slate-500
                        dark:placeholder:text-navy-200" placeholder="Search here..." />
            <input type="text" id="status_filter" placeholder="status" value="x" hidden>
        </label>
        <button
            class="table-search-toggle btn border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </div>&nbsp;

    <button
        class="ac-trigger btn border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
        <i class="fa-solid fa-filter"></i>
    </button>&nbsp;

    @if(isset($canRecycle) && $canRecycle)
        <a href="{{ route('admin.' . $modul . '.recycle') }}"
            class="btn border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
            <i class="fa-solid fa-trash-can-arrow-up"></i>
        </a>&nbsp;
    @endif

    @if(isset($canAdd) && $canAdd)
        <a href="{{ route('admin.' . $modul . '.create') }}"
            class="btn border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
            <i class="fa-solid fa-plus"></i>
        </a>
    @endif
</div>