@extends('layouts.app')

@section('content')
<div class="py-10">
    <div class="max-w-xl mx-auto bg-white rounded-xl shadow-md p-8">
        <h2 class="text-2xl font-bold text-blue-700 mb-6">📝 Tambah Setoran</h2>

        <form action="{{ route('deliveries.store') }}" method="POST" id="formSetoran" class="space-y-6">
            @csrf

            {{-- Pilih Toko --}}
            <div>
                <label for="store_id" class="block text-sm font-medium text-gray-700 mb-1">Toko</label>
                <select name="store_id" id="store_id" required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Toko --</option>
                    @foreach ($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Jumlah Barang --}}
            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Barang</label>
                <input type="number" name="quantity" id="quantity" required
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Total Harga --}}
            <div>
                <label for="total_price" class="block text-sm font-medium text-gray-700 mb-1">Total Harga (Rp)</label>
                <input type="text" id="total_price" name="total_price_view" readonly
                       class="w-full bg-gray-100 border-gray-200 rounded-lg shadow-sm text-gray-700">
            </div>

            {{-- Tombol Simpan --}}
            <div>
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition">
                    Simpan Setoran
                </button>
            </div>
        </form>
    </div>
</div>

{{-- JS Hitung Total --}}
<script>
    const qtyInput = document.getElementById('quantity');
    const totalInput = document.getElementById('total_price');

    qtyInput.addEventListener('input', function () {
        const qty = parseInt(qtyInput.value) || 0;
        const total = qty * 3000;
        totalInput.value = total.toLocaleString('id-ID');
    });
</script>
@endsection

