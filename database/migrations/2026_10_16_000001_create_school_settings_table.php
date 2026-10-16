<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();
            $table->string('school_name')->default('MAS AL-MAHFUZHIYAH KALIDADI');
            $table->string('short_name')->nullable();
            $table->string('npsn')->nullable();
            $table->string('accreditation')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('principal_name')->nullable();
            $table->text('principal_welcome')->nullable();
            $table->string('principal_photo')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->text('history')->nullable();
            $table->string('maps_embed_url', 2000)->nullable();
            $table->json('social_links')->nullable();
            $table->json('statistics')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_settings');
    }
};
