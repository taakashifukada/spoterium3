<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Spoterium</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        
        <!-- bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        
        <!-- script -->
    </head>
    <body>
        <div class=header>
            <a class=header_top href="/">Top</a>
            <div class=header_category1>カテゴリ1</div>
            <div class=header_category2>カテゴリ2</div>
            <div class=header_category3>カテゴリ3</div>
            <a class=header_history href="/history">履歴</a>
            <div class=header_folder>Folders</div>
            <a class=header_search href='/search'>検索</a>
            <div class=header_logout>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        ログアウト
                    </x-dropdown-link>
                </form>
            </div>
            <a href="/add/url">NEW</a>
        </div>
        <main>
            <div class=bookmarks_top>
                @for ($i=0; $i<8 && $i<count($bookmarks_fav); $i++)
                    <div class=bookmark_top>
                        <a href="{{ $bookmarks_fav[$i]->url }}">
                            <img src="{{ $bookmarks_fav[$i]->img_path }}" class=icon_top>
                        </a>
                        <div class=title_top>{{ $bookmarks_fav[$i]->title }}</div>
                    </div>
                @endfor
            </div>
            <div class=history_top>
                <p>更新履歴</p>
                @foreach ($bookmarks_new as $bookmark)
                    <div class=news_top>
                        <a href="{{ $bookmark->url }}" class=news_title_top>・{{ $bookmark->title }}</a>
                        <div class=news_time_top>{{ $bookmark->updated_at }}</div>
                    </div>
                @endforeach
            </div>
        </main>
        <!-- bootstrap js -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>
</html>