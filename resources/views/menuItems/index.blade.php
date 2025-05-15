@extends('layouts.app')

@section('title', 'Halaman Menu Utama')

@section('content')
    @if (session('success'))
        <div class="toast toast-top toast-end">
            <div class="alert alert-success">
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="toast toast-top toast-end">
            <div class="alert alert-error">
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif
    <div class="flex flex-col justify-center items-center mt-10 gap-10">
        <div>
            <button class="btn btn-neutral btn-xs sm:btn-sm md:btn-md lg:btn-lg xl:btn-lg"
                onclick="tambahDataMenuItems.showModal()">Tambah data</button>
        </div>
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 mb-10">
            <table class="table">
                <!-- head -->
                <thead>
                    <tr>
                        <th></th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                        <th>ketersediaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($menuItems as $index => $menuItem)
                        <tr>
                            <th>{{ $index + 1 }}</th>
                            <td>{{ $menuItem->name }}</td>
                            <td>{{ $menuItem->category }}</td>
                            <td>{{ formatRupiah($menuItem->price) }}</td>
                            @if ($menuItem->description == null)
                                <td>
                                    <input type="checkbox" class="checkbox pointer-events-none"
                                        id="checkbox-inter-{{ $menuItem->item_id }}" />
                                </td>
                                <script>
                                    document.getElementById("checkbox-inter-{{ $menuItem->item_id }}").indeterminate = true
                                </script>
                            @else
                                <td>{{ $menuItem->description }}</td>
                            @endif
                            <td>
                                <input type="checkbox" class="checkbox checkbox-success pointer-events-none"
                                    {{ $menuItem->is_available ? 'checked' : '' }}>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning"
                                    onclick="document.getElementById('editModal-{{ $menuItem->item_id }}').showModal()">Edit</button>
                                <!-- Start Modal Edit Data MenuItems -->
                                <dialog id="editModal-{{ $menuItem->item_id }}" class="modal">
                                    <div class="modal-box">
                                        <form method="dialog">
                                            <button
                                                class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                        </form>
                                        <h3 class="text-lg font-bold">Edit Data Menu</h3>
                                        <form method="post" action="{{ route('menuItems.update', $menuItem->item_id) }}">
                                            @csrf
                                            @method('put')
                                            <fieldset class="fieldset mt-5">
                                                <legend class="fieldset-legend">Nama Menu</legend>
                                                <input type="text" class="input" name="name"
                                                    value="{{ $menuItem->name }}" placeholder="Ketik Disini" />
                                                @error('name')
                                                    <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                                <legend class="fieldset-legend mt-2">Nama Kategori</legend>
                                                <input type="text" class="input" name="category"
                                                    placeholder="Ketik Disini" value="{{ $menuItem->category }}" />
                                                @error('category')
                                                    <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                                <legend class="fieldset-legend mt-2">Harga Menu</legend>
                                                <input type="number" class="input" name="price"
                                                    placeholder="Ketik Disini" value="{{ $menuItem->price }}" />
                                                @error('price')
                                                    <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                                <legend class="fieldset-legend mt-2">Deskripsi Menu</legend>
                                                <input type="text" class="input" name="description"
                                                    placeholder="Ketik Disini" value="{{ $menuItem->description }}" />
                                                @error('description')
                                                    <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                                {{-- <legend class="fieldset-legend mt-2">Ketersediaan</legend>
                                                <input type="hidden" name="is_available" value="0">
                                                <input type="checkbox" class="toggle toggle-success" name="is_available"
                                                    value="1" {{ $menuItem->is_available ? 'checked' : '' }} />
                                                @error('is_available')
                                                    <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror --}}
                                            </fieldset>
                                            <button class="btn btn-primary mt-5">Update</button>
                                        </form>
                                    </div>
                                </dialog>
                                <!-- End Modal Edit Data MenuItems -->
                                <button class="btn btn-sm btn-error"
                                    onclick="document.getElementById('deleteModal-{{ $menuItem->item_id }}').showModal()">Hapus</button>
                                <!-- Start Modal Delete Data MenuItems -->
                                <dialog id="deleteModal-{{ $menuItem->item_id }}" class="modal">
                                    <div class="modal-box">
                                        <form method="dialog">
                                            <button
                                                class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                        </form>
                                        <h3 class="text-lg font-bold">Yakin hapus menu {{ $menuItem->name }} ?</h3>
                                        <form method="post" action="{{ route('menuItems.destroy', $menuItem->item_id) }}">
                                            @method('delete')
                                            @csrf
                                            <button class="btn btn-error mt-5">Hapus</button>
                                        </form>
                                    </div>
                                </dialog>
                                <!-- End Modal Delete Data MenuItems -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Start Modal Tambah Data MenuItems -->
    <dialog id="tambahDataMenuItems" class="modal">
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="text-lg font-bold">Tambah Data Menu</h3>
            <form method="post" action="{{ route('menuItems.store') }}">
                <fieldset class="fieldset mt-5">
                    <legend class="fieldset-legend">Nama Menu</legend>
                    <input type="text" class="input" name="name" placeholder="Ketik Disini" />
                    @error('name')
                        <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <legend class="fieldset-legend mt-2">Nama Kategori</legend>
                    <input type="text" class="input" name="category" placeholder="Ketik Disini" />
                    @error('category')
                        <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <legend class="fieldset-legend mt-2">Harga Menu</legend>
                    <input type="number" class="input" name="price" placeholder="Ketik Disini" />
                    @error('price')
                        <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <legend class="fieldset-legend mt-2">Deskripsi Menu</legend>
                    <input type="text" class="input" name="description" placeholder="Ketik Disini" />
                    @error('description')
                        <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    {{-- <legend class="fieldset-legend mt-2">Ketersediaan</legend>
                    <input type="hidden" name="is_available" value="0">
                    <input type="checkbox" class="toggle toggle-success" name="is_available" value="1" checked />
                    @error('is_available')
                        <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror --}}
                </fieldset>
                @csrf
                <button class="btn btn-primary mt-5">Simpan</button>
            </form>
        </div>
    </dialog>
    <!-- End Modal Tambah Data MenuItems -->
    <script>
        // start atur waktu toast
        const toasts = document.querySelectorAll('.toast');
        setTimeout(function() {
            toasts.forEach(toast => {
                toast.style.transition = 'opacity 0.5s ease';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500); // Hapus elemen setelah fade out
            });
        }, 2000);
        // end atur waktu toast
    </script>
@endsection
