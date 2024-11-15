<?php

namespace App\Jobs\Mis;

use App\Models\Staff;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncUserIDToStaffJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $staffs = Staff::where('mis_user_id', null)->get();
        foreach ($staffs as $staff) {
            $misUser = DB::connection('mis')->table('dbo.x_User')->where('FIO', $staff->full_name)->first();
            if ($misUser) {
                $staff->update([
                    'mis_user_id' => $misUser->UserID,
                    'mis_guid' => $misUser->GUID,
                    'mis_sync_at' => Carbon::now()
                ]);

                $staff->integrations()->updateOrCreate(['name' => 'ТМ:МИС'], ['name' => 'ТМ:МИС', 'login' => $misUser->GeneralLogin]);
            }
        }
    }
}
