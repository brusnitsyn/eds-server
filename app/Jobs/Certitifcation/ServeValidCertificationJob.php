<?php

namespace App\Jobs\Certitifcation;

use App\Models\Certification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

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

            $now = Carbon::now();
            $validTo = Carbon::createFromTimestampMs(intval($certification->valid_to));

            if ($certification->valid_to < $now->timestamp) {
                $certification->update(['is_valid' => false]);
                continue;
            }

            if ($now->diffInMonths($validTo) < 1) $certification->update(['is_request_new' => true]);

        }
    }
}
