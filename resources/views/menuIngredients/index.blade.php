@extends('layouts.app')

@section('title', 'Halaman Menu Resep')

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
                onclick="tambahDataResep.showModal()">Tambah data</button>
        </div>
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 mb-10">
            <table class="table">
                <!-- head -->
                <thead>
                    <tr>
                        <th></th>
                        <th>Nama Menu</th>
                        <th>Bahan Masakan</th>
                        <th>Jumlah 'Qty'</th>
                        <th>Satuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($menuIngredients as $index => $item)
                        <tr>
                            <th>{{ $index + 1 }}</th>
                            <td>{{ $item->menuItem->name }}</td>
                            <td>{{ $item->ingredient->name }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $item->ingredient->unit }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning"
                                    onclick="document.getElementById('editModal-{{ $item->menu_ingredient_id }}').showModal()">Edit</button>
                                <!-- Start Modal Edit Data resep -->
                                <dialog id="editModal-{{ $item->menu_ingredient_id }}" class="modal">
                                    <div class="modal-box">
                                        <form method="dialog">
                                            <button
                                                class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                        </form>
                                        <h3 class="text-lg font-bold">Edit Data Resep</h3>
                                        <form method="post"
                                            action="{{ route('menuIngredient.update', $item->menu_ingredient_id) }}">
                                            @csrf
                                            @method('put')
                                            <fieldset class="fieldset mt-5">
                                                <legend class="fieldset-legend">Nama Menu</legend>
                                                <select class="select" name="item_id">
                                                    @foreach ($menus as $menu)
                                                        <option value="{{ $menu->item_id }}"
                                                            {{ $item->item_id == $menu->item_id ? 'selected' : '' }}>
                                                            {{ $menu->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('item_id')
                                                    <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                                <legend class="fieldset-legend mt-2">Nama Bahan</legend>
                                                <select class="select" name="ingredient_id">
                                                    @foreach ($ingredients as $ingredient)
                                                        <option value="{{ $ingredient->ingredient_id }}"
                                                            {{ $item->ingredient_id == $ingredient->ingredient_id ? 'selected' : '' }}>
                                                            {{ $ingredient->name }}
                                                            ({{ $ingredient->unit }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('ingredient_id')
                                                    <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                                <legend class="fieldset-legend mt-2">Jumlah (Qty)</legend>
                                                <input type="number" step="0.01" class="input" name="qty"
                                                    value="{{ $item->qty }}" />
                                                @error('qty')
                                                    <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </fieldset>
                                            <button class="btn btn-primary mt-5">Update</button>
                                        </form>
                                    </div>
                                </dialog>
                                <!-- End Modal Edit Data resep -->
                                <button class="btn btn-sm btn-error"
                                    onclick="document.getElementById('deleteModal-{{ $item->menu_ingredient_id }}').showModal()">Hapus</button>
                                <!-- Start Modal Delete Data Ingredients -->
                                <dialog id="deleteModal-{{ $item->menu_ingredient_id }}" class="modal">
                                    <div class="modal-box">
                                        <form method="dialog">
                                            <button
                                                class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                        </form>
                                        <h3 class="text-lg font-bold">Yakin hapus resep {{ $item->menuItem->name }} dengan
                                            bahan {{ $item->ingredient->name }} ?</h3>
                                        <form method="post"
                                            action="{{ route('menuIngredient.destroy', $item->menu_ingredient_id) }}">
                                            @method('delete')
                                            @csrf
                                            <button class="btn btn-error mt-5">Hapus</button>
                                        </form>
                                    </div>
                                </dialog>
                                <!-- End Modal Delete Data Ingredients -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Start Modal Tambah Data Resep -->
    <dialog id="tambahDataResep" class="modal">
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="text-lg font-bold">Tambah Data Resep</h3>
            <form method="post" action="{{ route('menuIngredient.store') }}">
                @csrf
                <fieldset class="fieldset mt-5">
                    <legend class="fieldset-legend">Nama Menu</legend>
                    <select class="select" name="item_id">
                        <option disabled selected>Pilih Menu</option>
                        @foreach ($menus as $menu)
                            <option value="{{ $menu->item_id }}">{{ $menu->name }}</option>
                        @endforeach
                    </select>
                    @error('item_id')
                        <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <legend class="fieldset-legend mt-2">Nama Bahan</legend>
                    <select class="select" name="ingredient_id">
                        <option disabled selected>Pilih Bahan</option>
                        @foreach ($ingredients as $ingredient)
                            <option value="{{ $ingredient->ingredient_id }}">{{ $ingredient->name }}
                                ({{ $ingredient->unit }})
                            </option>
                        @endforeach
                    </select>
                    @error('ingredient_id')
                        <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <legend class="fieldset-legend mt-2">Jumlah (Qty)</legend>
                    <input type="number" step="0.01" class="input" name="qty" placeholder="Ketik jumlah disini" />
                    @error('qty')
                        <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </fieldset>
                <button class="btn btn-primary mt-5">Simpan</button>
            </form>
        </div>
    </dialog>
    <!-- End Modal Tambah Data Resep -->
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
