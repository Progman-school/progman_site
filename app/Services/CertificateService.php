<?php

namespace App\Services;

use App\Exceptions\UserAlert;
use App\Models\Certificate;
use Exception;

class CertificateService extends MainService
{
    /**
     * @throws Exception
     */
    public static function checkCertificate($certificateNumber, $fullName): string {
        /** @var Certificate $certificate */
        $certificate = Certificate::with("user")
            ->with("course")
            ->with("technologies")
            ->where('full_number', trim($certificateNumber))
            ->first();

        if (!$certificate?->id || trim($certificate?->user?->real_last_name) !== trim($fullName)) {
            throw new UserAlert("The certificate {$certificateNumber} is not found in the system or invalid.");
        }

        return view(
            'api_answers.certificate_result_message',
            ['certificate' => $certificate]
        )->render();
    }
}
