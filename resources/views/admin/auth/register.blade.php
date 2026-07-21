
<!DOCTYPE html>
<html lang="en">
  <head>
    <script type="text/javascript" src="/___vscode_livepreview_injected_script"></script>
    <!-- Meta tags  -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
      name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
    />

    <title>Rolin - Register</title>
    <link rel="icon" type="image/png" href="images/favicon.png" />

    <!-- CSS Assets -->
    <link rel="stylesheet" href="css/app.css" />

    <!-- Javascript Assets -->
    <script src="js/app.js" defer></script>

    <!-- Javascript Third Party Libraries: Forms -->
    <script src="js/libs/forms.js" defer></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />
    <script>
      /**
       * THIS SCRIPT REQUIRED FOR PREVENT FLICKERING IN SOME BROWSERS
       */
      localStorage.getItem("dark-mode") === "dark" &&
        document.documentElement.classList.add("dark");
    </script>
  </head>

  <body class="is-header-blur is-sidebar-open">
    <!-- App preloader-->
    <div
      class="app-preloader fixed z-50 grid h-full w-full place-content-center bg-slate-50 dark:bg-navy-900"
    >
      <div class="app-preloader-inner relative inline-block size-48"></div>
    </div>

    <!-- Page Wrapper -->
    <div
      id="root"
      class="min-h-100vh cloak flex grow bg-slate-50 dark:bg-navy-900"
    >

      <!-- Main Content Wrapper -->
      <main class="main-content w-full flex items-center justify-center min-h-screen" style="margin-top:-2em;">
        <form method="POST" action="{{ route('register') }}" class="w-full">
            @csrf
            <div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6 justify-center">
              <div class="col-span-12 lg:col-span-8">
                <div class="card">
                  <div class="p-4 sm:p-5">
                    <h2 class="text-xl font-medium text-slate-800 dark:text-navy-50 lg:text-2xl text-center pb-5">
                      <img 
                        src="{{ asset('images/app-logo.png') }}" 
                        alt="App Logo"
                        class="mx-auto mb-4"
                        style="width: 5rem; height: 5rem; aspect-ratio: 1 / 1; object-fit: contain;"
                      />
                        Register Your Account
                    </h2>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                      <label class="block">
                        <span>Name </span>
                        <span class="relative mt-1.5 flex">
                          <input
                              class="form-input peer w-full rounded-lg bg-slate-150 px-3 py-2 pl-9 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring-3 dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900"
                              placeholder="Enter name"
                              id="name"
                              type="text"
                              name="name"
                              value="{{ old('name') }}"
                              autocomplete="off"
                              required
                              autofocus
                          />
                          <span
                              class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent"
                          >
                              <i class="fa-regular fa-user text-base"></i>
                          </span>
                        </span>
                        @if($errors->has('name'))
                          <span class="text-red-500 text-xs mt-1 block">{{ $errors->first('name') }}</span>
                        @endif
                      </label>
                      <label class="block">
                        <span>Email Address </span>
                        <span class="relative mt-1.5 flex">
                          <input
                              class="form-input peer w-full rounded-lg bg-slate-150 px-3 py-2 pl-9 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring-3 dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900"
                              placeholder="Enter email address"
                              id="email"
                              type="email"
                              name="email"
                              value="{{ old('email') }}"
                              autocomplete="off"
                              required
                          />
                          <span
                              class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent"
                          >
                              <i class="fa-regular fa-envelope text-base"></i>
                          </span>
                        </span>
                        @if($errors->has('email'))
                          <span class="text-red-500 text-xs mt-1 block">{{ $errors->first('email') }}</span>
                        @endif
                      </label>
                      <label class="block">
                        <span>Password </span>
                        <span class="relative mt-1.5 flex">
                          <input
                              class="form-input peer w-full rounded-lg bg-slate-150 px-3 py-2 pl-9 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring-3 dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900"
                              placeholder="Enter password"
                              id="password"
                              type="password"
                              name="password"
                              required
                              autocomplete="new-password"
                          />
                          <span
                              class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent"
                          >
                              <i class="fa-regular text-base"></i>
                          </span>
                        </span>
                        @if($errors->has('password'))
                          <span class="text-red-500 text-xs mt-1 block">{{ $errors->first('password') }}</span>
                        @endif
                      </label>
                      <label class="block">
                        <span>Confirm Password</span>
                        <span class="relative mt-1.5 flex">
                          <input
                              class="form-input peer w-full rounded-lg bg-slate-150 px-3 py-2 pl-9 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring-3 dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900"
                              placeholder="Enter password again"
                              id="password_confirmation"
                              type="password"
                              name="password_confirmation"
                              required
                              autocomplete="new-password"
                          />
                          <span
                              class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent"
                          >
                              <i class="fa"></i>
                          </span>
                        </span>
                        @if($errors->has('password_confirmation'))
                          <span class="text-red-500 text-xs mt-1 block">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                      </label>
                    </div>
                    <div
                      class="flex flex-col items-center justify-center space-y-4 p-4 sm:px-5 text-center"
                    >
                      <h2
                        class="text-lg font-medium tracking-wide text-slate-700 dark:text-navy-100"
                      >
                      </h2>
                      <div class="flex flex-row items-center justify-center w-full space-x-2">
                        <a href="{{ route('login') }}"
                          class="btn mt-10 h-10 w-full border border-primary bg-transparent font-medium text-primary hover:bg-primary hover:text-white focus:bg-primary focus:text-white active:bg-primary/90 dark:border-accent dark:bg-transparent dark:text-accent dark:hover:bg-accent dark:hover:text-white dark:focus:bg-accent dark:focus:text-white dark:active:bg-accent/90"
                        >
                          {{ __('Cancel') }}
                        </a>
                        <button
                          type="submit"
                          class="btn mt-10 h-10 w-full bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90"
                        >
                          {{ __('Register') }}
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </form>
      </main>
    </div>
  </body>
</html>
