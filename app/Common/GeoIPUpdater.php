<?php

namespace App\Common;


class GeoIPUpdater
{
    /**
     *
     * Update GEOLITE2 MMDB DATABASE file from maxmind.com
     * http://dev.maxmind.com/geoip/geoip2/geolite2/
     * GeoLite2 databases are updated on the first Tuesday of each month.
     * Work with Schedule in App\Console\Kernel
     *      $schedule->call(function(){
     *           try {
     *               $geoIpResult = GeoIPUpdater::updateGeoIpFiles();
     *           } catch (\Exception $e) {
     *               $geoIpResult = false;
     *           }
     *           if(!Storage::disk('logs')->exists('geoIpUpdate.log')){
     *           Storage::disk('logs')->append('geoIpUpdate.log' , 'DATE;RESULT;');
     *           }
     *           Storage::disk('logs')->append('geoIpUpdate.log' , Carbon::now()->toDateTimeString() . ';' . $geoIpResult);
     *      })->monthlyOn(7,'3:57');
     *
     *
     * @return bool
     */
    public static function updateGeoIpFiles() {
        //http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz
        $geodblink = config('geoip.uri.mmdb');

        //http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.md5
        $geodbmd5link = config('geoip.uri.md5');

        //GET DB & MD5 FILES
        $database_gz_filePath = self::getHTTPFile($geodblink, resource_path() . '/geoip/');
        $md5_filePath = self::getHTTPFile($geodbmd5link, resource_path() . '/geoip/');

        //UNZIP, TEST MD5 & COPY TO VENDOR\PragmaRX\Support\GeoIp;
        if($database_gz_filePath && $md5_filePath){
            $database_filePath= self::dezipGzFile(resource_path() . '/geoip/' . basename(config('geoip.uri.mmdb')));
            if($database_filePath){
                $calc_md5 = md5_file($database_filePath);
                $original_md5 = file_get_contents($md5_filePath);
                if($calc_md5==$original_md5){
                    $final_success = copy($database_filePath, base_path('vendor/PragmaRX/Support/src/GeoIp/'.basename($database_filePath)));
                    return $final_success;
                }
                return false;
            }
            return false;
        }
        return false;
    }

    private static function getHTTPFile($uri, $destinationPath) {
        set_time_limit(360);

        $fileWriteName = $destinationPath . basename($uri);

        $fileRead = @fopen($uri,"rb");
        $fileWrite = @fopen($fileWriteName, 'wb');
        if ($fileRead===false || $fileWrite===false) {
            // error reading or opening file
            return false;
        }

        while(!feof($fileRead))
        {
            $content = @fread($fileRead, 1024*8);
            $success = fwrite($fileWrite, $content);
            if($success===false){
                return false;
            }
        }
        fclose($fileWrite);
        fclose($fileRead);

        return $fileWriteName;
    }

    private static function dezipGzFile($filePath) {
        $buffer_size = 4096; // read 4kb at a time
        $out_file_name = str_replace('.gz', '', $filePath);

        $fileRead = gzopen($filePath, 'rb');
        $fileWrite = fopen($out_file_name, 'wb');

        if ($fileRead===false || $fileWrite===false) {
            // error reading or opening file
            return false;
        }

        while(!gzeof($fileRead)) {
            // Read buffer-size bytes
            // Both fwrite and gzread and binary-safe
            $success = fwrite($fileWrite, gzread($fileRead, $buffer_size));
            if($success===false){
                return $success;
            }
        }

        // Files are done, close files
        fclose($fileWrite);
        gzclose($fileRead);
        return $out_file_name;
    }

}