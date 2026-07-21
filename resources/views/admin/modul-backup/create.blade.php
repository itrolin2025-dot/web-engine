<x-app-layout>
<div class="p-6 max-w-xl mx-auto space-y-4">
<h2 class="text-xl font-semibold">Create Role</h2>
<form method="POST" action="{{ route('modul.store') }}">
@csrf
<div>
<label>Kode</label>
<input type="text" name="kode" class="w-full border p-2" required>
</div>
<div>
<label>Nama</label>
<input type="text" name="name" class="w-full border p-2" required>
</div>
<div>
<label>Select Access:</label>
    <div class="mt-2">
        <label><input type="checkbox" name="akses[]" value="view"> View</label><br>        
        <label><input type="checkbox" name="akses[]" value="add"> Add</label><br>
        <label><input type="checkbox" name="akses[]" value="edit"> Edit</label><br>
        <label><input type="checkbox" name="akses[]" value="delete"> Delete</label><br>
        <label><input type="checkbox" name="akses[]" value="detail"> Detail</label>
    </div>

<button class="bg-blue-600 text-white px-4 py-2">Simpan</button>
</form>
</div>
</x-app-layout>

