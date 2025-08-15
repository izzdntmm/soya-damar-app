@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Sales</h2>

    <form method="POST" action="{{ route('admin.sales.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" value="{{ $user->name }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ $user->email }}" class="form-control">
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
