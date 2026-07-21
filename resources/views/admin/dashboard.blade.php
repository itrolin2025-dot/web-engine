<x-app-layout>
  <!-- <div class="mt-4 grid grid-cols-12 gap-4 sm:mt-5 sm:gap-5 lg:mt-6 lg:gap-6">
    <div class="col-span-12 space-y-4 sm:space-y-5 lg:col-span-8 lg:space-y-6">
      
      @include('admin.widget.info')

      @include('admin.widget.project')

      @include('admin.widget.contact')
      
      
    </div>
    <div class="col-span-12 lg:col-span-4">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-1 lg:gap-6">
        @include('admin.widget.calender')

        @include('admin.widget.quotes')

        @include('admin.widget.messages')
      </div>
    </div>
  </div> -->
  <div class="mt-4 grid grid-cols-12 gap-4 sm:mt-5 sm:gap-5 lg:mt-6 lg:gap-6">
    <div class="col-span-12 lg:col-span-8 xl:col-span-9">
      <div class="mt-10"></div>
      <div
        class="mt-4 card col-span-12 mt-12 bg-gradient-to-r from-blue-500 to-blue-600 p-5 sm:col-span-8 sm:mt-0 sm:flex-row">
        <div class="flex justify-center sm:order-last">
          <img class="-mt-16 h-40 sm:mt-0" src="../images/illustrations/responsive.svg" alt="image" />
        </div>
        <div class="mt-2 flex-1 pt-2 text-center text-white sm:mt-0 sm:text-left">
          <h3 class="text-xl">
            Good morning, <span class="font-semibold">Mr. Nobody</span>
          </h3>
          <p class="mt-2 leading-relaxed">Have a nice day at work</p>
          <p>Progress is <span class="font-semibold">excellent!</span></p>

          <!-- <button class="btn mt-6 border border-white/10 bg-white/20 text-white hover:bg-white/30 focus:bg-white/30">
            View Schedule
          </button> -->
        </div>
      </div>

      <div class="mt-4 sm:mt-5 lg:mt-6">
        <div class="flex h-8 items-center justify-between">
          <h2 class="text-base font-medium tracking-wide text-slate-700 dark:text-navy-100">
            Appointment request
          </h2>
          <a href="#"
            class="border-b border-dotted border-current pb-0.5 text-xs+ font-medium text-primary outline-none transition-colors duration-300 hover:text-primary/70 focus:text-primary/70 dark:text-accent-light dark:hover:text-accent-light/70 dark:focus:text-accent-light/70">View
            All</a>
        </div>
        <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-3 sm:gap-5">
          <div class="card space-y-4 p-5">
            <div class="flex items-center space-x-3">
              <div class="avatar">
                <img class="rounded-full" src="../images/200x200.png" alt="image" />
              </div>
              <div>
                <h3 class="font-medium text-slate-700 line-clamp-1 dark:text-navy-100">
                  Travis Fuller
                </h3>
                <p class="mt-0.5 text-xs text-slate-400 dark:text-navy-300">
                  Scaling
                </p>
              </div>
            </div>
            <div>
              <p>Thu, 26 March</p>
              <p class="text-xl font-medium text-slate-700 dark:text-navy-100">
                08:00
              </p>
            </div>
            <div class="flex justify-between">
              <div class="flex space-x-2">
                <button
                  class="btn h-7 w-7 rounded-full bg-success/10 p-0 text-success hover:bg-success/20 focus:bg-success/20 active:bg-success/25">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </button>
                <button
                  class="btn h-7 w-7 rounded-full bg-error/10 p-0 text-error hover:bg-error/20 focus:bg-error/20 active:bg-error/25">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <button
                class="btn h-7 w-7 rounded-full bg-slate-150 p-0 font-medium text-slate-800 hover:bg-slate-200 hover:shadow-lg hover:shadow-slate-200/50 focus:bg-slate-200 focus:shadow-lg focus:shadow-slate-200/50 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:hover:shadow-navy-450/50 dark:focus:bg-navy-450 dark:focus:shadow-navy-450/50 dark:active:bg-navy-450/90">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 rotate-45" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                </svg>
              </button>
            </div>
          </div>
          <div class="card space-y-4 p-5">
            <div class="flex items-center space-x-3">
              <div class="avatar">
                <img class="rounded-full" src="../images/200x200.png" alt="image" />
              </div>
              <div>
                <h3 class="font-medium text-slate-700 line-clamp-1 dark:text-navy-100">
                  Alfredo Elliott
                </h3>
                <p class="mt-0.5 text-xs text-slate-400 dark:text-navy-300">
                  Checkup
                </p>
              </div>
            </div>
            <div>
              <p>Mon, 15 March</p>
              <p class="text-xl font-medium text-slate-700 dark:text-navy-100">
                06:00
              </p>
            </div>
            <div class="flex justify-between">
              <div class="flex space-x-2">
                <button
                  class="btn h-7 w-7 rounded-full bg-success/10 p-0 text-success hover:bg-success/20 focus:bg-success/20 active:bg-success/25">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </button>
                <button
                  class="btn h-7 w-7 rounded-full bg-error/10 p-0 text-error hover:bg-error/20 focus:bg-error/20 active:bg-error/25">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <button
                class="btn h-7 w-7 rounded-full bg-slate-150 p-0 font-medium text-slate-800 hover:bg-slate-200 hover:shadow-lg hover:shadow-slate-200/50 focus:bg-slate-200 focus:shadow-lg focus:shadow-slate-200/50 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:hover:shadow-navy-450/50 dark:focus:bg-navy-450 dark:focus:shadow-navy-450/50 dark:active:bg-navy-450/90">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 rotate-45" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                </svg>
              </button>
            </div>
          </div>
          <div class="card space-y-4 p-5">
            <div class="flex items-center space-x-3">
              <div class="avatar">
                <img class="rounded-full" src="../images/200x200.png" alt="image" />
              </div>
              <div>
                <h3 class="font-medium text-slate-700 line-clamp-1 dark:text-navy-100">
                  Derrick Simmons
                </h3>
                <p class="mt-0.5 text-xs text-slate-400 dark:text-navy-300">
                  Checkup
                </p>
              </div>
            </div>
            <div>
              <p>Wed, 14 March</p>
              <p class="text-xl font-medium text-slate-700 dark:text-navy-100">
                11:00
              </p>
            </div>
            <div class="flex justify-between">
              <div class="flex space-x-2">
                <button
                  class="btn h-7 w-7 rounded-full bg-success/10 p-0 text-success hover:bg-success/20 focus:bg-success/20 active:bg-success/25">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </button>
                <button
                  class="btn h-7 w-7 rounded-full bg-error/10 p-0 text-error hover:bg-error/20 focus:bg-error/20 active:bg-error/25">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <button
                class="btn h-7 w-7 rounded-full bg-slate-150 p-0 font-medium text-slate-800 hover:bg-slate-200 hover:shadow-lg hover:shadow-slate-200/50 focus:bg-slate-200 focus:shadow-lg focus:shadow-slate-200/50 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:hover:shadow-navy-450/50 dark:focus:bg-navy-450 dark:focus:shadow-navy-450/50 dark:active:bg-navy-450/90">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 rotate-45" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-4 sm:mt-5 lg:mt-6">
        <div class="flex h-8 items-center justify-between">
          <h2 class="text-base font-medium tracking-wide text-slate-700 dark:text-navy-100">
            Appointment request
          </h2>
          <a href="#"
            class="border-b border-dotted border-current pb-0.5 text-xs+ font-medium text-primary outline-none transition-colors duration-300 hover:text-primary/70 focus:text-primary/70 dark:text-accent-light dark:hover:text-accent-light/70 dark:focus:text-accent-light/70">View
            All</a>
        </div>
        <div class="card mt-3">
          <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
            <table class="is-hoverable w-full text-left">
              <thead>
                <tr>
                  <th
                    class="whitespace-nowrap rounded-tl-lg bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                    NAME
                  </th>
                  <th
                    class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                    LOCATION
                  </th>
                  <th
                    class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                    DATETIME
                  </th>
                  <th
                    class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                    STATUS
                  </th>

                  <th
                    class="whitespace-nowrap rounded-tr-lg bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <div class="flex items-center space-x-4">
                      <div class="avatar h-9 w-9">
                        <img class="rounded-full" src="../images/200x200.png" alt="avatar" />
                      </div>

                      <span class="font-medium text-slate-700 dark:text-navy-100">Anthony Jensen
                      </span>
                    </div>
                  </td>
                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <a href="#" class="hover:underline focus:underline">London, Kliniken Clinic
                    </a>
                  </td>
                  <td class="whitespace-nowrap px-4 py-3 font-medium text-slate-600 dark:text-navy-100 sm:px-5">
                    Mon, 12 May - 09:00
                  </td>
                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-4 h-5 w-5" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </td>

                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <button
                      class="btn h-8 w-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                      </svg>
                    </button>
                  </td>
                </tr>
                <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <div class="flex items-center space-x-4">
                      <div class="avatar h-9 w-9">
                        <img class="rounded-full" src="../images/200x200.png" alt="avatar" />
                      </div>

                      <span class="font-medium text-slate-700 dark:text-navy-100">Konnor Guzman
                      </span>
                    </div>
                  </td>
                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <a href="#" class="hover:underline focus:underline">Manchester, PLC Home Health
                    </a>
                  </td>
                  <td class="whitespace-nowrap px-4 py-3 font-medium text-slate-600 dark:text-navy-100 sm:px-5">
                    Tue, 17 June - 14:30
                  </td>
                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-4 h-5 w-5 text-error" fill="none"
                      viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </td>

                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <button
                      class="btn h-8 w-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                      </svg>
                    </button>
                  </td>
                </tr>
                <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <div class="flex items-center space-x-4">
                      <div class="avatar h-9 w-9">
                        <img class="rounded-full" src="../images/200x200.png" alt="avatar" />
                      </div>

                      <span class="font-medium text-slate-700 dark:text-navy-100">Derrick Simmons
                      </span>
                    </div>
                  </td>
                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <a href="#" class="hover:underline focus:underline">Liverpool, Life flash Clinic
                    </a>
                  </td>
                  <td class="whitespace-nowrap px-4 py-3 font-medium text-slate-600 dark:text-navy-100 sm:px-5">
                    Wed, 29 May - 13:30
                  </td>
                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-4 h-5 w-5 text-error" fill="none"
                      viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </td>

                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <button
                      class="btn h-8 w-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                      </svg>
                    </button>
                  </td>
                </tr>

                <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <div class="flex items-center space-x-4">
                      <div class="avatar h-9 w-9">
                        <img class="rounded-full" src="../images/200x200.png" alt="avatar" />
                      </div>

                      <span class="font-medium text-slate-700 dark:text-navy-100">Henry Curtis
                      </span>
                    </div>
                  </td>
                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <a href="#" class="hover:underline focus:underline">London, Kliniken Clinic
                    </a>
                  </td>
                  <td class="whitespace-nowrap px-4 py-3 font-medium text-slate-600 dark:text-navy-100 sm:px-5">
                    Mon, 22 June - 15:00
                  </td>
                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-4 h-5 w-5" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </td>

                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <button
                      class="btn h-8 w-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                      </svg>
                    </button>
                  </td>
                </tr>
                <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <div class="flex items-center space-x-4">
                      <div class="avatar h-9 w-9">
                        <img class="rounded-full" src="../images/200x200.png" alt="avatar" />
                      </div>

                      <span class="font-medium text-slate-700 dark:text-navy-100">Katrina West
                      </span>
                    </div>
                  </td>
                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <a href="#" class="hover:underline focus:underline">Manchester, PLC Home Health
                    </a>
                  </td>
                  <td class="whitespace-nowrap px-4 py-3 font-medium text-slate-600 dark:text-navy-100 sm:px-5">
                    Tue, 17 June - 14:30
                  </td>
                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-4 h-5 w-5 text-error" fill="none"
                      viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </td>

                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <button
                      class="btn h-8 w-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                      </svg>
                    </button>
                  </td>
                </tr>

                <tr class="border-y border-transparent">
                  <td class="whitespace-nowrap rounded-bl-lg px-4 py-3 sm:px-5">
                    <div class="flex items-center space-x-4">
                      <div class="avatar h-9 w-9">
                        <img class="rounded-full" src="../images/200x200.png" alt="avatar" />
                      </div>

                      <span class="font-medium text-slate-700 dark:text-navy-100">Travis Fuller
                      </span>
                    </div>
                  </td>
                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <a href="#" class="hover:underline focus:underline">Liverpool, Life flash Clinic
                    </a>
                  </td>
                  <td class="whitespace-nowrap px-4 py-3 font-medium text-slate-600 dark:text-navy-100 sm:px-5">
                    Wed, 19 May - 11:30
                  </td>
                  <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-4 h-5 w-5" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </td>

                  <td class="whitespace-nowrap rounded-br-lg px-4 py-3 sm:px-5">
                    <button
                      class="btn h-8 w-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                      </svg>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-span-12 lg:col-span-4 xl:col-span-3">
      <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-1 sm:gap-5">
        <div class="flex mt-6 h-8 items-center justify-between">
          <h2 class="text-base font-medium tracking-wide text-slate-700 dark:text-navy-100">
            Transaction Request
          </h2>
          <a href="#"
            class="border-b border-dotted border-current pb-0.5 text-xs+ font-medium text-primary outline-none transition-colors duration-300 hover:text-primary/70 focus:text-primary/70 dark:text-accent-light dark:hover:text-accent-light/70 dark:focus:text-accent-light/70">View
            All</a>
        </div>
        <div class="mt-1 grid grid-cols-1 gap-4 sm:grid-cols-1 sm:gap-5">
          <div class="card space-y-4 p-5">
            <div class="flex items-center space-x-3">
              <div class="avatar">
                <img class="rounded-full" src="../images/200x200.png" alt="image" />
              </div>
              <div>
                <h3 class="font-medium text-slate-700 line-clamp-1 dark:text-navy-100">
                  Travis Fuller
                </h3>
                <p class="mt-0.5 text-xs text-slate-400 dark:text-navy-300">
                  Scaling
                </p>
              </div>
            </div>
            <div>
              <p>Thu, 26 March</p>
              <p class="text-xl font-medium text-slate-700 dark:text-navy-100">
                08:00
              </p>
            </div>
            <div class="flex justify-between">
              <div class="flex space-x-2">
                <button
                  class="btn h-7 w-7 rounded-full bg-success/10 p-0 text-success hover:bg-success/20 focus:bg-success/20 active:bg-success/25">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </button>
                <button
                  class="btn h-7 w-7 rounded-full bg-error/10 p-0 text-error hover:bg-error/20 focus:bg-error/20 active:bg-error/25">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <button
                class="btn h-7 w-7 rounded-full bg-slate-150 p-0 font-medium text-slate-800 hover:bg-slate-200 hover:shadow-lg hover:shadow-slate-200/50 focus:bg-slate-200 focus:shadow-lg focus:shadow-slate-200/50 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:hover:shadow-navy-450/50 dark:focus:bg-navy-450 dark:focus:shadow-navy-450/50 dark:active:bg-navy-450/90">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 rotate-45" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                </svg>
              </button>
            </div>
          </div>
          <div class="card space-y-4 p-5">
            <div class="flex items-center space-x-3">
              <div class="avatar">
                <img class="rounded-full" src="../images/200x200.png" alt="image" />
              </div>
              <div>
                <h3 class="font-medium text-slate-700 line-clamp-1 dark:text-navy-100">
                  Travis Fuller
                </h3>
                <p class="mt-0.5 text-xs text-slate-400 dark:text-navy-300">
                  Scaling
                </p>
              </div>
            </div>
            <div>
              <p>Thu, 26 March</p>
              <p class="text-xl font-medium text-slate-700 dark:text-navy-100">
                08:00
              </p>
            </div>
            <div class="flex justify-between">
              <div class="flex space-x-2">
                <button
                  class="btn h-7 w-7 rounded-full bg-success/10 p-0 text-success hover:bg-success/20 focus:bg-success/20 active:bg-success/25">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </button>
                <button
                  class="btn h-7 w-7 rounded-full bg-error/10 p-0 text-error hover:bg-error/20 focus:bg-error/20 active:bg-error/25">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <button
                class="btn h-7 w-7 rounded-full bg-slate-150 p-0 font-medium text-slate-800 hover:bg-slate-200 hover:shadow-lg hover:shadow-slate-200/50 focus:bg-slate-200 focus:shadow-lg focus:shadow-slate-200/50 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:hover:shadow-navy-450/50 dark:focus:bg-navy-450 dark:focus:shadow-navy-450/50 dark:active:bg-navy-450/90">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 rotate-45" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                </svg>
              </button>
            </div>
          </div>
          <div class="card space-y-4 p-5">
            <div class="flex items-center space-x-3">
              <div class="avatar">
                <img class="rounded-full" src="../images/200x200.png" alt="image" />
              </div>
              <div>
                <h3 class="font-medium text-slate-700 line-clamp-1 dark:text-navy-100">
                  Travis Fuller
                </h3>
                <p class="mt-0.5 text-xs text-slate-400 dark:text-navy-300">
                  Scaling
                </p>
              </div>
            </div>
            <div>
              <p>Thu, 26 March</p>
              <p class="text-xl font-medium text-slate-700 dark:text-navy-100">
                08:00
              </p>
            </div>
            <div class="flex justify-between">
              <div class="flex space-x-2">
                <button
                  class="btn h-7 w-7 rounded-full bg-success/10 p-0 text-success hover:bg-success/20 focus:bg-success/20 active:bg-success/25">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </button>
                <button
                  class="btn h-7 w-7 rounded-full bg-error/10 p-0 text-error hover:bg-error/20 focus:bg-error/20 active:bg-error/25">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <button
                class="btn h-7 w-7 rounded-full bg-slate-150 p-0 font-medium text-slate-800 hover:bg-slate-200 hover:shadow-lg hover:shadow-slate-200/50 focus:bg-slate-200 focus:shadow-lg focus:shadow-slate-200/50 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:hover:shadow-navy-450/50 dark:focus:bg-navy-450 dark:focus:shadow-navy-450/50 dark:active:bg-navy-450/90">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 rotate-45" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
</x-app-layout>