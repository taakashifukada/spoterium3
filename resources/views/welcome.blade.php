<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <body>
        <form action="/welcome" method="POST">
            @csrf
            <textarea name="testes"></textarea>
            <div id="mokuji">
                <div v-for="(text,index) in contents_url">
                    <!--入力ボックス-->
                    <input type="text" placeholder="URL" v-model="contents_url[index]" name="url[]" required/>
                    <input type="text" placeholder="Title" v-model="contents_title[index]" name="titile[]" required/>
                    <!--削除ボタン-->
                    <button type="button" @click="removeInput(index)">削除</button>
                </div>
                
                <!-- 入力ボックスを追加するボタン ② -->
                <button type="button" @click="addInput">追加する</button>
            </div>
            <input type='submit' value="作成"/>
        </form>
        
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
