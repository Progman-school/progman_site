<?php

namespace App\Services;

use App\Models\Certificate;
use Exception;

class CertificateService
{
    /**
     * @throws Exception
     */
    public static function checkCertificate($certificateNumber, $fullName) {
        /** @var Certificate $checkCertificate */
        $checkCertificate = Certificate::where('full_number', trim($certificateNumber))->first();
        if (!$checkCertificate?->id) {
            throw new Exception("The certificate {$certificateNumber} is not found in the system or invalid.", 7);
        }

    }
}
