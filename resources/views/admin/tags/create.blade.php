<x-app-layout>
    @include('components.forms.tittle')

    <form method="POST" action="{{ route('admin.' . $modul . '.store') }}">
        @csrf
        <div class="card p-4 sm:p-5">
            <div class="col-span-12 flex-1 flex flex-col" style="min-width:0;">
                <div class="card flex flex-col space-y-6 h-full p-6">

                    {{-- Code (auto-generated, read-only) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="block space-y-1.5">
                            <span>Code</span>
                            <input type="text" value="Auto-generated after save" disabled
                                class="form-input w-full rounded-lg border border-slate-300 bg-slate-100 px-3 py-2 text-slate-400 cursor-not-allowed dark:border-navy-450 dark:bg-navy-600/50 dark:text-navy-300" />
                            <span class="text-xs text-slate-400">Code dibuat otomatis oleh sistem saat data disimpan (contoh: TAG-0001).</span>
                        </label>
                    </div>

                    {{-- Nama & Type --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Nama --}}
                        <label class="block space-y-1.5">
                            <span>Nama <span class="text-red-500">*</span></span>
                            <x-input name="nama" placeholder="Enter Tag Name" autocomplete="off" required value="{{ old('nama') }}" />
                            @error('nama')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        {{-- Type --}}
                        <label class="block space-y-1.5">
                            <span>Type <span class="text-red-500">*</span></span>
                            <select name="type" required class="form-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                <option value="">-- Select Type --</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                </div>
            </div>
        </div>

        @include('components.forms.save')
    </form>
</x-app-layout>
