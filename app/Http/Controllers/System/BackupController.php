<?php

namespace App\Http\Controllers\System;

use Config;
use Artisan;
use DateTime;
Use Throwable;
use App\Traits\BackupTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;


class BackupController extends Controller
{

    use BackupTrait;

    public function index() {

        $most_recent = $this->mostRecent();

        return view('system.backup.index')->with('last_zip', $most_recent);
    }

    public function db()
    {
        $output = Artisan::call('bk:bd');
        return json_encode($output);
    }

    public function files()
    {
        $output = Artisan::call('bk:files');
        return json_encode($output);
    }

    public function upload(Request $request)
    {

        $config = [
            'driver' => 'ftp',
            'host'   => $request['host'],
            'port' => $request['port'],
            'username' => $request['username'],
            'password'   => $request['password'],
            'port'  => 21,
            'passive'   => true,
        ];

        Config::set('filesystems.disks.ftp', $config);
        try{
            $most_recent = $this->mostRecent();
            $fileTo = $most_recent['name'];
            $fileFrom = Storage::get($most_recent['path']);
            $upload = Storage::disk('ftp')->put($fileTo, $fileFrom);

            return [
                'success' => $upload,
                'message' => 'Proceso finalizado satisfactoriamente'
            ];

        }catch (Throwable $e) {
            $this->setErrorLog($e);
            return $this->getErrorMessage("Lo sentimos, ocurrió un error inesperado: {$e->getMessage()}");
        }

    }

    public function mostRecent()
    {
        $zips = Storage::allFiles('backups/zip/');

        if (count($zips) > 0) {
            $name_zips = [];
            $most_recent_time = '';
            $last_date = null;

            foreach($zips as $zip){
                $zip_explode = explode( '/', $zip);
                if(count($zip_explode) <= 3){
                    array_push($name_zips, $zip_explode[2]);
                    $last = Storage::lastModified($zip);
                    $datetime = new DateTime("@$last");
                    if ($datetime > $most_recent_time) {
                        $most_recent_time = $datetime;
                        $most_recent_path = $zip;
                        $most_recent_name = $zip_explode[2];
                        $last_date = $last;
                    }
                }
            }

            return [
                'date' => \Carbon\Carbon::createFromTimestamp($last_date)->format('d-m-Y \a \l\a\s H:i'),
                'path' => $most_recent_path,
                'name' => $most_recent_name
            ];
        } else {
            return '';
        }
    }


    public function download($filename)
    {
        $most_recent = $this->mostRecent();

        if ($most_recent) {
            return Storage::download('backups'.DIRECTORY_SEPARATOR.'zip'.DIRECTORY_SEPARATOR.$filename);
        } else {
            return [
                'message' => 'Lo sentimos, no existen Backups Generados'];
        }
    }

}
