@extends('layouts.app')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .page-header h2 {
        font-weight: bold;
        margin: 0;
    }

    .btn-add {
        background-color: #007bff;
        color: white;
        padding: 8px 14px;
        border: none;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }

    .btn-add:hover {
        background-color: #0056b3;
    }

    .store-card {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 15px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .store-card strong {
        font-size: 1.1rem;
    }

    .store-card .actions {
        margin-top: 10px;
    }

    .store-card .actions a {
        margin-right: 12px;
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
    }

    .store-card .actions a:hover {
        text-decoration: underline;
    }

    .store-card .actions form {
        display: inline;
    }

    .store-card .actions button {
        background: none;
        border: none;
        color: red;
        cursor: pointer;
        font-weight: 500;
    }

    .store-card .actions button:hover {
        text-decoration: underline;
    }
</style>

<div class="page-header">
    <h2>Daftar Toko</h2>
    <a href="{{ route('stores.create') }}" class="btn-add">+ Tambah Toko</a>
</div>

@forelse ($stores as $store)
    <div class="store-card">
        <strong>{{ $store->name }}</strong><br>
        {{ $store->address }}

        <div class="actions">
            <a href="{{ route('stores.edit', $store->id) }}">Edit</a>
            <form method="POST" action="{{ route('stores.destroy', $store->id) }}" onsubmit="return confirm('Yakin ingin menghapus toko ini?')">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        </div>
    </div>
@empty
    <p>Tidak ada data toko tersedia.</p>
@endforelse
@endsection
