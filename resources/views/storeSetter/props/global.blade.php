<store-properties-setter
        properties="{{ json_encode([
                        'key' => 'global',
                        'values' => [
                                'actualLocale' => \Illuminate\Support\Facades\App::getLocale(),
                                'availableLocalesList' => config('codeheuresUtils.availableLocales'),
                                'imageRatio' => floatval(env('IMAGE_RATIO')),
                                'routeHome' => route('home'),
                                'routeCategory' => route('category.index'),
                                'routeListCurrencies' => route('utils.getListCurrencies'),
                                'routeListLocales' => route('utils.getListLocales'),
                                'filterMinLengthSearch' => config('runtime.minLengthSearch')
                        ]
                ])}}"
></store-properties-setter>