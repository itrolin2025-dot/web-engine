<div class="flex justify-center space-x-2 pt-4 mt-4">
    <a href="{{ route('admin.' . $modul) }}" class="btn min-w-[7rem] border border-slate-300 font-medium text-slate-800
            hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80
            dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500
            dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
        Cancel
    </a>

    <button type="submit" id="save-btn"
        class="btn min-w-[7rem] bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
        Save
    </button>
</div>