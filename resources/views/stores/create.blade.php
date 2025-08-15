@extends('layouts.app')

@section('content')
<style>
    .form-container {
        background: #fff;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        max-width: 500px;
        margin: 0 auto;
    }

    .form-container h2 {
        font-size: 1.5rem;
        margin-bottom: 20px;
        font-weight: bold;
    }

    label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
    }

    input[type="text"],
    textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        margin-bottom: 16px;
        font-size: 14px;
    }

    textarea {
        resize: vertical;
        min-height: 80px;
    }

    button {
        padding: 10px 18px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #0056b3;
    }
</style>

<div class="form-container">
    <h2>Tambah Toko</h2>

    <form action="{{ route('stores.store') }}" method="POST">
        @csrf

        <label for="name">Nama Toko:</label>
        <input type="text" id="name" name="name" required>

        <label for="address">Alamat:</label>
        <textarea id="address" name="address" required></textarea>

        <button type="submit">Simpan</button>
    </form>
</div>
@endsection
