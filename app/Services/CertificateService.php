<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateService
{
    private function bcdechex($dec) {
        $last = bcmod($dec, 16);
        $remain = bcdiv(bcsub($dec, $last), 16);
        if($remain == 0) {
            return dechex($last);
        } else {
            return $this->bcdechex($remain).dechex($last);
        }
    }

    public function readPropsCert(UploadedFile $certFile)
    {
        $certName = $certFile->getClientOriginalName();
        $certPath = Storage::disk('local')->putFileAs('/certifications', $certFile, $certName);
        $certContents = file_get_contents(Storage::disk('local')->path($certPath));

        $certificateCAPemContent = '-----BEGIN CERTIFICATE-----'.PHP_EOL
            .chunk_split(base64_encode($certContents), 64, PHP_EOL)
            .'-----END CERTIFICATE-----'.PHP_EOL;

        $parsedCert = openssl_x509_parse($certificateCAPemContent);

        $serialNumber = $parsedCert['serialNumber'];

        if(intval($serialNumber)) $serialNumber = strtoupper($this->bcdechex($parsedCert['serialNumber']));

        $parsedSubject = $parsedCert['subject'];

        $full_name = $parsedSubject['CN'];
        $explodeFullName = Str::of($full_name)->explode(' ');
        $first_name = $explodeFullName[1];
        $middle_name = $explodeFullName[2];
        $last_name = $explodeFullName[0];

        $result = [
            'cert' => [
                'serial_number' => $serialNumber,
                'valid_from' => Carbon::parse($parsedCert['validFrom_time_t'])->valueOf(),
                'valid_to' => Carbon::parse($parsedCert['validTo_time_t'])->valueOf(),
            ],
            'job_title' => $parsedSubject['title'],
            'full_name' => $full_name,
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' => $last_name,
            'snils' => $parsedSubject['SNILS'],
            'inn' => $parsedSubject['INN']
        ];

        return response()
            ->json($result)
            ->setStatusCode(200);
    }
}
