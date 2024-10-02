<?php

namespace App\Services;

use App\Models\Staff;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

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
        $certPath = Storage::disk('certification')->putFileAs($certName, $certFile, $certName);

        $result = $this->getInfoCertificate($certPath);

        return response()
            ->json($result)
            ->setStatusCode(200);
    }

    public function getInfoCertificate(string $pathToCert)
    {
        if ($pathToCert == null) {return;}

        $pathToCert = Storage::path($pathToCert);
        Log::info($pathToCert);

        $certContents = file_get_contents($pathToCert);

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

        $job_title = Str::ucfirst(Str::lower($parsedSubject['title'] ?? ''));

        $result = [
            'cert' => [
                'serial_number' => $serialNumber,
                'valid_from' => Carbon::parse($parsedCert['validFrom_time_t'])->valueOf(),
                'valid_to' => Carbon::parse($parsedCert['validTo_time_t'])->valueOf(),
            ],
            'job_title' => $job_title,
            'full_name' => $full_name,
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' => $last_name,
            'snils' => $parsedSubject['SNILS'],
            'inn' => $parsedSubject['INN']
        ];

        return $result;
    }

    public function download(array $staffIds)
    {
        $staffs = Staff::whereIn('id', $staffIds)->get();
        $timestampNow = Carbon::now()->getTimestampMs();
        $zipPath = Storage::disk('files')->path('');
        $zipName = "$timestampNow.zip";
        $zip = new ZipArchive();
        $zip->open("$zipPath/$zipName", ZipArchive::CREATE | ZipArchive::OVERWRITE);

        if (count($staffIds) == 0) {
            return response()->json([
                /// TODO:
            ], 404);
        }

        foreach ($staffs as $staff) {
            $certFiles = Storage::allFiles($staff->certification->path_certification);
            foreach ($certFiles as $certFile) {
                $filePath = Storage::path($certFile);
                $zip->addFile($filePath, $certFile);
            }
        }

        $zip->close();

        return Storage::download("files/$zipName");
    }
}
