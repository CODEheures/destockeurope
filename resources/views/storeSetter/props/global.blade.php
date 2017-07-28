<store-properties-setter
        properties="{{ json_encode([
                        'key' => 'global',
                        'values' => [
                                'actualLocale' => \Illuminate\Support\Facades\App::getLocale(),
                                'availableLocalesList' => config('codeheuresUtils.availableLocales'),
                                'imageRatio' => floatval(env('IMAGE_RATIO')),
                                'routeHome' => route('home'),
                                'routeCategory' => route('category.index'),
                                'routeCategoryWithCount' => route('category.index', ['count'=> 'true']),
                                'routeListCurrencies' => route('utils.getListCurrencies'),
                                'routeListLocales' => route('utils.getListLocales'),
                                'filterMinLengthSearch' => config('runtime.minLengthSearch'),
                                'routeFacebookSharer' => $routeName == 'advert.show' ?  route('advert.show', ['slug' => $advert->slug, 'lang' => \Illuminate\Support\Facades\App::getLocale()])  : '',
                        ]
                ])}}"
></store-properties-setter>