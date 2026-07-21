<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $hmif = DB::table('users')->where('email', 'hmif@amikom.ac.id')->first();

        if ($hmif) {
            DB::table('events')->update([
                'organizer_id' => $hmif->id,
                'is_published' => true,
            ]);
        }
    }

    public function down(): void
    {
    }
};