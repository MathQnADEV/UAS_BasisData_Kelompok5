@extends('layouts.app')

@section('title', 'Halaman Pelanggan')

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
                onclick="tambahDataPelanggan.showModal()">Tambah data</button>
        </div>
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 mb-10">
            <table class="table">
                <!-- head -->
                <thead>
                    <tr>
                        <th></th>
                        <th>Nama Pelanggan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $index => $customer)
                        <tr>
                            <th>{{ $index + 1 }}</th>
                            <td>{{ $customer->name }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning"
                                    onclick="document.getElementById('editModal-{{ $customer->customer_id }}').showModal()">Edit</button>
                                <!-- Start Modal Edit Data Pelanggan -->
                                <dialog id="editModal-{{ $customer->customer_id }}" class="modal">
                                    <div class="modal-box">
                                        <form method="dialog">
                                            <button
                                                class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                        </form>
                                        <h3 class="text-lg font-bold">Edit Data Pelanggan</h3>
                                        <form method="post"
                                            action="{{ route('customer.update', $customer->customer_id) }}">
                                            @csrf
                                            @method('put')
                                            <fieldset class="fieldset mt-5">
                                                <legend class="fieldset-legend">Nama Pelanggan</legend>
                                                <input type="text" class="input" name="name"
                                                    value="{{ $customer->name }}" placeholder="Ketik Disini" />
                                                @error('name')
                                                    <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </fieldset>
                                            <button class="btn btn-primary mt-5">Update</button>
                                        </form>
                                    </div>
                                </dialog>
                                <!-- End Modal Edit Data Pelanggan -->
                                <button class="btn btn-sm btn-error"
                                    onclick="document.getElementById('deleteModal-{{ $customer->customer_id }}').showModal()">Hapus</button>
                                <!-- Start Modal Delete Data Pelanggan -->
                                <dialog id="deleteModal-{{ $customer->customer_id }}" class="modal">
                                    <div class="modal-box">
                                        <form method="dialog">
                                            <button
                                                class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                        </form>
                                        <h3 class="text-lg font-bold">Yakin delete pelanggan {{ $customer->name }} ?</h3>
                                        <form method="post"
                                            action="{{ route('customer.destroy', $customer->customer_id) }}">
                                            @method('delete')
                                            @csrf
                                            <button class="btn btn-error mt-5">Hapus</button>
                                        </form>
                                    </div>
                                </dialog>
                                <!-- End Modal Delete Data Pelanggan -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Start Modal Tambah Data Pelanggan -->
    <dialog id="tambahDataPelanggan" class="modal">
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="text-lg font-bold">Tambah Data Pelanggan</h3>
            <form method="post" action="{{ route('customer.store') }}">
                <fieldset class="fieldset mt-5">
                    <legend class="fieldset-legend">Nama Pelanggan</legend>
                    <input type="text" class="input" name="name" placeholder="Ketik Disini" />
                    @error('name')
                        <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </fieldset>
                @csrf
                <button class="btn btn-primary mt-5">Simpan</button>
            </form>
        </div>
    </dialog>
    <!-- End Modal Tambah Data Pelanggan -->

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
