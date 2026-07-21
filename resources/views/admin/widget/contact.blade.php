<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:gap-6">
        <div class="card px-4 pb-4 sm:px-5">
          <div class="my-3 flex h-8 items-center justify-between">
            <h2 class="font-medium tracking-wide text-slate-700 line-clamp-1 dark:text-navy-100">
              Contact List
            </h2>

            <div
              x-data="usePopper({placement:'bottom-end',offset:4})"
              @click.outside="isShowPopper &amp;&amp; (isShowPopper = false)"
              class="inline-flex"
            >
              <button
                x-ref="popperRef"
                @click="isShowPopper = !isShowPopper"
                class="btn -mr-1.5 size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25"
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
                style="position: fixed; inset: auto 0px 0px auto; margin: 0px; transform: translate(-856px, 477px);"
                data-popper-reference-hidden=""
                data-popper-escaped=""
                data-popper-placement="top-end"
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

          <div class="space-y-3.5" x-data="{expandedItem:'item-1'}">
            <div x-data="accordionItem('item-1')">
              <div class="flex items-center justify-between">
                <div class="flex space-x-4">
                  <div class="avatar size-10">
                    <img class="rounded-full" src="images/200x200.png" alt="avatar" />
                  </div>
                  <div>
                    <h3 class="font-medium text-slate-700 line-clamp-1 dark:text-navy-100">
                      Konnor Guzman
                    </h3>
                    <p class="mt-0.5 text-xs line-clamp-1">(01) 22 888 4444</p>
                  </div>
                </div>
                <button
                  @click="expanded = !expanded"
                  class="btn size-7 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25"
                >
                  <i :class="expanded &amp;&amp; '-rotate-180'" class="fas fa-chevron-down transition-transform -rotate-180"></i>
                </button>
              </div>
              <div x-show="expanded" x-collapse="">
                <div class="flex justify-between pt-4">
                  <button
                    class="btn size-7 rounded-full bg-success/10 p-0 text-success hover:bg-success/20 focus:bg-success/20 active:bg-success/25"
                  >
                    <i class="fa-solid fa-phone text-xs"></i>
                  </button>
                  <button
                    class="btn size-7 rounded-full bg-warning/10 p-0 text-warning hover:bg-warning/20 focus:bg-warning/20 active:bg-warning/25"
                  >
                    <i class="fa-solid fa-video text-xs"></i>
                  </button>
                  <button
                    class="btn size-7 rounded-full bg-secondary/10 p-0 text-secondary hover:bg-secondary/20 focus:bg-secondary/20 active:bg-secondary/25 dark:bg-secondary-light/10 dark:text-secondary-light dark:hover:bg-secondary-light/20 dark:focus:bg-secondary-light/20 dark:active:bg-secondary-light/25"
                  >
                    <i class="fa-regular fa-comment text-xs"></i>
                  </button>
                  <button
                    class="btn size-7 rounded-full bg-info/10 p-0 text-info hover:bg-info/20 focus:bg-info/20 active:bg-info/25"
                  >
                    <i class="fa-regular fa-envelope text-xs"></i>
                  </button>
                  <button
                    class="btn size-7 rounded-full bg-slate-150 p-0 text-slate-800 hover:bg-slate-200 focus:bg-slate-200 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:focus:bg-navy-450 dark:active:bg-navy-450/90"
                  >
                    <i class="fa-solid fa-ellipsis"></i>
                  </button>
                </div>
              </div>
            </div>
            <div x-data="accordionItem('item-2')">
              <div class="flex items-center justify-between">
                <div class="flex space-x-4">
                  <div class="avatar size-10">
                    <img class="rounded-full" src="images/200x200.png" alt="avatar" />
                  </div>
                  <div>
                    <h3 class="font-medium text-slate-700 line-clamp-1 dark:text-navy-100">
                      Alfredo Elliott
                    </h3>
                    <p class="mt-0.5 text-xs line-clamp-1">(095)-800-8313</p>
                  </div>
                </div>
                <button
                  @click="expanded = !expanded"
                  class="btn size-7 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25"
                >
                  <i :class="expanded &amp;&amp; '-rotate-180'" class="fas fa-chevron-down transition-transform"></i>
                </button>
              </div>
              <div
                x-show="expanded"
                x-collapse=""
                style="display: none; height: 0px; overflow: hidden;"
                hidden=""
              >
                <div class="flex justify-between pt-4">
                  <button
                    class="btn size-7 rounded-full bg-success/10 p-0 text-success hover:bg-success/20 focus:bg-success/20 active:bg-success/25"
                  >
                    <i class="fa-solid fa-phone text-xs"></i>
                  </button>
                  <button
                    class="btn size-7 rounded-full bg-warning/10 p-0 text-warning hover:bg-warning/20 focus:bg-warning/20 active:bg-warning/25"
                  >
                    <i class="fa-solid fa-video text-xs"></i>
                  </button>
                  <button
                    class="btn size-7 rounded-full bg-secondary/10 p-0 text-secondary hover:bg-secondary/20 focus:bg-secondary/20 active:bg-secondary/25 dark:bg-secondary-light/10 dark:text-secondary-light dark:hover:bg-secondary-light/20 dark:focus:bg-secondary-light/20 dark:active:bg-secondary-light/25"
                  >
                    <i class="fa-regular fa-comment text-xs"></i>
                  </button>
                  <button
                    class="btn size-7 rounded-full bg-info/10 p-0 text-info hover:bg-info/20 focus:bg-info/20 active:bg-info/25"
                  >
                    <i class="fa-regular fa-envelope text-xs"></i>
                  </button>
                  <button
                    class="btn size-7 rounded-full bg-slate-150 p-0 text-slate-800 hover:bg-slate-200 focus:bg-slate-200 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:focus:bg-navy-450 dark:active:bg-navy-450/90"
                  >
                    <i class="fa-solid fa-ellipsis"></i>
                  </button>
                </div>
              </div>
            </div>
            <div x-data="accordionItem('item-3')">
              <div class="flex items-center justify-between">
                <div class="flex space-x-4">
                  <div class="avatar size-10">
                    <div class="is-initial rounded-full bg-info text-sm-plus uppercase text-white">ds</div>
                  </div>
                  <div>
                    <h3 class="font-medium text-slate-700 line-clamp-1 dark:text-navy-100">
                      Derrick Simmons
                    </h3>
                    <p class="mt-0.5 text-xs line-clamp-1">(350)-813-3861</p>
                  </div>
                </div>
                <button
                  @click="expanded = !expanded"
                  class="btn size-7 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25"
                >
                  <i :class="expanded &amp;&amp; '-rotate-180'" class="fas fa-chevron-down transition-transform"></i>
                </button>
              </div>
              <div
                x-show="expanded"
                x-collapse=""
                style="display: none; height: 0px; overflow: hidden;"
                hidden=""
              >
                <div class="flex justify-between pt-4">
                  <button
                    class="btn size-7 rounded-full bg-success/10 p-0 text-success hover:bg-success/20 focus:bg-success/20 active:bg-success/25"
                  >
                    <i class="fa-solid fa-phone text-xs"></i>
                  </button>
                  <button
                    class="btn size-7 rounded-full bg-warning/10 p-0 text-warning hover:bg-warning/20 focus:bg-warning/20 active:bg-warning/25"
                  >
                    <i class="fa-solid fa-video text-xs"></i>
                  </button>
                  <button
                    class="btn size-7 rounded-full bg-secondary/10 p-0 text-secondary hover:bg-secondary/20 focus:bg-secondary/20 active:bg-secondary/25 dark:bg-secondary-light/10 dark:text-secondary-light dark:hover:bg-secondary-light/20 dark:focus:bg-secondary-light/20 dark:active:bg-secondary-light/25"
                  >
                    <i class="fa-regular fa-comment text-xs"></i>
                  </button>
                  <button
                    class="btn size-7 rounded-full bg-info/10 p-0 text-info hover:bg-info/20 focus:bg-info/20 active:bg-info/25"
                  >
                    <i class="fa-regular fa-envelope text-xs"></i>
                  </button>
                  <button
                    class="btn size-7 rounded-full bg-slate-150 p-0 text-slate-800 hover:bg-slate-200 focus:bg-slate-200 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:focus:bg-navy-450 dark:active:bg-navy-450/90"
                  >
                    <i class="fa-solid fa-ellipsis"></i>
                  </button>
                </div>
              </div>
            </div>
            <div x-data="accordionItem('item-4')">
              <div class="flex items-center justify-between">
                <div class="flex space-x-4">
                  <div class="avatar size-10">
                    <img class="rounded-full" src="images/200x200.png" alt="avatar" />
                  </div>
                  <div>
                    <h3 class="font-medium text-slate-700 line-clamp-1 dark:text-navy-100">
                      Henry Curtis
                    </h3>
                    <p class="mt-0.5 text-xs line-clamp-1">(675)-975-0083</p>
                  </div>
                </div>
                <button
                  @click="expanded = !expanded"
                  class="btn size-7 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25"
                >
                  <i :class="expanded &amp;&amp; '-rotate-180'" class="fas fa-chevron-down transition-transform"></i>
                </button>
              </div>
              <div
                x-show="expanded"
                x-collapse=""
                style="display: none; height: 0px; overflow: hidden;"
                hidden=""
              >
                <div class="flex justify-between pt-4">
                  <button
                    class="btn size-7 rounded-full bg-success/10 p-0 text-success hover:bg-success/20 focus:bg-success/20 active:bg-success/25"
                  >
                    <i class="fa-solid fa-phone text-xs"></i>
                  </button>
                  <button
                    class="btn size-7 rounded-full bg-warning/10 p-0 text-warning hover:bg-warning/20 focus:bg-warning/20 active:bg-warning/25"
                  >
                    <i class="fa-solid fa-video text-xs"></i>
                  </button>
                  <button
                    class="btn size-7 rounded-full bg-secondary/10 p-0 text-secondary hover:bg-secondary/20 focus:bg-secondary/20 active:bg-secondary/25 dark:bg-secondary-light/10 dark:text-secondary-light dark:hover:bg-secondary-light/20 dark:focus:bg-secondary-light/20 dark:active:bg-secondary-light/25"
                  >
                    <i class="fa-regular fa-comment text-xs"></i>
                  </button>
                  <button
                    class="btn size-7 rounded-full bg-info/10 p-0 text-info hover:bg-info/20 focus:bg-info/20 active:bg-info/25"
                  >
                    <i class="fa-regular fa-envelope text-xs"></i>
                  </button>
                  <button
                    class="btn size-7 rounded-full bg-slate-150 p-0 text-slate-800 hover:bg-slate-200 focus:bg-slate-200 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:focus:bg-navy-450 dark:active:bg-navy-450/90"
                  >
                    <i class="fa-solid fa-ellipsis"></i>
                  </button>
                </div>
              </div>
            </div>
            <div x-data="accordionItem('item-5')">
              <div class="flex items-center justify-between">
                <div class="flex space-x-4">
                  <div class="avatar size-10">
                    <img class="rounded-full" src="images/200x200.png" alt="avatar" />
                  </div>
                  <div>
                    <h3 class="font-medium text-slate-700 line-clamp-1 dark:text-navy-100">
                      John Doe
                    </h3>
                    <p class="mt-0.5 text-xs line-clamp-1">(727)-810-3880</p>
                  </div>
                </div>
                <button
                  @click="expanded = !expanded"
                  class="btn size-7 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25"
                >
                  <i :class="expanded &amp;&amp; '-rotate-180'" class="fas fa-chevron-down transition-transform"></i>
                </button>
              </div>
              <div
                x-show="expanded"
                x-collapse=""
                style="display: none; height: 0px; overflow: hidden;"
                hidden=""
              >
                <div class="flex justify-between pt-4">
                  <button
                    class="btn size-7 rounded-full bg-success/10 p-0 text-success hover:bg-success/20 focus:bg-success/20 active:bg-success/25"
                  >
                    <i class="fa-solid fa-phone text-xs"></i>
                  </button>
                  <button
                    class="btn size-7 rounded-full bg-warning/10 p-0 text-warning hover:bg-warning/20 focus:bg-warning/20 active:bg-warning/25"
                  >
                    <i class="fa-solid fa-video text-xs"></i>
                  </button>
                  <button
                    class="btn size-7 rounded-full bg-secondary/10 p-0 text-secondary hover:bg-secondary/20 focus:bg-secondary/20 active:bg-secondary/25 dark:bg-secondary-light/10 dark:text-secondary-light dark:hover:bg-secondary-light/20 dark:focus:bg-secondary-light/20 dark:active:bg-secondary-light/25"
                  >
                    <i class="fa-regular fa-comment text-xs"></i>
                  </button>
                  <button
                    class="btn size-7 rounded-full bg-info/10 p-0 text-info hover:bg-info/20 focus:bg-info/20 active:bg-info/25"
                  >
                    <i class="fa-regular fa-envelope text-xs"></i>
                  </button>
                  <button
                    class="btn size-7 rounded-full bg-slate-150 p-0 text-slate-800 hover:bg-slate-200 focus:bg-slate-200 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:focus:bg-navy-450 dark:active:bg-navy-450/90"
                  >
                    <i class="fa-solid fa-ellipsis"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card px-4 pb-4 sm:px-5">
          <div class="my-3 flex h-8 items-center justify-between">
            <h2 class="font-medium tracking-wide text-slate-700 dark:text-navy-100">Recent Payments</h2>
            <a
              href="#"
              class="border-b border-dotted border-current pb-0.5 text-xs-plus font-medium text-primary outline-hidden transition-colors duration-300 hover:text-primary/70 focus:text-primary/70 dark:text-accent-light dark:hover:text-accent-light/70 dark:focus:text-accent-light/70"
              >View All</a
            >
          </div>
          <div class="space-y-3.5">
            <div class="flex cursor-pointer items-center justify-between">
              <div class="flex items-center space-x-3.5">
                <div class="avatar">
                  <img class="rounded-full" src="images/200x200.png" alt="avatar" />
                </div>
                <div>
                  <p class="font-medium text-slate-700 dark:text-navy-100">Konnor Guzman</p>
                  <p class="text-xs text-slate-400 line-clamp-1 dark:text-navy-300">
                    Dec 21, 2021 - 08:05
                  </p>
                </div>
              </div>
              <p class="font-medium text-slate-600 dark:text-navy-100">$660.22</p>
            </div>
            <div class="flex cursor-pointer items-center justify-between">
              <div class="flex items-center space-x-3.5">
                <div class="avatar">
                  <img class="rounded-full" src="images/200x200.png" alt="avatar" />
                </div>
                <div>
                  <p class="font-medium text-slate-700 dark:text-navy-100">Henry Curtis</p>
                  <p class="text-xs text-slate-400 line-clamp-1 dark:text-navy-300">
                    Dec 19, 2021 - 11:55
                  </p>
                </div>
              </div>
              <p class="font-medium text-slate-600 dark:text-navy-100">$33.63</p>
            </div>
            <div class="flex cursor-pointer items-center justify-between">
              <div class="flex items-center space-x-3.5">
                <div class="avatar">
                  <img class="rounded-full" src="images/200x200.png" alt="avatar" />
                </div>
                <div>
                  <p class="font-medium text-slate-700 dark:text-navy-100">Derrick Simmons</p>
                  <p class="text-xs text-slate-400 line-clamp-1 dark:text-navy-300">
                    Dec 16, 2021 - 14:45
                  </p>
                </div>
              </div>
              <p class="font-medium text-slate-600 dark:text-navy-100">$674.63</p>
            </div>
            <div class="flex cursor-pointer items-center justify-between">
              <div class="flex items-center space-x-3.5">
                <div class="avatar">
                  <img class="rounded-full" src="images/200x200.png" alt="avatar" />
                </div>
                <div>
                  <p class="font-medium text-slate-700 dark:text-navy-100">Kartina West</p>
                  <p class="text-xs text-slate-400 line-clamp-1 dark:text-navy-300">
                    Dec 13, 2021 - 11:30
                  </p>
                </div>
              </div>
              <p class="font-medium text-slate-600 dark:text-navy-100">$547.63</p>
            </div>
            <div class="flex cursor-pointer items-center justify-between">
              <div class="flex items-center space-x-3.5">
                <div class="avatar">
                  <img class="rounded-full" src="images/200x200.png" alt="avatar" />
                </div>
                <div>
                  <p class="font-medium text-slate-700 dark:text-navy-100">Samantha Shelton</p>
                  <p class="text-xs text-slate-400 line-clamp-1 dark:text-navy-300">
                    Dec 10, 2021 - 09:41
                  </p>
                </div>
              </div>
              <p class="font-medium text-slate-600 dark:text-navy-100">$736.24</p>
            </div>
            <div class="flex cursor-pointer items-center justify-between">
              <div class="flex items-center space-x-3.5">
                <div class="avatar">
                  <img class="rounded-full" src="images/200x200.png" alt="avatar" />
                </div>
                <div>
                  <p class="font-medium text-slate-700 dark:text-navy-100">Joe Perkins</p>
                  <p class="text-xs text-slate-400 line-clamp-1 dark:text-navy-300">
                    Dec 06, 2021 - 11:41
                  </p>
                </div>
              </div>
              <p class="font-medium text-slate-600 dark:text-navy-100">$736.24</p>
            </div>
          </div>
        </div>
      </div>