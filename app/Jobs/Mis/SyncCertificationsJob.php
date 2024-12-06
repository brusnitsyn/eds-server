<?php

namespace App\Jobs\Mis;

use App\Models\MisStaffSettingsOption;
use App\Models\Staff;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SyncCertificationsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $staffs = Staff::all();

        $misCertSettingOption = MisStaffSettingsOption::where('key', 'nomer-sertifikata-polzovatelya')->first();
        $misCertValidFromSettingOption = MisStaffSettingsOption::where('key', 'sertifikat-deystvitelen-s')->first();
        $misCertValidToSettingOption = MisStaffSettingsOption::where('key', 'sertifikat-deystvitelen-po')->first();

        foreach ($staffs as $staff) {
            if ($staff->mis_user_id == null) continue;
            $misUserSettings = DB::connection('mis')->table('amu_mis_AOKB_prod.dbo.x_UserSettings')->where('rf_UserID', $staff->mis_user_id)->get();
            $misUserCertification = $misUserSettings->where('Property', '=', $misCertSettingOption->property)->select('ValueStr')->first();
            $misUserCertificationValidFrom = $misUserSettings->where('Property', '=', $misCertValidFromSettingOption->property)->select('ValueStr')->first();
            $misUserCertificationValidTo = $misUserSettings->where('Property', '=', $misCertValidToSettingOption->property)->select('ValueStr')->first();

            if ($misUserCertification === null) {
                $staff->certification()->update([
                    'mis_serial_number' => null,
                    'mis_valid_from' => $misUserCertificationValidFrom ? $misUserCertificationValidFrom['ValueStr'] : null,
                    'mis_valid_to' => $misUserCertificationValidTo ? $misUserCertificationValidTo['ValueStr'] : null,
                    'mis_is_identical' => false
                ]);
            } else {
                $staff->certification()->update([
                    'mis_serial_number' => $misUserCertification['ValueStr'],
                    'mis_valid_from' => $misUserCertificationValidFrom ? $misUserCertificationValidFrom['ValueStr'] : null,
                    'mis_valid_to' => $misUserCertificationValidTo ? $misUserCertificationValidTo['ValueStr'] : null,
                    'mis_is_identical' => $misUserCertification['ValueStr'] && Str::contains($staff->certification->serial_number, $misUserCertification['ValueStr'], true)
                ]);
            }

            $staff->update([
                'mis_sync_at' => Carbon::now(),
            ]);
        }
    }
}
