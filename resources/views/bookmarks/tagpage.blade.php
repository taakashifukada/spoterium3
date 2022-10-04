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
        <script src="{{ asset('js/confirmDel.js') }}"></script>
    </head>
    <body>
        <div class=header>
            <a class=header_top href="/">Top</a>
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
            @foreach($bookmarks as $bookmark)
                <div class="bookmark_idx">
                    <p class="updated_idx">"{{ $bookmark->updated_at}}"</p>
                    <div class="del_n_edit">
                        <form class="delete_idx" action='/delete/{{ $bookmark->id }}' method="POST" onSubmit="return confirmDel()">
                            @csrf
                            <button class='del_button_idx' type="submit" >削除</button>
                        </form>
                        <form class="edit_idx" action='/edit/{{ $bookmark->id }}' method="GET">
                            @csrf
                            <button class='edit_button_idx' type="submit" >編集</button>
                        </form>
                    </div>
                    <div class="imgzone_idx">
                        <img src="{{ $bookmark->img_path }}" class="thumbnail_idx">
                    </div>
                    <div class="textzone_idx">
                        <a href="{{ $bookmark->url }}" class="title_idx">{{ $bookmark->title }}</a>
                        <p class="comment_idx">{{ $bookmark->comment }}</p>
                        <div class='folder_idx'>
                            フォルダ:<a href='/folders?folder_id={{ $bookmark->folder->id}}'>{{ $bookmark->folder->name }}</a>
                        </div>
                        <div class="tags_idx">
                            タグ:
                            @foreach($bookmark->tags as $tag)
                                <span>
                                    <a href="/tags?tag_id={{ $tag->id }}">{{ $tag->name }}</a>
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @if (count($bookmark->contents) != 0)
                    <div class="mokuji_idx">
                        <a data-toggle="collapse" href="#collapse{{$bookmark->id}}">▼目次</a>
                        <div id="collapse{{$bookmark->id}}" class="panel-collapse collapse">
                            @foreach($bookmark->contents as $content)
                                <p>{{ $content->contents_index }}</p>
                                <a href="{{ $content->contents_url }}" target="_blank" rel="noopener noreferrer">{{ $content->contents_title }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
                <hr>
            @endforeach
            
        </main>
        <!-- bootstrap js -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>
</html>