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

    // list with search, filter, pagination + statistik
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

        // Pagination 20 data per halaman
        $transactions = $query->orderBy('occurred_at','desc')->paginate(20)->withQueryString();

        // Statistik total income vs expense
        $stats = Transaction::where('user_id', Auth::id())
            ->selectRaw("type, SUM(amount) as total")
            ->groupBy('type')
            ->pluck('total','type')
            ->toArray();

        // Statistik bulanan untuk chart
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
            'cover' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('transactions', 'public');
            $data['cover_path'] = $path;
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
            'cover' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            if ($transaction->cover_path) {
                Storage::disk('public')->delete($transaction->cover_path);
            }
            $path = $request->file('cover')->store('transactions', 'public');
            $data['cover_path'] = $path;
        }

        $transaction->update($data);

        return redirect()->route('transactions.index')->with('success', 'Transaksi diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        if ($transaction->cover_path) {
            Storage::disk('public')->delete($transaction->cover_path);
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
