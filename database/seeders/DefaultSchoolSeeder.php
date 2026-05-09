<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\School;
use Carbon\Carbon;

class DefaultSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::updateOrCreate(
            ['slug' => 'default'],
            [
                'name'                  => 'Sekolah Default',
                'admin_name'            => 'Administrator',
                'subscription_status'   => 'active',
                'trial_ends_at'         => null,
                'subscription_ends_at'  => Carbon::now()->addYears(10),
            ]
        );

        // Backfill school_id NULL pada data existing
        DB::table('books')->whereNull('school_id')->update(['school_id' => $school->id]);
        DB::table('students')->whereNull('school_id')->update(['school_id' => $school->id]);
        DB::table('loans')->whereNull('school_id')->update(['school_id' => $school->id]);

        $this->command->info("DefaultSchoolSeeder: School ID {$school->id} — backfill selesai.");
    }
}
