<?php

namespace App\Services;

use App\Facades\CertificateFacade;
use App\Models\Staff;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpZip\ZipFile;

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

        if ($certificationFiles == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ошибка в пакете!'
            ])->setStatusCode(400);
        }

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
        $zipTool = new ZipFile();
        $zipTool->openFile($archivePath);

        if ($isCertArchive) {
            $hasBadFile = false;
            foreach ($zipTool->getListFiles() as $fileInZip) {
                $extFile = pathinfo($fileInZip, PATHINFO_EXTENSION);
                if ($extFile == "zip") {
                    $hasBadFile = true;
                } else {
                    $hasBadFile = false;
                }
            }

            if ($hasBadFile) {
                return null;
            }
        }

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

    private function extractToFolder(string $archivePath, $extractToFolder = 'archives')
    {
        $zipTool = new ZipFile();
        $zipTool->openFile($archivePath);

        $extractionPath = storage_path("app/temp/$extractToFolder");

        if (Storage::disk('temp')->exists($extractToFolder)) {
            Storage::disk('temp')->deleteDirectory($extractToFolder);
            Storage::disk('temp')->makeDirectory($extractToFolder);
        }

        $zipTool->extractTo($extractionPath);
        $zipTool->close();

        return Storage::disk('temp')->path($extractToFolder);
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

    public function getCertificateFileName(string $disk, string $folder)
    {
        $files = Storage::disk($disk)->files($folder);
        $certificateFile = null;
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) == "cer") {
                $certificateFile = Storage::disk($disk)->path($file);
            }
        }

        $pathinfoFilename = pathinfo($certificateFile, PATHINFO_FILENAME);
        return Str::trim($pathinfoFilename);
    }

    private function readMany($archive) {
        $this->extractToFolder($archive->getRealPath());
        $zips = Storage::disk('temp')->files('/archives');

        foreach ($zips as $zip) {
            $path = Storage::disk('temp')->path($zip);
            $certificationFolder = $this->extractToFolder($path, 'certifications');
//            $certificationPathToFile = $this->getCertificatePath('temp', 'certifications');
            $certificationFileName = $this->getCertificateFileName('temp', 'certifications');
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

    private function copyToCertificationDirectory($certificationFolderName) {
        $temp = Storage::disk('temp')->path('/certifications');

        if (Storage::disk('certification')->exists($certificationFolderName)) {
            Storage::disk('certification')->deleteDirectory($certificationFolderName);
        }

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
            $certStoragePath = pathinfo(Storage::disk('certification')->path($folderName));

            $certificateInfo = null;
            $files = Storage::disk('certification')->files($folderName);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) == "cer") {
                    $certificateInfo = pathinfo($file);
                }
            }

            $certificatePath = "certifications/{$certificateInfo['dirname']}/{$certificateInfo['basename']}";

            $certInfo = CertificateFacade::getInfoCertificate($certificatePath);

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

            $certInfo['cert']['path_certification'] = "certifications/{$certificateInfo['dirname']}";
            $certInfo['cert']['file_certification'] = $certificateInfo['basename'];
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
