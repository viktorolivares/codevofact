<?php
namespace Modules\Padron\Traits;

use ZipArchive;
/**
 * Class Extractor
 *
 * Extract a archive (zip/gzip/rar) file.
 *
 * @author CodexWorld
 * @url https://www.codexworld.com
 *
 */
trait ExtractorTrait {

    /**
     * Checks file extension and calls suitable extractor functions.
     *
     * @param $archive
     * @param $destination
     */
    public static function extract($archive, $destination){

        $ext = pathinfo($archive, PATHINFO_EXTENSION);
        switch ($ext){
            case 'zip':
                $res = self::extractZipArchive($archive, $destination);
                break;
        }

        return $res;
    }

    /**
     * Decompress/extract a zip archive using ZipArchive.
     *
     * @param $archive
     * @param $destination
     */
    public static function extractZipArchive($archive, $destination){
        // Check if webserver supports unzipping.
        if(!class_exists('ZipArchive')){
            $GLOBALS['status'] = array('error' => 'Your PHP version does not support unzip functionality.');
            return false;
        }

        $zip = new ZipArchive;

        // Check if archive is readable.
        if($zip->open($archive) === TRUE){
            // Check if destination is writable
            if(is_writeable($destination . '/')){
                $zip->extractTo($destination);
                $zip->close();
                $GLOBALS['status'] = array('success' => 'Files unzipped successfully');
                return true;
            }else{
                $GLOBALS['status'] = array('error' => 'Directory not writeable by webserver.');
                return false;
            }
        }else{
            $GLOBALS['status'] = array('error' => 'Cannot read .zip archive.');
            return false;
        }
    }
}
