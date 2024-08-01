<?php

namespace App\Http\Controllers\Api\v1\Certification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Certification\UploadCertificationRequest;
use Illuminate\Http\Request;

class CertificateReaderController extends Controller
{
    public function uploadCertification(UploadCertificationRequest $request)
    {
        return $request->readData();
    }
}
