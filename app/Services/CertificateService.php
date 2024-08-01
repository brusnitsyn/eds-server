<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use phpseclib3\File\ASN1;
use phpseclib3\File\X509;

class CertificateService
{
    public function readPropsCert(UploadedFile $certFile)
    {
        $certName = $certFile->getClientOriginalName();
        $certPath = Storage::disk('local')->putFileAs('/certifications', $certFile, $certName);
        $certContents = file_get_contents(Storage::disk('local')->path($certPath));

        ASN1::loadOIDs([
            "inn" => "1.2.643.3.131.1.1",
        ]);

        $cert = new X509();
        $cert::registerExtension("id-at-inn", [
            'type' => ASN1::TYPE_SEQUENCE,
            'children' => [
                'toggle' => ['type' => ASN1::TYPE_BOOLEAN],
                'num' => ['type' => ASN1::TYPE_INTEGER],
                'name' => ['type' => ASN1::TYPE_OCTET_STRING],
                'list' => [
                    'type' => ASN1::TYPE_SEQUENCE,
                    'min' => 0,
                    'max' => -1,
                    'children' => ['type' => ASN1::TYPE_OCTET_STRING],
                ],
            ]
        ]);

        $cert->loadX509($certContents);

        $serialNumber = Str::upper($cert->getCurrentCert()['tbsCertificate']['serialNumber']->toHex(true));
        $validFrom = Carbon::parse($cert->getCurrentCert()['tbsCertificate']['validity']['notBefore']['utcTime'])->getTimestamp();
        $validTo = Carbon::parse($cert->getCurrentCert()['tbsCertificate']['validity']['notAfter']['utcTime'])->getTimestamp();

        $job_title = $cert->getSubjectDNProp('id-at-title')[0];
        $full_name = $cert->getSubjectDNProp('id-at-commonName')[0];
        $explodeFullName = Str::of($full_name)->explode(' ');
        $first_name = $explodeFullName[1];
        $middle_name = $explodeFullName[2];
        $last_name = $explodeFullName[0];
        $snils = 3912038; ///TODO FIX THIS ///$cert->getSubjectDNProp('id-at-snils');
        $inn = 3912038; ///TODO FIX THIS ///$cert->getSubjectDNProp('id-at-inn');

        $result = [
            'serial_number' => $serialNumber,
            'valid_from' => $validFrom,
            'valid_to' => $validTo,
            'job_title' => $job_title,
            'full_name' => $full_name,
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' => $last_name,
            'snils' => $snils,
            'inn' => $inn
        ];

        return response()
            ->json($result)
            ->setStatusCode(200);
    }
}
