<div class="ac-panel" style="width: 100%;">
    <div class="px-3 py-3">

        @include('admin.' . $modul . '.partials.filter')

        <div class="mt-4 space-x-1 text-right">

            <button id="btn_cancel"
                class="btn border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
                Cancel
            </button>&nbsp;

            <button id="btn_filter" type="button"
                class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                Apply
            </button>

        </div>
    </div>
</div>