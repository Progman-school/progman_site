<?php

namespace App\Http\Controllers;

use PDO;

class DBRebuilder extends MainController
{
    const OLD_DB_HOST = "mysql";
    const OLD_DB_PORT = "3306";
    const OLD_DB_NAME = "laravel_tmp";
    const OLD_DB_USER = "laravel";
    const OLD_DB_PASSWORD = "pass";

    public function oldDBConnection():PDO {
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        return new PDO('mysql:host=' . self::OLD_DB_HOST . ';dbname=' . self::OLD_DB_NAME, self::OLD_DB_USER, self::OLD_DB_PASSWORD, $options);
    }

    public function rebuildTags():string {
        $oldDB = $this->oldDBConnection();
        $oldDB->beginTransaction();
        $oldDBRequest = $oldDB->query("SELECT * FROM `language_contents`");
        $oldDBBData = $oldDBRequest->fetchAll(PDO::FETCH_ASSOC);
        $oldDB->commit();

        return self::do($oldDBBData);;
    }

}
