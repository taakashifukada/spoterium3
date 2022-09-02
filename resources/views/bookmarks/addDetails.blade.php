<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Spoterium</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        
        <script src="https://cdn.jsdelivr.net/npm/vue@2.6.11"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
        
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
        
        <script src="https://cdn.jsdelivr.net/npm/vue@2.6.11"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
        
        <script>
            new Vue({
                el: '#mokuji',
                data: {
                    contents_url: [], // 複数入力のデータ（配列）
                    contents_title: [],
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