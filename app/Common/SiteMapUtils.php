<?php

namespace App\Common;

use App\Advert;


trait SiteMapUtils
{

    public static function sitemapUpdate() {
        try {
            $countAdverts = Advert::count();
            $limit = 3;

            $sitemapFileName = public_path('sitemap-products.xml');
            if(file_exists($sitemapFileName)){
                unlink($sitemapFileName);
            }

            $content = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
                '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9  http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL;



            for ($i = 0; $i <= $countAdverts; $i+=$limit){
                $adverts = Advert::offset($i)->limit($limit)->get();
                foreach ($adverts as $advert) {
                    $content = $content . '<url><loc>'. route('advert.show', ['slug' => $advert->slug]) . '</loc></url>' . PHP_EOL;
                }
            }

            $content = $content . '</urlset>';
            file_put_contents($sitemapFileName, $content);
            return $countAdverts;
        } catch (\Exception $e) {
            return false;
        }
    }


}