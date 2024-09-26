<?php

namespace App\Jobs\Certitifcation;

use App\Models\Certification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class ServeValidCertificationJob implements ShouldQueue
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
        $certifications = Certification::all();

        foreach ($certifications as $certification) {
            if (!($certification->valid_to < Carbon::now()->timestamp)) {
                $certification->update(['is_valid' => false]);
                continue;
            }

            if (Carbon::diffInMonths($certification->validTo) < 1) $certification->update(['is_request_new' => true]);
        }
    }
}
