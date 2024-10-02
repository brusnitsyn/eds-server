<?php

namespace App\Http\Controllers\Api\v1\Certification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Certification\ArchiveUploadRequest;
use App\Http\Requests\Certification\DownloadCertRequest;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function archiveUpload(ArchiveUploadRequest $request)
    {
        return $request->upload();
    }

    public function downloadCert(DownloadCertRequest $request)
    {
        return $request->download();
    }
}
