<div
        class="modal fixed inset-0 z-[100] flex flex-col items-center justify-center overflow-hidden px-4 py-6 sm:px-5"
        id="modal1"
        role="dialog"
      >
        <div class="modal-overlay absolute inset-0 bg-slate-900/60"></div>
        <div
          class="modal-content scrollbar-sm relative flex max-w-lg flex-col items-center overflow-y-auto rounded-lg bg-white px-4 py-10 text-center dark:bg-navy-700 sm:px-5"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="inline size-28 shrink-0 text-success"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>

          <div class="mt-4">
            <h2 class="text-2xl text-slate-700 dark:text-navy-100">
              Success Message
            </h2>
            <p class="mt-2">
              Lorem ipsum dolor sit amet, consectetur adipisicing elit.
              Consequuntur dignissimos soluta totam?
            </p>
            <button
              data-close-modal
              class="btn mt-6 bg-success font-medium text-white hover:bg-success-focus focus:bg-success-focus active:bg-success-focus/90"
            >
              Close
            </button>
          </div>
        </div>
      </div>