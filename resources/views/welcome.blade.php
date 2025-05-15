@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
    <div class="grid lg:grid-cols-4 md:grid-cols-2 m-10 gap-10">
        <div class="card bg-neutral text-neutral-content">
            <div class="card-body items-center text-center">
                <h2 class="card-title">Pelanggan</h2>
                <p>CRUD tabel Pelanggan</p>
                <div class="card-actions justify-end">
                    <a href="{{ route('customer.index') }}">
                        <button class="btn btn-primary">
                            Lihat Pelanggan
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <div class="card bg-neutral text-neutral-content">
            <div class="card-body items-center text-center">
                <h2 class="card-title">Pegawai</h2>
                <p>CRUD tabel Pegawai</p>
                <div class="card-actions justify-end">
                    <a href="{{ route('employee.index') }}">
                        <button class="btn btn-primary">
                            Lihat Pegawai
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <div class="card bg-neutral text-neutral-content">
            <div class="card-body items-center text-center">
                <h2 class="card-title">Pemesanan</h2>
                <p>CRUD tabel Pemesanan</p>
                <div class="card-actions justify-end">
                    <a href="{{ route('orders.index') }}">
                        <button class="btn btn-primary">
                            Pesan Disini!
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <div class="card bg-neutral text-neutral-content">
            <div class="card-body items-center text-center">
                <h2 class="card-title">List Menu</h2>
                <p>CRUD tabel Menu F&B</p>
                <div class="card-actions justify-end">
                    <a href="{{ route('menuItems.index') }}">
                        <button class="btn btn-primary">
                            Lihat Menu F&B
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <div class="card bg-neutral text-neutral-content">
            <div class="card-body items-center text-center">
                <h2 class="card-title">List Masakan Menu</h2>
                <p>Create Read Update Delete</p>
                <div class="card-actions justify-end">
                    <a href="{{ route('menuIngredient.index') }}">
                        <button class="btn btn-primary">
                            Lihat Resep Menu
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <div class="card bg-neutral text-neutral-content">
            <div class="card-body items-center text-center">
                <h2 class="card-title">List Bahan</h2>
                <p>CRUD tabel Bahan Pokok</p>
                <div class="card-actions justify-end">
                    <a href="{{ route('ingredients.index') }}">
                        <button class="btn btn-primary">
                            Lihat Bahan Pokok
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
