<x-app-layout>
<div class="p-6 max-w-xl mx-auto space-y-4">
<h2 class="text-xl font-semibold">Create Role</h2>
<form method="POST" action="{{ route('modul.update', $modul->id) }}">
    @csrf
    @method('PUT')

    <label>Kode</label>
    <input type="text" name="kode" class="border p-2 w-full mb-4" value="{{ $modul->kode }}">

    <label>Module Name</label>
    <input type="text" name="name" class="border p-2 w-full mb-4" value="{{ $modul->name }}">

    <label>Select Access:</label>
    <div class="mt-2">
        
        <label><input type="checkbox" name="akses[]" value="view"
            {{ in_array('view', $selectedAccess) ? 'checked' : '' }}> View</label><br>

        <label><input type="checkbox" name="akses[]" value="add"
            {{ in_array('add', $selectedAccess) ? 'checked' : '' }}> Add</label><br>

        <label><input type="checkbox" name="akses[]" value="edit"
            {{ in_array('edit', $selectedAccess) ? 'checked' : '' }}> Edit</label><br>

        <label><input type="checkbox" name="akses[]" value="delete"
            {{ in_array('delete', $selectedAccess) ? 'checked' : '' }}> Delete</label><br>
            
        <label><input type="checkbox" name="akses[]" value="detail"
            {{ in_array('detail', $selectedAccess) ? 'checked' : '' }}> Detail</label>
    </div>

    <button class="mt-4 px-4 py-2 bg-blue-500 text-white">Update</button>
</form>
</div>
</x-app-layout>




