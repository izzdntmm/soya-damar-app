<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class SalesManagementController extends Controller
{
    public function index()
    {
        $sales = User::role('sales')->get();
        return view('admin.sales.index', compact('sales'));
    }

    public function edit(User $user)
    {
        return view('admin.sales.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return redirect()->route('admin.sales.index')->with('success', 'Sales updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Sales deleted successfully');
    }
}
