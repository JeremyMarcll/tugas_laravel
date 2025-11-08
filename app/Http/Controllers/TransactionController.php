<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // List transaksi dengan filter, search, pagination, dan statistik
    public function index(Request $request)
    {
        $query = Transaction::where('user_id', Auth::id());

        if ($s = $request->query('s')) {
            $query->where(function($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('notes', 'like', "%{$s}%")
                  ->orWhere('category', 'like', "%{$s}%");
            });
        }

        if ($type = $request->query('type')) {
            if (in_array($type, ['income','expense'])) {
                $query->where('type', $type);
            }
        }

        if ($cat = $request->query('category')) {
            $query->where('category', $cat);
        }

        $transactions = $query->orderBy('occurred_at','desc')->paginate(20)->withQueryString();

        $stats = Transaction::where('user_id', Auth::id())
            ->selectRaw("type, SUM(amount) as total")
            ->groupBy('type')
            ->pluck('total','type')
            ->toArray();

        $monthly = Transaction::where('user_id', Auth::id())
            ->selectRaw("
                DATE_TRUNC('month', occurred_at) as month,
                SUM(CASE WHEN type='income' THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type='expense' THEN amount ELSE 0 END) as expense
            ")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('transactions.index', compact('transactions', 'stats', 'monthly'));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'type' => ['required', Rule::in(['income','expense'])],
            'amount' => 'required|numeric',
            'occurred_at' => 'nullable|date',
            'category' => 'nullable|string|max:100',
            'bukti' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        // Tanggal otomatis jika kosong
        $data['occurred_at'] = $data['occurred_at'] ?? now();

        // Upload bukti jika ada
        if ($request->hasFile('bukti')) {
            $data['bukti'] = $request->file('bukti')->store('transactions', 'public');
        }

        $data['user_id'] = Auth::id();

        Transaction::create($data);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'type' => ['required', Rule::in(['income','expense'])],
            'amount' => 'required|numeric',
            'occurred_at' => 'nullable|date',
            'category' => 'nullable|string|max:100',
            'bukti' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        // Tanggal otomatis jika kosong
        $data['occurred_at'] = $data['occurred_at'] ?? ($transaction->occurred_at ?? now());

        // Hapus file lama jika ada dan upload baru
        if ($request->hasFile('bukti')) {
            if ($transaction->bukti) {
                Storage::disk('public')->delete($transaction->bukti);
            }
            $data['bukti'] = $request->file('bukti')->store('transactions', 'public');
        }

        $transaction->update($data);

        return redirect()->route('transactions.index')->with('success', 'Transaksi diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        // Hapus file bukti jika ada
        if ($transaction->bukti) {
            Storage::disk('public')->delete($transaction->bukti);
        }

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaksi dihapus.');
    }

    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        return view('transactions.show', compact('transaction'));
    }
}
