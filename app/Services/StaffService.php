<?php

namespace App\Services;

use App\Facades\CertificateFacade;
use App\Http\Resources\StaffIntegrate\StaffIntegrateResource;
use App\Models\MisStaffSettingsOption;
use App\Models\Staff;
use App\Models\StaffIntegrate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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
        } else {
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

        if (Storage::disk('temp')->exists($extractToFolder)) {
            Storage::disk('temp')->deleteDirectory($extractToFolder);
            Storage::disk('temp')->makeDirectory($extractToFolder);
        } else {
            Storage::disk('temp')->makeDirectory($extractToFolder);
        }

        $extractionPath = storage_path("app/temp/$extractToFolder");

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
            } else {
                $createdStaff->update($certInfo);
                $createdStaff->certification()->update($certInfo['cert']);
            }

            $misUser = DB::connection('mis')->table('dbo.x_User')->where('FIO', $createdStaff->full_name)->first();
            if ($misUser)
            {
                $createdStaff->integrations()->updateOrCreate(['name' => 'ТМ:МИС'], ['name' => 'ТМ:МИС', 'login' => $misUser->GeneralLogin]);
            }
        }
    }

    public function delete(Staff $staff)
    {
        $hasDeleteCert = Storage::disk('local')->deleteDirectory($staff->certification->path_certification);
        $staff->delete();
        return response()->json([
            'status' => 'success',
        ]);
    }

    public function createIntegrate(Staff $staff, array $data)
    {
        $createdIntegrate = $staff->integrations()->create($data);
        return StaffIntegrateResource::make($createdIntegrate);
    }

    public function updateIntegrate(StaffIntegrate $staffIntegrate, array $data)
    {
        $staffIntegrate->update($data);
        return StaffIntegrateResource::make($staffIntegrate);
    }

    public function insertCertToMis(Staff $staff)
    {
        if (!$staff->certification()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'У указаного пользователя нет сертификата!'
            ]);
        }

        if ($staff->mis_user_id == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Пользователя нет в ТМ:МИС!'
            ]);
        }

        $misCertSettingOption = MisStaffSettingsOption::where('key', 'nomer-sertifikata-polzovatelya')->first();
        $misCertValidFromSettingOption = MisStaffSettingsOption::where('key', 'sertifikat-deystvitelen-s')->first();
        $misCertValidToSettingOption = MisStaffSettingsOption::where('key', 'sertifikat-deystvitelen-po')->first();

        $baseModelSetting = [
            'rf_UserID' => $staff->mis_user_id,
            'OwnerGUID' => '00000000-0000-0000-0000-000000000000',
            'DocTypeDefGUID' => '00000000-0000-0000-0000-000000000000',
        ];

        $certSettingModel = [
            'rf_SettingTypeID' => $misCertSettingOption->setting_type,
            'Property' => $misCertSettingOption->property,
            'ValueStr' => $staff->certification->serial_number,
        ];

        $certValidFromModel = [
            'rf_SettingTypeID' => $misCertValidFromSettingOption->setting_type,
            'Property' => $misCertValidFromSettingOption->property,
            'ValueStr' => $staff->certification->valid_from,
        ];

        $certValidToModel = [
            'rf_SettingTypeID' => $misCertValidToSettingOption->setting_type,
            'Property' => $misCertValidToSettingOption->property,
            'ValueStr' => $staff->certification->valid_to,
        ];

        DB::connection('mis')->table('amu_mis_AOKB_prod.dbo.x_UserSettings')->updateOrInsert([
            'rf_UserID' => $staff->mis_user_id,
            'Property' => $misCertSettingOption->property,
        ], array_merge($baseModelSetting, $certSettingModel));

        DB::connection('mis')->table('amu_mis_AOKB_prod.dbo.x_UserSettings')->updateOrInsert([
            'rf_UserID' => $staff->mis_user_id,
            'Property' => $misCertValidFromSettingOption->property,
        ], array_merge($baseModelSetting, $certValidFromModel));

        DB::connection('mis')->table('amu_mis_AOKB_prod.dbo.x_UserSettings')->updateOrInsert([
            'rf_UserID' => $staff->mis_user_id,
            'Property' => $misCertValidToSettingOption->property,
        ], array_merge($baseModelSetting, $certValidToModel));

        $staff->certification()->update([
            'mis_serial_number' => $staff->certification->serial_number,
            'mis_valid_from' => $staff->certification->valid_from,
            'mis_valid_to' => $staff->certification->valid_to,
            'mis_is_identical' => true
        ]);

        $staff->update([
            'mis_sync_at' => Carbon::now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Сертификат пользователя был прописан в МИС'
        ]);
    }
}
