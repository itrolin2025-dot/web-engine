<div id="deleteModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center">
  <!-- Modal overlays, layered for effect -->
  <div class="absolute inset-0 bg-black/70 modal-overlay"></div>
  <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm modal-overlay"></div>

  <div class="modal-content scrollbar-sm relative flex max-w-md w-full mx-2 flex-col overflow-y-auto rounded-lg bg-white pt-10 pb-4 px-2 sm:px-6 text-center dark:bg-navy-700">
    <i class="fa-solid fa-trash text-4xl text-primary mb-4"></i>
    <div class="mt-4 px-2 sm:px-12">
      <h3 class="text-lg text-slate-800 dark:text-navy-50">Delete Data</h3>
      <p class="mt-1 text-slate-500 dark:text-navy-200">
        Are you sure delete <b id="deleteName"></b>?
      </p>
    </div>
    <div class="my-4 mt-16 h-px bg-slate-200 dark:bg-navy-500"></div>
    
    <!-- Button group, make stack on mobile for extra clarity -->
    <div class="flex flex-col sm:flex-row gap-3 justify-center items-center px-2">
      <button
        data-close-delete-modal
        class="btn w-full sm:w-auto min-w-[7rem] rounded-full border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90 py-3 px-4"
      >
        No
      </button>
      <button
        id="confirmDelete"
        class="btn w-full sm:w-auto min-w-[7rem] rounded-full bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90 py-3 px-4"
      >
        Yes
      </button>
    </div>
  </div>
</div>