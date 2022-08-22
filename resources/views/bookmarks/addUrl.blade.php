
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Spoterium</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
        
            <x-dropdown-link :href="route('logout')"
                    onclick="event.preventDefault();
                                this.closest('form').submit();">
                {{ __('Log out') }}
            </x-dropdown-link>
        </form>
        <div id='url_form'>
            <form action="/add/url" method="POST">
                @csrf
                <p class='title'>URLを入力してブックマークを作成</p>
                <input type="text" name="bookmark[url]" placeholder="URL"/>
                <input type='submit' value="次へ"/>
            </form>
        </div>
    </body>
</html>
