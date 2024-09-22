<?php

namespace App\Services;

use App\Exceptions\InvalidCredentialsException;
use App\Facades\CertificateFacade;
use App\Models\Certification;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StaffService
{
    public function create(array $data): \Illuminate\Http\JsonResponse
    {
        $isPackage = $data['is_package'];
        $archive = $data['archive'];

        if ($isPackage) {
            return $this->readMany($archive);
        }

        $certificationFiles = $this->readCertificationArchive($archive, 'certifications', true);

        $certificateFile = null;
        foreach ($certificationFiles as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) == "cer") {
                $certificateFile = Storage::disk('temp')->path($file);
            }
        }

        $pathinfo = pathinfo($certificateFile);
        $certStorage = $this->copyToCertificationDirectory($pathinfo['filename']);

        $this->createCert($certStorage, $certificateFile, $certificateFile);

        return response()->json([
            'status' => 'ok',
            'message' => 'Персона создана!'
        ])->setStatusCode(201);
    }



    private function readCertificationArchive($archivePath, $tempPath = 'archives', $isCertArchive = false) {
        $zipTool = new \ZipArchive();
        $zipTool->open($archivePath);
        $pathInfo = pathinfo($archivePath);
        $archiveName = $pathInfo['filename'];
        $extractionPath = storage_path("app/temp/$tempPath");

        if (Storage::disk('temp')->exists($tempPath)) {
            Storage::disk('temp')->deleteDirectory($tempPath);
            Storage::disk('temp')->makeDirectory($tempPath);
        }

        $zipTool->extractTo($extractionPath);
        $zipTool->close();

        if ($isCertArchive) {
            return Storage::disk('temp')->files("/$tempPath");
        }

        return Storage::disk('temp')->files("/$tempPath/$archiveName");
    }

//    private function readCertificationArchive(string $archivePath, $tempPath = 'archives', $isCertArchive = false)
//    {
//
//    }

    private function extractToFolder(string $archivePath, $tempPath = 'archives')
    {
        $zipTool = new \ZipArchive();
        $zipTool->open($archivePath);

        $extractionPath = storage_path("app/temp/$tempPath");

        if (Storage::disk('temp')->exists($tempPath)) {
            Storage::disk('temp')->deleteDirectory($tempPath);
            Storage::disk('temp')->makeDirectory($tempPath);
        }

        $hasExtracted = $zipTool->extractTo($extractionPath);
        $zipTool->close();

        return Storage::disk('temp')->path($tempPath);
    }

    private function getCertificateName(string $disk, string $folder) {
        $files = Storage::disk($disk)->files($folder);
        $certificateFile = null;
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) == "cer") {
                $certificateFile = Storage::disk($disk)->path($file);
            }
        }

        return $certificateFile;
    }

    private function readMany($archive) {
        $folderZips = $this->extractToFolder($archive->getRealPath()); // Получить файлы по пути


        foreach ($zips as $zip) {
            $path = Storage::disk('temp')->path($zip);
            $certificationFolder = $this->extractToFolder($path, 'certifications');
            $certificationFileName = $this->getCertificateName('temp', $certificationFolder);

            if ($certificationFileName) {
                $certStorage = $this->copyToCertificationDirectory($certificationFileName);
                $this->createCert($certStorage, $certificationFolder, $certificationFileName);
            }
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Архив отправлен на обработку!'
        ])->setStatusCode(201);
    }

    private function copyToCertificationDirectory($folderName = "") {
        $temp = Storage::disk('temp')->path('/certifications');
        $dir = Storage::disk('certification')->path($folderName);
        $hasCopied = \Illuminate\Support\Facades\File::copyDirectory($temp, $dir);
        return $hasCopied ? $dir : false;
    }

    public function createMany(UploadedFile $archive): \Illuminate\Http\JsonResponse
    {
        $zipTool = new \ZipArchive();
        $zipTool->open($archive->getRealPath());
        $storagePath = "/certifications";
        $extractionPath = storage_path("app/temp/certifications");

        if (!Storage::disk('temp')->exists($storagePath)) {
            Storage::disk('temp')->makeDirectory($storagePath);
        }

        $zipTool->extractTo($extractionPath);
        $zipTool->close();

        $certifications = Storage::disk('temp')->files('/certifications');

        foreach ($certifications as $certification) {
            $path = Storage::disk('temp')->path($certification);
            $zipTool->open($path);
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Персона создана!'
        ])->setStatusCode(201);
    }

    public function get(array $data)
    {

    }

    public function update(Staff $staff, array $data): \Illuminate\Http\JsonResponse
    {
        $cert = $data['cert'];
        $staff->update($data);
        $staff->certification()->update($cert);

        return response()->json([
            'status' => 'ok',
            'message' => 'Информация о пользователе обновлена!'
        ]);
    }

    /**
     * @param string $certStorage
     * @param string $tempPath
     * @param string $path
     * @return void
     */
    public function createCert(string $certStorage, string $tempPath, string $folderName): void
    {
        if ($certStorage) {
            $certStorageTempPath = pathinfo(Storage::disk('temp')->path($tempPath));
            $certInfo = CertificateFacade::getInfoCertificate('certification', $folderName);

            $createdStaff = Staff::where('snils', $certInfo['snils'])->first();
            $certInfo['cert']['path_certification'] = "certifications/{$certStorageTempPath['filename']}";
            $certInfo['full_name'] = "{$certInfo['last_name']} {$certInfo['first_name']} {$certInfo['middle_name']}";
            $certInfo['job_title'] = ucfirst(strtolower($certInfo['job_title']));

            if (!$createdStaff) {
                $createdStaff = Staff::create($certInfo);
                $createdStaff->certification()->create($certInfo['cert']);
            }

            $createdStaff->certification()->update($certInfo['cert']);
        }
    }
}
