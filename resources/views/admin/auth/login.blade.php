<!doctype html>
<html lang="en">

<head>
  <!-- Meta tags  -->
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />

  <title>Rolin - Login Page</title>
  <link rel="icon" type="image/png" href="{{ asset('images/app-logo.png') }}" />

  <!-- CSS Assets -->
  <link rel="stylesheet" href="../css/app.css" />

  <!-- Javascript Assets -->
  <script src="../js/app.js" defer></script>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />
  <script>
    /**
     * THIS SCRIPT REQUIRED FOR PREVENT FLICKERING IN SOME BROWSERS
     */
    localStorage.getItem("_x_darkMode_on") === "true" &&
      document.documentElement.classList.add("dark");
  </script>
</head>

<body x-data class="is-header-blur" x-bind="$store.global.documentBody">
  <!-- App preloader-->
  <div class="app-preloader fixed z-50 grid h-full w-full place-content-center bg-slate-50 dark:bg-navy-900">
    <div class="app-preloader-inner relative inline-block size-48"></div>
  </div>

  <!-- Page Wrapper -->
  <div id="root" class="min-h-100vh flex grow bg-slate-50 dark:bg-navy-900" x-cloak>
    <div class="fixed top-0 hidden p-6 lg:block lg:px-12">
      <a href="#" class="flex items-center space-x-2">
        <p class="text-xl font-semibold uppercase text-slate-700 dark:text-navy-100">
          ROLIN NUISEL NIAGA
        </p>
      </a>
    </div>
    <div class="hidden w-full place-items-center lg:grid">
      <div class="w-full max-w-lg p-6">
        <img class="w-full" x-show="!$store.global.isDarkModeEnabled" src="../images/illustrations/dashboard-check.svg"
          alt="image" />
        <!-- <img
            class="w-full"
            x-show="$store.global.isDarkModeEnabled"
            src="images/illustrations/dashboard-check-dark.svg"
            alt="image"
          /> -->
      </div>
    </div>
    <main class="flex w-full flex-col items-center bg-white dark:bg-navy-700 lg:max-w-md">
      <div class="flex w-full max-w-sm grow flex-col justify-center p-5">
        <div class="text-center">
          <!-- Tambahkan logo di atas Welcome Back -->
          <img src="{{ asset('images/app-logo.png') }}" alt="App Logo" class="mx-auto mb-4"
            style="width: 5rem; height: 5rem; aspect-ratio: 1 / 1; object-fit: contain;" />
          <div class="mt-4">
            <h2 class="text-2xl font-semibold text-slate-600 dark:text-navy-100">
              Welcome Back
            </h2>
            <p class="text-slate-400 dark:text-navy-300">
              Please login to continue
            </p>
          </div>
        </div>
        <form method="POST" action="{{ route('admin.login') }}">
          @csrf
          <div class="mt-10">
            <!-- <label class="relative flex">
                    <input
                        class="form-input peer w-full rounded-lg bg-slate-150 px-3 py-2 pl-9 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring-3 dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900"
                        placeholder="Username"
                        type="text"
                    />
                    <span
                        class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent"
                    >
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="size-5 transition-colors duration-200"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                        />
                        </svg>
                    </span>
                    </label>
                    <label class="relative mt-4 flex">
                    <input
                        class="form-input peer w-full rounded-lg bg-slate-150 px-3 py-2 pl-9 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring-3 dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900"
                        placeholder="Password"
                        type="password"
                    />
                    <span
                        class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent"
                    >
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="size-5 transition-colors duration-200"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                        />
                        </svg>
                    </span>
                    </label> -->

            <div>
              @if ($errors->has('email'))
                <div class="w-full flex justify-center">
                  <div
                    class="mb-4 px-4 py-3 rounded-md bg-red-500 text-white text-sm shadow-lg font-semibold flex items-center gap-2 animate-fade-in"
                    style="z-index: 100;" role="alert">
                    <svg class="w-5 h-5 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.364 5.636a9 9 0 11-12.728 0m12.728 0A9 9 0 015.636 18.364m12.728-12.728L5.636 18.364" />
                    </svg>
                    Email atau password salah
                  </div>
                </div>
              @endif
              <label class="relative flex">
                <x-text-input id="email"
                  class="form-input peer w-full rounded-lg bg-slate-150 px-3 py-2 pl-9 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring-3 dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900"
                  type="text" name="email" :value="old('email')" required autofocus autocomplete="username"
                  placeholder="Username" />
                <span
                  class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                  <svg xmlns="http://www.w3.org/2000/svg" class="size-5 transition-colors duration-200" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                </span>
                <!-- <x-input-error :messages="$errors->get('email')" class="mt-2" /> -->
              </label>
            </div>

            <div>
              <label class="relative flex mt-4 flex">

                <x-text-input id="password"
                  class="form-input peer w-full rounded-lg bg-slate-150 px-3 py-2 pl-9 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring-3 dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900"
                  type="password" name="password" required autocomplete="current-password" placeholder="Password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                <span
                  class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                  <svg xmlns="http://www.w3.org/2000/svg" class="size-5 transition-colors duration-200" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                </span>
              </label>

            </div>

            <!-- <div class="mt-4 flex items-center justify-between space-x-2">
                    <label class="inline-flex items-center space-x-2">
                        <input
                        class="form-checkbox is-outline size-5 rounded-sm border-slate-400/70 bg-slate-100 before:bg-primary checked:border-primary hover:border-primary focus:border-primary dark:border-navy-500 dark:bg-navy-900 dark:before:bg-accent dark:checked:border-accent dark:hover:border-accent dark:focus:border-accent"
                        type="checkbox"
                        />
                        <span class="line-clamp-1">Remember me</span>
                    </label>
                    <a
                        href="#"
                        class="text-xs text-slate-400 transition-colors line-clamp-1 hover:text-slate-800 focus:text-slate-800 dark:text-navy-300 dark:hover:text-navy-100 dark:focus:text-navy-100"
                        >Forgot Password?</a
                    >
                    </div> -->


            <!-- <div class="block mt-4">
              <label for="remember_me" class="inline-flex items-center">
                  <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                  <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
              </label>
          </div>
            <div class="mt-4 flex items-center justify-between space-x-2">
              <label class="inline-flex items-center space-x-2">
                <input
                  class="form-checkbox is-outline size-5 rounded-sm border-slate-400/70 bg-slate-100 before:bg-primary checked:border-primary hover:border-primary focus:border-primary dark:border-navy-500 dark:bg-navy-900 dark:before:bg-accent dark:checked:border-accent dark:hover:bg-accent dark:focus:border-accent"
                  type="checkbox"
                />
                <span class="line-clamp-1">Remember me</span>
              </label>
              <a
                href="#"
                class="text-xs text-slate-400 transition-colors line-clamp-1 hover:text-slate-800 focus:text-slate-800 dark:text-navy-300 dark:hover:text-navy-100 dark:focus:text-navy-100"
                >Forgot Password?</a
              >
            </div> -->

            <button
              class="btn mt-10 h-10 w-full bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
              Sign In
            </button>

            <div class="mt-4 text-center text-xs-plus">
              <p class="line-clamp-1">
                <span>Dont have Account?</span>

                <a class="text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                  href="{{ route('admin.register') }}">Request account</a>
              </p>
            </div>
            <!-- <div class="mt-4 text-center text-xs-plus">
                    <p class="line-clamp-1">
                        <span>Dont have Account?</span>

                        <a
                        class="text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                        href="pages-singup-2.html"
                        >Create account</a
                        >
                    </p>
                    </div>
                    <div class="my-7 flex items-center space-x-3">
                    <div class="h-px flex-1 bg-slate-200 dark:bg-navy-500"></div>
                    <p>OR</p>
                    <div class="h-px flex-1 bg-slate-200 dark:bg-navy-500"></div>
                    </div>
                    <div class="flex space-x-4">
                    <button
                        class="btn w-full space-x-3 border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90"
                    >
                        <img
                        class="size-5.5"
                        src="images/logos/google.svg"
                        alt="logo"
                        />
                        <span>Google</span>
                    </button>
                    <button
                        class="btn w-full space-x-3 border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90"
                    >
                        <img
                        class="size-5.5"
                        src="images/logos/github.svg"
                        alt="logo"
                        />
                        <span>Github</span>
                    </button>
                    </div> -->
          </div>
        </form>
      </div>
      <div class="my-5 flex justify-center text-xs text-slate-400 dark:text-navy-300">
        <a href="#">- Copyright © 2025 Rolin -</a>
        <!-- <div class="mx-3 my-1 w-px bg-slate-200 dark:bg-navy-500"></div>
          <a href="#">Term of service</a> -->
      </div>
    </main>
  </div>

  <!-- 
        This is a place for Alpine.js Teleport feature 
        @see https://alpinejs.dev/directives/teleport
      -->
  <div id="x-teleport-target"></div>
  <script>
    window.addEventListener("DOMContentLoaded", () => Alpine.start());
  </script>
</body>

</html>