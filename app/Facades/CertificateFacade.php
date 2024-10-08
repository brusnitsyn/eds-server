<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static readPropsCert($certFile)
 * @method static getInfoCertificate(string $pathToCert)
 * @method static download(array $staffIds)
 *
 * @see \App\Services\CertificateService
 */
class CertificateFacade extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return 'certificate.facade';
    }
}
