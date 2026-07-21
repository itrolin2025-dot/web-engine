<x-app-layout>

    @include('components.forms.tittle')

    <form method="POST" action="{{ route($modul . '.store') }}" >
        @csrf
        <div class="card p-4 sm:p-5">
            <p class="text-base font-medium text-slate-700 dark:text-navy-100"></p>
            <div class="col-span-12 lg:col-span-9 flex-1 flex flex-col" style="min-width:0;">
                <div class="card flex flex-col space-y-6 h-full" style="padding:1.5em; height:100%;">
                    
                    {{-- BARIS 1 --}}
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
                        <label class="block space-y-1.5">
                            <span>Name <span style="color:red">*</span></span>
                            <x-input name="name" placeholder="Enter Name" autocomplete="off" required/>
                        </label>
                    </div>

                </div>
            </div>
        </div>

        @include('components.forms.save')

    </form>

</x-app-layout>