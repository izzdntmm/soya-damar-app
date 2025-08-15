@extends('layouts.app')

@section('content')
<h2>Edit Toko</h2>

<form action="{{ route('stores.update', $store->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Nama Toko:</label><br>
    <input type="text" name="name" value="{{ $store->name }}" required><br><br>

    <label>Alamat:</label><br>
    <textarea name="address" required>{{ $store->address }}</textarea><br><br>

    <button type="submit">Update</button>
</form>
@endsection
