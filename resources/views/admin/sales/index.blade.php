@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Manajemen Sales</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
                <tr>
                    <td>{{ $sale->name }}</td>
                    <td>{{ $sale->email }}</td>
                    <td>
                        <a href="{{ route('admin.sales.edit', $sale) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.sales.destroy', $sale) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus sales ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
