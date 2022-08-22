<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Spoterium</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <form method="POST" action="{{ route('logout') }}" required>
            @csrf
        
            <x-dropdown-link :href="route('logout')"
                    onclick="event.preventDefault();
                                this.closest('form').submit();">
                {{ __('Log out') }}
            </x-dropdown-link>
        </form>
        <p>ブックマークの詳細</p>
        <p>URL:{{ $url }}</p>
        <div id='details_form'>
            <form action="/add/details" method="POST" enctype="multipart/form-data">
                @csrf
                <p class='title'>ブックマークのタイトル</p>
                <input type="text" name="bookmark[title]" placeholder="Title" required />
                <p class='title'>カテゴリ</p>
                <input type="text" name="bookmark[category_name]" placeholder="Category"/>
                <p class='title'>タグ</p>
                <input type="text" name="bookmark[tag_names]" placeholder="スペース区切りでタグを入力"/>
                <p class='title'>サムネイル</p>
                <input type="file" name="img" accept="image/jpg", "image/png" onchange="previewFile(this);">
                <p>プレビュー</p>
                <img id="preview">
                <p class='title'>コメント</p>
                <input type="text" name="bookmark[comment]" placeholder="Comment"/>
                <input type='submit' value="作成"/>
            </form>
            <div class="mokuji_form">
                <form action="/mokuji" method="POST">
                    @csrf
                    <p class='title'>目次を追加</p>
                    <input type="text" name="mokuji_url" placeholder="URL"/>
                    <input type="text" name="mokuji_name" placeholder="目次名"/>
                    <input type='submit' value="追加"/>
                </form>
            </div>
        </div>
        
        <script>
            function previewFile(e){
                //console.log(e.files[0]);
                //fileAPI,fileReaderインスタンス
                var file_data = new FileReader();
                
                //onload:ページやファイルなどのリソースをすべて読み込んでから何らかの処理を行うためのイベントハンドラ―
                //readAsDataURLが完了したら動作する。
                file_data.onload = (function() {
                    //id属性が付与されているimgタグのsrc属性に、fileReaderで取得した値の結果を入力することで
                    //プレビュー表示している
                    //resultプロパティ:読み取り成功後、読み取ったデータ(今回はローカルURL)を取得する
                    document.getElementById('preview').src = file_data.result;
                    console.log(file_data.result);
                });
                //pc上の画像ファイルの場所をローカルURLとして読み込む
                file_data.readAsDataURL(e.files[0]);
            }
        </script>
    </body>
</html>