@extends('layouts.app')

@section('content')
<div class="py-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Admin Dashboard</h2>
    <p class="text-sm text-gray-500 mb-6">Welcome back, {{ Auth::user()->name }}</p>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded shadow text-center">
            <div class="text-gray-500">Total Sales</div>
            <div class="text-xl font-semibold">10</div>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <div class="text-gray-500">Transactions</div>
            <div class="text-xl font-semibold">120</div>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <div class="text-gray-500">Stores</div>
            <div class="text-xl font-semibold">35</div>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <div class="text-gray-500">Total Penghasilan</div>
            <div class="text-xl font-semibold">Rp 1.500.000</div>
        </div>
    </div>

    <div class="bg-white p-4 rounded shadow mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Overall Sales Performance</h3>
        <canvas id="salesChart"></canvas>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Transactions</h3>
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="text-gray-500">
                        <th>Date</th>
                        <th>Store</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t">
                        <td>2025-06-01</td>
                        <td>Toko A</td>
                        <td>Rp 500.000</td>
                        <td><span class="text-green-600 font-medium">Paid</span></td>
                    </tr>
                    <tr class="border-t">
                        <td>2025-06-02</td>
                        <td>Toko B</td>
                        <td>Rp 250.000</td>
                        <td><span class="text-red-600 font-medium">Unpaid</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Performing Sales</h3>
            <ul class="text-sm text-gray-700">
                <li class="border-b py-2">Budi - Rp 3.000.000</li>
                <li class="border-b py-2">Siti - Rp 2.500.000</li>
                <li class="py-2">Andi - Rp 2.000.000</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['2025-06-01', '2025-06-02', '2025-06-03'],
            datasets: [{
                label: 'Sales (Rp)',
                data: [500000, 250000, 750000],
                backgroundColor: 'rgba(59, 130, 246, 0.6)',
                borderRadius: 6,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
