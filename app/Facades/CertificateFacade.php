<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static readPropsCert($certFile)
 * @method static getInfoCertificate($certificatePath)
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
