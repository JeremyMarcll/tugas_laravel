<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // relasi ke users
            $table->string('title');
            $table->text('notes')->nullable(); // catatan tambahan
            $table->enum('type', ['income', 'expense']); // tipe transaksi
            $table->decimal('amount', 15, 2); // jumlah nominal
            $table->date('occurred_at')->nullable(); // tanggal transaksi
            $table->string('bukti')->nullable(); // path bukti pembayaran
            $table->string('category')->nullable(); // kategori transaksi
            $table->timestamps(); // created_at dan updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
