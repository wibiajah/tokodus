<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Fix data duplikat dulu
        $duplicates = DB::table('users')
            ->select('toko_id', DB::raw('COUNT(*) as count'))
            ->where('role', 'kepala_toko')
            ->whereNotNull('toko_id')
            ->where('toko_id', '!=', 999)
            ->groupBy('toko_id')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            $firstKepala = DB::table('users')
                ->where('role', 'kepala_toko')
                ->where('toko_id', $duplicate->toko_id)
                ->orderBy('created_at', 'asc')
                ->first();

            DB::table('users')
                ->where('role', 'kepala_toko')
                ->where('toko_id', $duplicate->toko_id)
                ->where('id', '!=', $firstKepala->id)
                ->update(['toko_id' => 999]);
        }

        // 2. Trigger untuk mencegah duplikasi saat INSERT
        DB::unprepared("
            CREATE TRIGGER check_kepala_toko_insert
            BEFORE INSERT ON users
            FOR EACH ROW
            BEGIN
                DECLARE kepala_count INT;
                
                IF NEW.role = 'kepala_toko' AND NEW.toko_id IS NOT NULL AND NEW.toko_id != 999 THEN
                    SELECT COUNT(*) INTO kepala_count
                    FROM users 
                    WHERE role = 'kepala_toko' 
                      AND toko_id = NEW.toko_id 
                      AND toko_id != 999;
                    
                    IF kepala_count > 0 THEN
                        SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Toko ini sudah memiliki Kepala Toko!';
                    END IF;
                END IF;
            END
        ");

        // 3. Trigger untuk mencegah duplikasi saat UPDATE
        DB::unprepared("
            CREATE TRIGGER check_kepala_toko_update
            BEFORE UPDATE ON users
            FOR EACH ROW
            BEGIN
                DECLARE kepala_count INT;
                
                IF NEW.role = 'kepala_toko' AND NEW.toko_id IS NOT NULL AND NEW.toko_id != 999 THEN
                    SELECT COUNT(*) INTO kepala_count
                    FROM users 
                    WHERE role = 'kepala_toko' 
                      AND toko_id = NEW.toko_id 
                      AND toko_id != 999
                      AND id != NEW.id;
                    
                    IF kepala_count > 0 THEN
                        SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Toko ini sudah memiliki Kepala Toko!';
                    END IF;
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS check_kepala_toko_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS check_kepala_toko_update');
    }
};