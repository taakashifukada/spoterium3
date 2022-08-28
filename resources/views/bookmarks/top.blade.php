<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Spoterium</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        
        <!-- script -->
    </head>
    <body>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-dropdown-link :href="route('logout')"
                    onclick="event.preventDefault();
                                this.closest('form').submit();">
                ログアウト
            </x-dropdown-link>
        </form>
        <main>
            <p>Toppage</p>
            <a href="/add/url">Bookmarkを追加</a>
            <div class="bookmark_idx">
                <p class="updated_idx">"{{ $bookmark->updated_at}}"</p>
                <div class="imgzone_idx">
                    <img src="{{ $bookmark->img_path }}" class="thumbnail_idx">
                </div>
                <div class="textzone_idx">
                    <a href="{{ $bookmark->url }}" class="title_idx">{{ $bookmark->title }}</a>
                    <p class="comment_idx">{{ $bookmark->comment }}</p>
                    <p class='category_idx'>カテゴリ:{{ $bookmark->category->name }}</p>
                    <div class="tags_idx">
                        タグ:
                        @foreach($bookmark->tags as $tag)
                            {{ $tag->name }}
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="mokuji_idx">
                <p>▼目次</p>
                @foreach($bookmark->contents as $content)
                    <p>{{ $content->contents_index }}</p>
                    <a href="{{ $content->contents_url }}">{{ $content->contents_title }}</a>
                @endforeach
            </div>
            
        </main>
    </body>
</html>