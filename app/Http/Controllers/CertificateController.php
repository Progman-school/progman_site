<?php
namespace App\Http\Controllers;

use App\Helpers\APIHelper;
use App\Services\CertificateService;
use Exception;
use Illuminate\Http\Request;

class CertificateController extends MainController
{
    /**
     * @throws Exception
     */
    public function checkCertificate(Request $request): string
    {
        return APIHelper::createFrontAnswer(
            CertificateService::checkCertificate($request->certificate, $request->student)
        );
    }
}

