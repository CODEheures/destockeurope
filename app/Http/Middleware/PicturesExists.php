<?php

namespace App\Http\Middleware;

use App\Http\Controllers\PictureController;
use Closure;

class PicturesExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $allPicturesExist = true;

        foreach (session('uploadPictures') as $uploadPicture) {
            $existThumb = PictureController::exist($uploadPicture['thumbUrl']);
            $existPicture = PictureController::exist($uploadPicture['normalUrl']);
            if (!$existThumb || !$existPicture) {
                $allPicturesExist = false;
                $sessionHash = array_merge([], array_filter(session('uploadPictures'), function($elem) use ($uploadPicture) {
                    return $elem['thumbUrl'] == $uploadPicture['thumbUrl'];
                }));

                PictureController::destroy($sessionHash[0]['hashName']);
            }

        }

        if($allPicturesExist){
            return $next($request);
        } else {
            return redirect()->back()->withInput()->withErrors(trans('strings.view_advert_create_image_too_old'));
        }
    }
}
