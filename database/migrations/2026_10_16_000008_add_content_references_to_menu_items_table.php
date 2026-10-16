<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            // Referensi konten spesifik (opsional). Bila kosong, tipe menu
            // mengarah ke halaman indeks fiturnya (PRD 6.2 & 6.6).
            $table->foreignId('news_id')->nullable()->after('department_id')->constrained('news')->nullOnDelete();
            $table->foreignId('announcement_id')->nullable()->after('news_id')->constrained('announcements')->nullOnDelete();
            $table->foreignId('gallery_id')->nullable()->after('announcement_id')->constrained('galleries')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('gallery_id');
            $table->dropConstrainedForeignId('announcement_id');
            $table->dropConstrainedForeignId('news_id');
        });
    }
};
