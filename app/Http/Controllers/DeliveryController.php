<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Notifications\NewDeliverySubmitted;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class DeliveryController extends Controller
{
    public function index()
    {
        $deliveries = auth()->user()->deliveries()->where('is_submitted', false)->with('store')->get();
        return view('deliveries.index', compact('deliveries'));
    }

    public function create()
    {
        $stores = auth()->user()->stores;
        return view('deliveries.create', compact('stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $quantity = $request->quantity;
        $pricePerUnit = 3000;
        $total = $quantity * $pricePerUnit;

        Delivery::create([
            'user_id' => auth()->id(),
            'store_id' => $request->store_id,
            'quantity' => $quantity,
            'total_price' => $total,
            'delivery_date' => now()->toDateString(),
            'is_submitted' => false,
        ]);

        return redirect()->route('deliveries.index')->with('success', 'Setoran berhasil disimpan (belum dikirim).');
    }

    public function edit(Delivery $delivery)
    {
        if ($delivery->user_id !== auth()->id() || $delivery->is_submitted) {
            abort(403);
        }

        $stores = auth()->user()->stores;
        return view('deliveries.edit', compact('delivery', 'stores'));
    }

    public function update(Request $request, Delivery $delivery)
    {
        if ($delivery->user_id !== auth()->id() || $delivery->is_submitted) {
            abort(403);
        }

        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $total = $request->quantity * 3000;

        $delivery->update([
            'store_id' => $request->store_id,
            'quantity' => $request->quantity,
            'total_price' => $total,
        ]);

        return redirect()->route('deliveries.index')->with('success', 'Setoran berhasil diperbarui.');
    }

    public function destroy(Delivery $delivery)
    {
        if ($delivery->user_id !== auth()->id() || $delivery->is_submitted) {
            abort(403);
        }

        $delivery->delete();

        return redirect()->route('deliveries.index')->with('success', 'Setoran dihapus.');
    }

    public function submit()
    {
        $user = auth()->user();

        // Ambil semua delivery yang belum disubmit
        $deliveries = $user->deliveries()->where('is_submitted', false)->get();

        // Jika tidak ada setoran baru
        if ($deliveries->isEmpty()) {
            return redirect()->route('deliveries.index')->with('info', 'Tidak ada setoran yang dikirim.');
        }

        // Update semua delivery
        foreach ($deliveries as $delivery) {
            $delivery->update([
                'is_submitted' => true,
                'delivery_date' => now(), // update tanggal setoran
            ]);
        }

        // Kirim notifikasi ke semua admin
        $admins = User::where('role', 'admin')->get();

        Notification::send($admins, new NewDeliverySubmitted($user, now()->toDateString()));

        return redirect()->route('deliveries.index')->with('success', 'Setoran berhasil dikirim ke admin!');
    }

}
