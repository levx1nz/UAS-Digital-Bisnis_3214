<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('events', 'is_published')) {
            DB::table('events')
                ->whereNull('organizer_id')
                ->update(['is_published' => true]);
        }
    }

    public function down(): void
    {
    }
};