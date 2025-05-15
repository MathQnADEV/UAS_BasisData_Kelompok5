@extends('layouts.app')

@section('title', 'Halaman Pemesanan')

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
                onclick="tambahDataPemesanan.showModal()">Tambah data</button>
        </div>
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 mb-10">
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Pelanggan</th>
                        <th>Pegawai</th>
                        <th>Tanggal Pemesanan</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($viewOrders as $index => $viewOrder)
                        <tr>
                            <th>{{ $index + 1 }}</th>
                            <td>{{ $viewOrder->customer_name }}</td>
                            <td>{{ $viewOrder->employee_name }}</td>
                            <td>{{ $viewOrder->order_date }}</td>
                            <td>
                                {!! nl2br(e($viewOrder->items)) !!}
                            </td>
                            <td>
                                {{ formatRupiah($viewOrder->total) }}
                            </td>
                            <td>{{ $viewOrder->payment_method }}</td>
                            <td>
                                <span class="badge badge-success">{{ $viewOrder->status }}</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-success"
                                    onclick='fetchStruk({{ $viewOrder->order_id }})'>Detail</button>
                            </td>
                            <dialog id="modalStruk" class="modal">
                                <div class="modal-box w-11/12 max-w-3xl">
                                    <h3 class="font-bold text-lg mb-4">Detail Pemesanan</h3>
                                    <div id="detailStrukIsi" class="text-sm"></div>
                                    <div class="mt-4 flex justify-end">
                                        <button class="btn btn-primary"
                                            onclick="document.getElementById('modalStruk').close()">Tutup</button>
                                    </div>
                                </div>
                            </dialog>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Start Modal Tambah Data Pemesanan -->
    <dialog id="tambahDataPemesanan" class="modal">
        <div class="modal-box w-11/12 max-w-5xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" id="btnCloseModalTambah">✕</button>
            </form>
            <h3 class="text-lg font-bold">Tambah Data Pemesanan</h3>
            <form method="post" action="{{ route('orders.store') }}">
                @csrf
                <fieldset class="fieldset mt-5">
                    <legend class="fieldset-legend">Nama Pelanggan</legend>
                    <select class="select" name="customer_id">
                        <option disabled selected>Pilih Pelanggan</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->customer_id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <legend class="fieldset-legend mt-2">Nama Pegawai</legend>
                    <select class="select" name="employee_id">
                        <option disabled selected>Pilih Pegawai</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->employee_id }}">{{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <legend class="fieldset-legend mt-2">Tanggal Pemesanan</legend>
                    <input type="date" class="input" name="order_date" value={{ date('Y-m-d') }} />
                    @error('order_date')
                        <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <legend class="fieldset-legend mt-2">Item Menu</legend>
                    <div id="items-container">
                        <div class="item-row mb-2 flex items-center gap-2">
                            <select class="input kategori-select" data-index="0">
                                <option selected disabled>Pilih Kategori</option>
                                @foreach ($kategoriList as $kategori)
                                    <option value="{{ $kategori }}">{{ $kategori }}</option>
                                @endforeach
                            </select>
                            <select name="items[0][item_id]" class="input menu-item-select">
                                <option selected disabled>Pilih Menu</option>
                            </select>
                            <input type="number" name="items[0][qty]" class="input w-20" value="1" min="1">
                            <input type="text" name="items[0][special_req]" class="input"
                                placeholder="Catatan opsional">
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary mt-5" onclick="tambahItem()">+ Item</button>
                    <legend class="fieldset-legend mt-2">Metode Pembayaran</legend>
                    <select name="payment_method" class="input">
                        <option value="cash">Cash</option>
                        <option value="qris">QRIS</option>
                    </select>
                    @error('payment_method')
                        <p class="label text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </fieldset>
                <button type="button" class="btn btn-primary mt-5" onclick="tampilkanRingkasan()">Selesai</button>
            </form>
        </div>
    </dialog>
    <!-- End Modal Tambah Data Pemesanan -->
    {{-- Start Modal Struk Konfirmasi --}}
    <dialog id="modalRingkasan" class="modal">
        <div class="modal-box w-11/12 max-w-3xl">
            <h3 class="font-bold text-lg mb-4">Ringkasan Pemesanan</h3>
            <div id="ringkasanIsi"></div>
            <div class="mt-4 flex justify-end gap-2">
                <button class="btn btn-error" onclick="document.getElementById('modalRingkasan').close()">Batal</button>
                <button class="btn btn-primary" onclick="submitForm()">Konfirmasi & Kirim</button>
            </div>
        </div>
    </dialog>
    {{-- End Modal Struk Konfirmasi --}}
    <script>
        let itemIndex = 1;

        function buatDropdownKategori(index) {
            return `
                <select class="input kategori-select" data-index="${index}">
                    <option selected disabled>Pilih Kategori</option>
                    @foreach ($kategoriList as $kategori)
                        <option value="{{ $kategori }}">{{ $kategori }}</option>
                    @endforeach
                </select>
            `;
        }

        function buatDropdownMenuItem(index) {
            return `
                <select name="items[${index}][item_id]" class="input menu-item-select">
                    <option selected disabled>Pilih Menu</option>
                </select>
            `;
        }

        // start tambah item dari modal tambah data
        function tambahItem() {
            const container = document.getElementById('items-container');
            const div = document.createElement('div');
            div.classList.add('item-row', 'mb-2');
            div.innerHTML = `
                <div class="flex items-center gap-2">
                    ${buatDropdownKategori(itemIndex)}
                    ${buatDropdownMenuItem(itemIndex)}
                    <input type="number" name="items[${itemIndex}][qty]" class="input w-20" value="1" min="1">
                    <input type="text" name="items[${itemIndex}][special_req]" class="input" placeholder="Catatan opsional">
                    <button type="button" class="btn btn-error btn-sm" onclick="hapusItem(this)">Hapus</button>
                </div>
            `;
            container.appendChild(div);
            itemIndex++;
        }
        // end tambah item dari modal tambah data

        // start button hapusitem
        function hapusItem(button) {
            const row = button.closest('.item-row');
            row.remove();
        }
        // end button hapusitem

        // start fungsi clear modal tambah data
        document.getElementById('btnCloseModalTambah').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            container.innerHTML = `
                <div class="item-row mb-2">
                    <div class="flex items-center gap-2">
                        ${buatDropdownKategori(0)}
                        ${buatDropdownMenuItem(0)}
                        <input type="number" name="items[0][qty]" class="input w-20" value="1" min="1">
                        <input type="text" name="items[0][special_req]" class="input" placeholder="Catatan opsional">
                        <button type="button" class="btn btn-error btn-sm" onclick="hapusItem(this)">Hapus</button>
                    </div>
                </div>
            `;
            itemIndex = 1;
        });
        // end fungsi clear modal tambah

        // start memanggil procedur kategori untuk mencari menu
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('kategori-select')) {
                const kategori = e.target.value;
                const index = e.target.getAttribute('data-index');

                fetch('/get-menu-by-category', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            kategori: kategori
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        const menuSelect = e.target.parentElement.querySelector('.menu-item-select');
                        menuSelect.innerHTML = '<option selected disabled>Pilih Menu</option>';
                        data.forEach(menu => {
                            if (menu.name == undefined) {
                                menuSelect.innerHTML += `<option disabled>Tidak Tersedia</option>`;
                            } else {
                                menuSelect.innerHTML +=
                                    `<option value="${menu.item_id}">${menu.name} (Rp${menu.price})</option>`;
                            }
                        });
                    })
                    .catch(err => {
                        console.error('Gagal mengambil data:', err);
                    });
            }
        });
        // end memanggil procedur kategori untuk mencari menu

        // start fungsi tampilkan ringkasan
        function tampilkanRingkasan() {
            const container = document.getElementById('items-container');
            const items = container.querySelectorAll('.item-row');

            const menuItems = @json($menuItems);

            let ringkasanHTML = '<ul class="mb-4">';
            let total = 0;

            items.forEach((row, i) => {
                const itemSelect = row.querySelector('select.menu-item-select');
                const qtyInput = row.querySelector('input[name^="items"][name$="[qty]"]');
                const catatanInput = row.querySelector('input[name^="items"][name$="[special_req]"]');

                const itemId = parseInt(itemSelect.value);
                const qty = parseInt(qtyInput.value);
                const catatan = catatanInput.value || '-';

                const menu = menuItems.find(m => m.item_id === itemId);
                const subtotal = menu.price * qty;
                total += subtotal;

                ringkasanHTML += `
                <li class="mb-2">
                    <strong>${menu.name}</strong> x${qty} = ${formatRupiah(subtotal)} <br>
                    <em>Catatan: ${catatan}</em>
                </li>
            `;
            });

            ringkasanHTML += `</ul><p class="font-bold text-right">Total:  Rp${total.toLocaleString()}</p>`;
            document.getElementById('ringkasanIsi').innerHTML = ringkasanHTML;

            document.getElementById('modalRingkasan').showModal();
        }
        // end fungsi tampilkan ringkasan

        // start fungsi submit form modal tambah data setelah tampilkan ringkasan
        function submitForm() {
            document.querySelector('form[action="{{ route('orders.store') }}"]').submit();
        }
        // end fungsi submit form modal tambah data setelah tampilkan ringkasan

        // start fungsi formater
        function formatRupiah(angka) {
            return 'Rp' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
        // end fungsi formater

        function fetchStruk(orderId) {
            fetch(`http://127.0.0.1:8000/orders/${orderId}`)
                .then(res => {
                    return res.json();
                })
                .then(order => {
                    if (Array.isArray(order)) {
                        order = order[0]; // Fix di sini
                    }
                    lihatStruk(order);
                })
                .catch(err => {
                    alert('Gagal memuat data struk.');
                    console.error("Fetch error:", err);
                });
        }

        function lihatStruk(order) {
            const menuItems = @json($menuItems);
            let detailHTML = `
                                        <ul class="list bg-base-100 rounded-box shadow-md">
                                            <li class="p-4 py-1 text-bold text-lg font-bold tracking-wide">Pelanggan:
                                                <span class="font-normal">
                                                    ${order.customer.name}
                                                </span>
                                            </li>
                                            <li class="p-4 py-1 font-bold text-lg tracking-wide">Pegawai:
                                                <span class="font-normal">
                                                    ${order.employee.name}
                                                </span>
                                            </li>
                                            <li class="p-4 py-1 font-bold text-lg tracking-wide">Tanggal:
                                                <span class="font-normal">
                                                    ${order.order_date}
                                                </span>
                                            </li>
                                            <li class="p-4 py-1 font-bold text-lg tracking-wide">Metode Pembayaran:
                                                <span class="font-normal">
                                                    ${order.payment_method}
                                                </span>
                                            </li>
                                            <li class="p-4 py-1 font-bold text-lg tracking-wide">Status:
                                                <span class="font-normal">
                                                    ${order.status}
                                                </span>
                                            </li>
                                            <li class="p-4 py-1 font-bold text-lg tracking-wide">Daftar Item:</li>
                                    `;
            let total = 0;
            let indexNum = 0;
            order.order_items.forEach(item => {
                const menu = menuItems.find(m => m.item_id === item.menu_item.item_id);
                const subtotal = item.qty * item.price_at_order;
                indexNum++;
                total += subtotal;
                detailHTML += `
                                            <li class="list-row">
                                                <div class="text-4xl font-thin opacity-30 tabular-nums">${indexNum}</div>
                                                <div class="list-col-grow">
                                                <div>${menu.name} x${item.qty} = ${formatRupiah(subtotal)}</div>
                                                <div class="text-xs uppercase font-semibold opacity-60">Catatan: ${item.special_req || '-'}</div>
                                                </div>
                                            </li>
                                        `;
            });

            detailHTML += `</ul><p class="font-bold text-right mt-3">Total: ${formatRupiah(total)}</p>`;

            document.getElementById('detailStrukIsi').innerHTML = detailHTML;
            document.getElementById('modalStruk').showModal();
        }

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
