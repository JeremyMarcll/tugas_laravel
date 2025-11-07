@extends('layouts.app')

@section('title','Daftar Transaksi')

@section('content')
<div class="card p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Daftar Transaksi</h3>
        <a href="{{ route('transactions.create') }}" class="btn btn-primary btn-sm">+ Tambah Transaksi</a>
    </div>

    <!-- 🔍 Filter & Search -->
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-5">
            <input name="s" value="{{ request('s') }}" class="form-control" placeholder="Cari judul, catatan, atau kategori...">
        </div>
        <div class="col-md-3">
            <select name="type" class="form-select">
                <option value="">Semua Tipe</option>
                <option value="income" {{ request('type')=='income'?'selected':'' }}>Pemasukan</option>
                <option value="expense" {{ request('type')=='expense'?'selected':'' }}>Pengeluaran</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success w-100">Filter</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
    </form>

    <!-- 📊 Chart -->
    <div id="chart" style="height: 350px;" class="mb-4"></div>

    <!-- 📋 Tabel Transaksi -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Judul</th>
                    <th>Tipe</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $index => $t)
                    <tr>
                        <td>{{ $transactions->firstItem() + $index }}</td>
                        <td class="text-start">
                            <strong>{{ $t->title }}</strong>
                            @if($t->cover_path)
                                <div class="mt-1">
                                    <img src="{{ Storage::url($t->cover_path) }}" alt="cover" style="max-height:60px; border-radius:4px;">
                                </div>
                            @endif
                            @if($t->notes)
                                <div class="text-muted small mt-1">{!! Str::limit($t->notes, 120) !!}</div>
                            @endif
                        </td>
                        <td>
                            @if($t->type === 'income')
                                <span class="badge bg-success">Pemasukan</span>
                            @else
                                <span class="badge bg-danger">Pengeluaran</span>
                            @endif
                        </td>
                        <td>{{ $t->category ?? '-' }}</td>
                        <td>Rp {{ number_format($t->amount, 2, ',', '.') }}</td>
                        <td>{{ optional($t->occurred_at)->format('d M Y') ?? '-' }}</td>
                        <td>
                            <a href="{{ route('transactions.show', $t) }}" class="btn btn-sm btn-info text-white">Lihat</a>
                            <a href="{{ route('transactions.edit', $t) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('transactions.destroy', $t) }}" method="POST" style="display:inline" onsubmit="return confirmDelete(this);">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">Tidak ada data transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 📄 Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $transactions->links('pagination::bootstrap-5') }}
    </div>
</div>

<!-- Script Chart & Delete Confirmation -->
<script>
document.addEventListener('DOMContentLoaded', function(){
    const monthly = {!! json_encode($monthly) !!};

    const categories = monthly.map(m => m.month);
    const income = monthly.map(m => parseFloat(m.income));
    const expense = monthly.map(m => parseFloat(m.expense));

    const options = {
        series: [
            { name: 'Pemasukan', data: income },
            { name: 'Pengeluaran', data: expense }
        ],
        chart: {
            type: 'area',
            height: 350,
            stacked: false,
            zoom: { enabled: false }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth' },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                inverseColors: false,
                opacityFrom: 0.5,
                opacityTo: 0.1,
                stops: [0, 100, 100, 100]
            },
        },
        xaxis: {
            categories: categories.map(d => new Date(d).toLocaleDateString('id-ID', { month: 'short', year: 'numeric' })),
            title: { text: 'Bulan' }
        },
        yaxis: {
            title: { text: 'Jumlah (Rp)' },
            labels: {
                formatter: val => 'Rp ' + val.toLocaleString('id-ID')
            }
        },
        tooltip: {
            shared: true,
            y: { formatter: val => 'Rp ' + val.toLocaleString('id-ID') }
        },
        legend: { position: 'top' },
        colors: ['#28a745', '#dc3545'],
        title: {
            text: 'Statistik Transaksi Bulanan',
            align: 'left'
        }
    };

    const chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
});

// SweetAlert delete confirmation
function confirmDelete(form) {
    event.preventDefault();
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: 'Data yang dihapus tidak bisa dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((res) => {
        if (res.isConfirmed) form.submit();
    });
}
</script>
@endsection
