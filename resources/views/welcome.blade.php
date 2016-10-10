@extends('layouts.app')

@section('content')
    <div class="ui grid">
        <div class="row">
            <div class="computer only four wide column">
                <category-vertical-menu
                        load-error-message="{{ trans('strings.view_all_error_load_message') }}"
                        route-meta-category="{{ route('metaCategory.index') }}">
                </category-vertical-menu>
             </div>
            <div class="sixteen wide tablet twelve wide computer column">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Beatae cumque ea expedita explicabo fugit inventore ipsa laboriosam minima nisi, quidem? Beatae, et inventore omnis sed soluta tempora tempore unde vitae!</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium atque dolor expedita fugiat itaque laboriosam, magni maiores minus modi, nobis nostrum optio placeat quia quisquam quo rem reprehenderit soluta veniam.</p>
            </div>
        </div>
    </div>
@endsection
