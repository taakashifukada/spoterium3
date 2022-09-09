<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <!--JavaScript-->
        <script src="{{asset('js/suggest.js')}}"></script>
    </head>
    <body>
        <div id="search">
            <form onsubmit="return false;">
              <table>
                <tr>
                  <td>検索:</td>
                  <td>
                    <!-- 入力フォーム -->
                    <input id="text" type="text" name="pattern" v-model="keyword" autocomplete="off" size="10" style="display: block">
                    <!-- 補完候補を表示するエリア -->
                    <div id="suggest" style="display:none;"></div>
                  </td>
                </tr>
              </table>
            </form>
            
            <div v-for="bookmark in filteredBookmarks" :key="bookmark.id">
                <a :href="bookmark.url" v-text="bookmark.title"></a>
                フォルダ:<a :href="'/folders?folder_id=' + bookmark.folder.id">@{{ bookmark.folder.name }}</a>
                
            </div>
        </div>
        <script>
            let inp = document.getElementById("text");
            console.log(inp.value);
        </script>
        
        <script src="https://cdn.jsdelivr.net/npm/vue@2.6.11"></script>
        <script>
            console.log(@json($bookmarks));
        
            const vue = new Vue({
                el: '#search',
                data: {
                    keyword: "test",
                    bookmarks: @json($bookmarks),
                },
                computed: {
                    filteredBookmarks: function() {
                        let bookmarks = [];
                        
                        for (let i in this.bookmarks) {
                            let bookmark = this.bookmarks[i];
                            
                            if(bookmark.title.indexOf(this.keyword) !== -1 ||
                                bookmark.folder.name.indexOf(this.keyword) !== -1) {
                                bookmarks.push(bookmark)
                            }
                        }
                        
                        return bookmarks;
                    }
                }
            });
        </script>
        
        <script>
            const data = @json($tag_names);
            const data2 = @json($folder_names);
            const data3 = @json($bookmark_titles);
            
            const tags=[];
            for (let i=0; i<data.length; i++){
                tags.push(data[i]['name']);
            };
            
            const folders=[];
            for (let i=0; i<data2.length; i++){
                folders.push(data2[i]['name']);
            }
            
            const bookmarks=[];
            for (let i=0; i<data3.length; i++){
                bookmarks.push(data3[i]['title']);
            }
            
            let list=[];
            list=list.concat(tags,folders,bookmarks);
        
            function startSuggest() {
              new Suggest.LocalMulti(
                    "text",    // 入力のエレメントID
                    "suggest", // 補完候補を表示するエリアのID
                    list,      // 補完候補の検索対象となる配列
                    {dispMax: 10, interval: 1000}); // オプション
            }
            
            window.addEventListener ?
              window.addEventListener('load', startSuggest, false) :
              window.attachEvent('onload', startSuggest);
        </script>
    </body>
</html>
