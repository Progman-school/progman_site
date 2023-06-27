<?php
namespace App\Http\Controllers;

use App\Services\CertificateService;
use Illuminate\Http\Request;

class CertificateController extends MainController
{
    public function checkCertificate(Request $request): string
    {
        return self::do(CertificateService::checkCertificate());
    }
}

