@extends('layouts.portal')

@section('content')
    <!-- banner -->
    <div class="ui grid">
        <div class="four wide tablet only four wide computer only column"></div>
        <div class="twelve wide computer only column">
            <div class="ui leaderboard test ad" data-text="728 x 90"></div>
        </div>
        <div class="twelve wide tablet only column">
            <div class="ui banner test ad" data-text="banner"></div>
        </div>
        <div class="sixteen wide mobile only column">
            <div class="ui half banner test centered ad" data-text="Half Banner"></div>
        </div>
    </div>

    <!-- main page -->
    <div class="ui grid">
        <div class="four wide tablet only four wide computer only column">
            <div class="ui small rectangle test ad " data-text="180 x 150"></div>
            <div class="ui small rectangle test ad spaced-top-2" data-text="180 x 150"></div>
            <!--<div class="ui wide skyscraper test ad welcome-ads" data-text="160 x 600"></div>-->
        </div>
        <div class="sixteen wide mobile twelve wide tablet twelve wide computer column">
            <div class="ui segment">
                <div class="ui grid">
                    <div class="sixteen wide column">
                        <div class="ui segment">
                            <h1 class="ui header">Règles de diffusion des annonces</h1>
                            @include('global.tempo.inCreation')
                            @include('global.cg.common.diffusionRules')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection