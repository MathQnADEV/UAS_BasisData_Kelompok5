@extends('layouts.app')

@section('title', 'Halaman Bahan Baku')

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
                onclick="tambahDataIngredients.showModal()">Tambah data</button>
        </div>
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 mb-10">
            <table class="table">
                <!-- head -->
                <thead>
                    <tr>
                        <th></th>
                        <th>Nama Bahan</th>
                        <th>Unit</th>
                        <th>Stok Bahan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ingredients as $index => $ingredient)
                        <tr>
                            <th>{{ $index + 1 }}</th>
                            <td>{{ $ingredient->name }}</td>
                            <td>{{ $ingredient->unit }}</td>
                            <td>{{ $ingredient->current_stock }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning"
                                    onclick="document.getElementById('editModal-{{ $ingredient->ingredient_id }}').showModal()">Edit</button>
                                <!-- Start Modal Edit Data Ingredients -->
                                <dialog id="editModal-{{ $ingredient->ingredient_id }}" class="modal">
                                    <div class="modal-box">
                                        <form method="dialog">
                                            <button
                                                class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                        </form>
                                        <h3 class="text-lg font-bold">Edit Data Ingredients</h3>
                                        <form method="post"
                                            action="{{ route('ingredients.update', $ingredient->ingredient_id) }}">
                                            @csrf
                                            @method('put')
                                            <fieldset class="fieldset mt-5">
                                                <legend class="fieldset-legend">Nama Bahan Baku</legend>
                                                <input type="text" class="input" name="name"
                                                    value="{{ $ingredient->name }}" placeholder="Ketik Disini" />
                                                @error('name')
                                                    <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                                <legend class="fieldset-legend">Nama Unit</legend>
                                                <input type="text" class="input" name="unit"
                                                    value="{{ $ingredient->unit }}" placeholder="Ketik Disini" />
                                                @error('unit')
                                                    <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                                <legend class="fieldset-legend">Stok Bahan Baku</legend>
                                                <input type="text" class="input" name="current_stock"
                                                    value="{{ $ingredient->current_stock }}" placeholder="Ketik Disini" />
                                                @error('current_stock')
                                                    <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </fieldset>
                                            <button class="btn btn-primary mt-5">Update</button>
                                        </form>
                                    </div>
                                </dialog>
                                <!-- End Modal Edit Data Ingredients -->
                                <button class="btn btn-sm btn-error"
                                    onclick="document.getElementById('deleteModal-{{ $ingredient->ingredient_id }}').showModal()">Hapus</button>
                                <!-- Start Modal Delete Data Ingredients -->
                                <dialog id="deleteModal-{{ $ingredient->ingredient_id }}" class="modal">
                                    <div class="modal-box">
                                        <form method="dialog">
                                            <button
                                                class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                        </form>
                                        <h3 class="text-lg font-bold">Yakin delete bahan baku {{ $ingredient->name }} ?
                                        </h3>
                                        <form method="post"
                                            action="{{ route('ingredients.destroy', $ingredient->ingredient_id) }}">
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
    <!-- Start Modal Tambah Data Ingredients -->
    <dialog id="tambahDataIngredients" class="modal">
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="text-lg font-bold">Tambah Data Bahan Pokok</h3>
            <form method="post" action="{{ route('ingredients.store') }}">
                <fieldset class="fieldset mt-5">
                    <legend class="fieldset-legend">Nama Bahan Pokok</legend>
                    <input type="text" class="input" name="name" placeholder="Ketik Disini" />
                    @error('name')
                        <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <legend class="fieldset-legend">Nama per Unit</legend>
                    <input type="text" class="input" name="unit" placeholder="Misal 'kg'" />
                    @error('unit')
                        <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <legend class="fieldset-legend">Stok Bahan Baku</legend>
                    <input type="number" class="input" name="current_stock" placeholder="Ketik Disini" />
                    @error('current_stock')
                        <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </fieldset>
                @csrf
                <button class="btn btn-primary mt-5">Simpan</button>
            </form>
        </div>
    </dialog>
    <!-- End Modal Tambah Data Ingredients -->
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
