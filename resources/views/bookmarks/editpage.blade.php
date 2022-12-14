<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Spoterium</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        
        <script src="https://cdn.jsdelivr.net/npm/vue@2.6.11"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
        <p>ブックマークの詳細</p>
        <form action="/edit/{{$bookmark->id}}" id='details_form' method="POST" enctype="multipart/form-data">
            @csrf
            <p class='title'>URL</p>
            <input type="text" class="details_input" name="bookmark[url]" value="{{$bookmark->url}}" >
            <p class='title'>ブックマークのタイトル</p>
            <input type="text" class="details_input" name="bookmark[title]" maxlength=200 placeholder="Title" value="{{$bookmark->title}}" required />
            <p class='title'>フォルダ: favsを指定するとトップページに表示されます。</p>
            <input type="text" class="details_input" name="bookmark[folder_name]" value="{{$bookmark->folder->name}}" maxlength=200 placeholder="Folder"/>
            <p class='title'>タグ</p>
            <input type="text" class="details_input" name="bookmark[tag_names]" maxlength=500 value="{{$tags}}" placeholder="スペース区切りでタグを入力"/>
            <p class='title'>サムネイル</p>
            <input type="file" id='thumbnail_add' name="img" accept="image/jpg", "image/png" onchange="previewFile(this);">
            <p>プレビュー</p>
            <img id="preview">
            <p class='title'>コメント</p>
            <textarea id='add_comment' cols=100 rows=5 name="bookmark[comment]" maxlength=1000 placeholder="Comment">{{$bookmark->comment}}</textarea>
            <input type='submit' value="更新"/>
            
            <div id="mokuji">
                <p class='title'>目次</p>
                <div v-for="(text,index) in contents_url">
                    <!--入力ボックス-->
                    <input type="text" placeholder="URL" v-model="contents_url[index]" name="contents_url[]" required/>
                    <input type="text" placeholder="Title" v-model="contents_title[index]" name="contents_title[]" required/>
                    <!--削除ボタン-->
                    <button type="button" @click="removeInput(index)">削除</button>
                </div>
                
                <!-- 入力ボックスを追加するボタン ② -->
                <button type="button" @click="addInput">追加する</button>
            </div>
        </form>
        
        <script>
            window.onload= function(){
                    document.getElementById('preview').src="{{ $bookmark->img_path }}";
                    document.getElementById('details_form').action="/edit/{{$bookmark->id}}?img_change=0";
                }
            
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
                    document.getElementById('details_form').action="/edit/{{$bookmark->id}}?img_change=1";
                    //console.log(file_data.result);
                });
                //pc上の画像ファイルの場所をローカルURLとして読み込む
                file_data.readAsDataURL(e.files[0]);
            }
        </script>
        
        <script src="https://cdn.jsdelivr.net/npm/vue@2.6.11"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
        <script></script>
        
        <script>
            new Vue({
                el: '#mokuji',
                data: {
                    contents_url: [], // 複数入力のデータ（配列）
                    contents_title: [],
                    old_mokuji: @json($bookmark->contents)
                },
                beforeMount(){
                    for(let i in this.old_mokuji){
                        this.contents_url.push(this.old_mokuji[i].contents_url); // 配列に１つ空データを追加する
                        this.contents_title.push(this.old_mokuji[i].contents_title);
                        
                    }
                },
                methods: {
        
                    // ボタンをクリックしたときのイベント ①〜③
                    removeInput(index) {

                        this.contents_url.splice(index, 1); // 👈 該当するデータを削除(index:削除開始の番号、1:削除する個数)
                        this.contents_titile.splice(index, 1);
            
                    },
                    addInput() {

                        this.contents_url.push(''); // 配列に１つ空データを追加する
                        this.contents_title.push('');
            
                    },
                    
                }
            });
            
        </script>
    
    </body>
</html>