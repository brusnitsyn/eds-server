<?php

namespace App\Http\Controllers\Api\v1\Certification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Certification\ArchiveUploadRequest;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function archiveUpload(ArchiveUploadRequest $request)
    {
        return $request->upload();
    }
}
