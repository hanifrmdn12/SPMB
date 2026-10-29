<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table): void {
            $table->string('otp_kode')->nullable()->after('access_pin');
            $table->timestamp('otp_kadaluarsa_pada')->nullable()->after('otp_kode');
            $table->unsignedTinyInteger('otp_percobaan')->default(0)->after('otp_kadaluarsa_pada');
            $table->timestamp('otp_dikirim_pada')->nullable()->after('otp_percobaan');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table): void {
            $table->dropColumn(['otp_kode', 'otp_kadaluarsa_pada', 'otp_percobaan', 'otp_dikirim_pada']);
        });
    }
};
