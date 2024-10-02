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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $certificateFileName = Str::trim(pathinfo($certificateFile, PATHINFO_FILENAME));
        $certificateFolder = dirname($certificateFile);
        $certStorage = $this->copyToCertificationDirectory($certificateFileName);

        $this->createCert($certStorage, $certificateFolder, $certificateFileName);

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

    private function extractToFolder(string $archivePath, $tempPath = 'archives')
    {
        $zipTool = new \ZipArchive();
        $zipTool->open($archivePath);

        $extractionPath = storage_path("app/temp/$tempPath");

        if (Storage::disk('temp')->exists($tempPath)) {
            Storage::disk('temp')->deleteDirectory($tempPath);
            Storage::disk('temp')->makeDirectory($tempPath);
        }

        $zipTool->extractTo($extractionPath);
        $zipTool->close();

        return Storage::disk('temp')->path($tempPath);
    }

    private function getCertificatePath(string $disk, string $folder) {
        $files = Storage::disk($disk)->files($folder);
        $certificateFile = null;
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) == "cer") {
                $certificateFile = Storage::disk($disk)->path($file);
            }
        }

        return $certificateFile;
    }

    public function getCertificateFileName(string $path)
    {
        return Str::trim(pathinfo($path, PATHINFO_FILENAME));
    }

    private function readMany($archive) {
        $this->extractToFolder($archive->getRealPath());
        $zips = Storage::disk('temp')->files('/archives');

        foreach ($zips as $zip) {
            $path = Storage::disk('temp')->path($zip);
            $certificationFolder = $this->extractToFolder($path, 'certifications');
            $certificationPathToFile = $this->getCertificatePath('temp', 'certifications');
            $certificationFileName = $this->getCertificateFileName($certificationPathToFile);
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

    private function copyToCertificationDirectory($certificationFolderName = "") {
        $temp = Storage::disk('temp')->path('/certifications');
        $dir = Storage::disk('certification')->path($certificationFolderName);
        $hasCopied = \Illuminate\Support\Facades\File::copyDirectory($temp, $dir);
        return $hasCopied ? $dir : false;
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

            $now = Carbon::now();
            $validTo = Carbon::createFromTimestampMs($certInfo['cert']['valid_to']);

            if ($validTo->isFuture()) {
                if ($now->diffInMonths($validTo) < 1) {
                    $certInfo['cert']['is_request_new'] = true;
                } else {
                    $certInfo['cert']['is_request_new'] = false;
                }
                $certInfo['cert']['is_valid'] = true;
            } else {
                $certInfo['cert']['is_valid'] = false;
            }

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
