<div>
        <div class="flex items-center justify-between">
          <h2 class="text-sm-plus font-medium tracking-wide text-slate-700 dark:text-navy-100">
            Ongoing Projects
          </h2>
          <div
            x-data="usePopper({placement:'bottom-end',offset:4})"
            @click.outside="isShowPopper &amp;&amp; (isShowPopper = false)"
            class="inline-flex"
          >
            <button
              x-ref="popperRef"
              @click="isShowPopper = !isShowPopper"
              class="btn size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="size-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"
                ></path>
              </svg>
            </button>

            <div
              x-ref="popperRoot"
              class="popper-root"
              :class="isShowPopper &amp;&amp; 'show'"
              style="position: fixed; inset: auto 0px 0px auto; margin: 0px; transform: translate(-453px, 27px);"
              data-popper-placement="top-end"
              data-popper-reference-hidden=""
            >
              <div class="popper-box rounded-md border border-slate-150 bg-white py-1.5 font-inter dark:border-navy-500 dark:bg-navy-700">
                <ul>
                  <li>
                    <a
                      href="#"
                      class="flex h-8 items-center px-3 pr-8 font-medium tracking-wide outline-hidden transition-all hover:bg-slate-100 hover:text-slate-800 focus:bg-slate-100 focus:text-slate-800 dark:hover:bg-navy-600 dark:hover:text-navy-100 dark:focus:bg-navy-600 dark:focus:text-navy-100"
                      >Action</a
                    >
                  </li>
                  <li>
                    <a
                      href="#"
                      class="flex h-8 items-center px-3 pr-8 font-medium tracking-wide outline-hidden transition-all hover:bg-slate-100 hover:text-slate-800 focus:bg-slate-100 focus:text-slate-800 dark:hover:bg-navy-600 dark:hover:text-navy-100 dark:focus:bg-navy-600 dark:focus:text-navy-100"
                      >Another Action</a
                    >
                  </li>
                  <li>
                    <a
                      href="#"
                      class="flex h-8 items-center px-3 pr-8 font-medium tracking-wide outline-hidden transition-all hover:bg-slate-100 hover:text-slate-800 focus:bg-slate-100 focus:text-slate-800 dark:hover:bg-navy-600 dark:hover:text-navy-100 dark:focus:bg-navy-600 dark:focus:text-navy-100"
                      >Something else</a
                    >
                  </li>
                </ul>
                <div class="my-1 h-px bg-slate-150 dark:bg-navy-500"></div>
                <ul>
                  <li>
                    <a
                      href="#"
                      class="flex h-8 items-center px-3 pr-8 font-medium tracking-wide outline-hidden transition-all hover:bg-slate-100 hover:text-slate-800 focus:bg-slate-100 focus:text-slate-800 dark:hover:bg-navy-600 dark:hover:text-navy-100 dark:focus:bg-navy-600 dark:focus:text-navy-100"
                      >Separated Link</a
                    >
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-3 space-y-3.5">
          <div class="card p-3">
            <div class="flex items-center space-x-3">
              <img
                class="size-10 rounded-lg object-cover object-center"
                src="images/illustrations/lms-ui.svg"
                alt="image"
              />
              <div class="flex-1">
                <div class="flex justify-between">
                  <p class="font-medium text-slate-700 dark:text-navy-100">LMS App Design</p>
                </div>
                <div class="mt-0.5 flex text-xs text-slate-400 dark:text-navy-300">
                  <p>Updated at 7 Sep</p>
                  <div class="mx-2 my-1 hidden w-px bg-slate-200 dark:bg-navy-500 sm:flex"></div>

                  <p class="hidden sm:flex">Deadline: 25.08.2020</p>
                </div>
              </div>
            </div>
            <p class="-mt-3 text-right text-xs font-medium text-primary dark:text-accent-light">
              24%
            </p>
            <div class="progress mt-2 h-1.5 bg-slate-150 dark:bg-navy-500">
              <div class="is-active relative w-4/12 overflow-hidden rounded-full bg-primary dark:bg-accent"></div>
            </div>
          </div>
          <div class="card p-3">
            <div class="flex items-center space-x-3">
              <img
                class="size-10 rounded-lg object-cover object-center"
                src="images/illustrations/store-ui.svg"
                alt="image"
              />
              <div class="flex-1">
                <div class="flex justify-between">
                  <p class="font-medium text-slate-700 dark:text-navy-100">Store Dashboard</p>
                </div>
                <div class="mt-0.5 flex text-xs text-slate-400 dark:text-navy-300">
                  <p>Updated a hour ago</p>
                  <div class="mx-2 my-1 hidden w-px bg-slate-200 dark:bg-navy-500 sm:flex"></div>

                  <p class="hidden sm:flex">Deadline: 21.08.2020</p>
                </div>
              </div>
            </div>
            <p class="-mt-3 text-right text-xs font-medium text-secondary dark:text-secondary-light">
              56%
            </p>

            <div class="progress mt-2 h-1.5 bg-secondary/15 dark:bg-secondary-light/25">
              <div class="w-6/12 rounded-full bg-secondary"></div>
            </div>
          </div>
          <div class="card p-3">
            <div class="flex items-center space-x-3">
              <img
                class="size-10 rounded-lg object-cover object-center"
                src="images/illustrations/chat-ui.svg"
                alt="image"
              />
              <div class="flex-1">
                <div class="flex justify-between">
                  <p class="font-medium text-slate-700 dark:text-navy-100">Chat Mobile App</p>
                </div>
                <div class="mt-0.5 flex text-xs text-slate-400 dark:text-navy-300">
                  <p>Updated 3 days ago</p>
                  <div class="mx-2 my-1 hidden w-px bg-slate-200 dark:bg-navy-500 sm:flex"></div>

                  <p class="hidden sm:flex">Deadline: 16.09.2020</p>
                </div>
              </div>
            </div>
            <p class="-mt-3 text-right text-xs font-medium text-warning">64%</p>

            <div class="progress mt-2 h-1.5 bg-warning/15 dark:bg-warning/25">
              <div class="w-7/12 rounded-full bg-warning"></div>
            </div>
          </div>
          <div class="card p-3">
            <div class="flex items-center space-x-3">
              <img
                class="size-10 rounded-lg object-cover object-center"
                src="images/illustrations/nft.svg"
                alt="image"
              />
              <div class="flex-1">
                <div class="flex justify-between">
                  <p class="font-medium text-slate-700 dark:text-navy-100">NFT Marketplace App</p>
                </div>
                <div class="mt-0.5 flex text-xs text-slate-400 dark:text-navy-300">
                  <p>Updated a week ago</p>
                  <div class="mx-2 my-1 hidden w-px bg-slate-200 dark:bg-navy-500 sm:flex"></div>

                  <p class="hidden sm:flex">Deadline: 26.11.2020</p>
                </div>
              </div>
            </div>
            <p class="-mt-3 text-right text-xs font-medium text-info">14%</p>

            <div class="progress mt-2 h-1.5 bg-info/15 dark:bg-info/25">
              <div class="w-2/12 rounded-full bg-info"></div>
            </div>
          </div>
        </div>
      </div>