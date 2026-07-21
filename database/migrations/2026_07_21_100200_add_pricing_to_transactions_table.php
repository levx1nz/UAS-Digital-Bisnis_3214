<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('ticket_tier_id')->nullable()->after('event_id')
                  ->constrained('ticket_tiers')->nullOnDelete();
            $table->string('ticket_tier_name')->nullable()->after('ticket_tier_id');
            $table->integer('original_price')->default(0)->after('ticket_tier_name');
            $table->string('coupon_code')->nullable()->after('original_price');
            $table->integer('discount')->default(0)->after('coupon_code');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ticket_tier_id');
            $table->dropColumn(['ticket_tier_name', 'original_price', 'coupon_code', 'discount']);
        });
    }
};