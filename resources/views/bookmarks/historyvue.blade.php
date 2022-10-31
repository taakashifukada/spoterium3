<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/vue-simple-suggest.css') }}">
        
        
        <!-- bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- bootstrap js -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        
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
        
        <div id="search">
            <div class='fullTextWrap' v-if='fullText' @click.self='backgroundClick'>
                <p class='text_viewer'>@{{ currentText }}</p>
            </div>
                <div>
                    <div v-for="(bookmark, index) in bookmarks" v-if='index < 200' :key="bookmark.id">
                        <div class="bookmark_idx">
                            <p class="updated_idx">@{{bookmark.updated_at}}</p>
                            <div class="del_n_edit">
                                <form class="delete_idx" :action="'/delete/' + bookmark.id" method="POST" onSubmit="return confirmDel()">
                                    @csrf
                                    <button class='del_button_idx' type="submit" >削除</button>
                                </form>
                                <form class="edit_idx" :action="'/edit/' + bookmark.id" method="GET">
                                    @csrf
                                    <button class='edit_button_idx' type="submit" >編集</button>
                                </form>
                            </div>
                            <div class="imgzone_idx">
                                <img :src="bookmark.img_path" class="thumbnail_idx">
                            </div>
                        
                            <div class="textzone_idx">
                                <a :href="bookmark.url" class="title_idx" target="_blank" rel="noopener noreferrer" v-text="bookmark.title"></a>
                                <p class="comment_idx" @click='contentClick(index)' @mouseover="commentMouseover(index)" @mouseout="activeComment=-1" :class="{overed:activeComment===index} ">@{{ bookmark.comment }}</p>
                                <div class='folder_idx'>
                                    フォルダ:<a :href="'/folders?folder_id=' + bookmark.folder_id">@{{ bookmark.folder.name }}</a>
                                </div>
                                <div class="tags_idx">
                                    タグ:
                                    <div v-for="tag in bookmark.tags" :key="tag.id">
                                        <span>
                                            <a :href="'/tags?tag_id=' + tag.id">@{{ tag.name }}</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mokuji_idx" v-if="bookmark.contents.length != 0">
                            <a data-toggle="collapse" :href="'#collapse' + bookmark.id">▼目次</a>
                            <div v-bind:id="'collapse' + bookmark.id" class="panel-collapse collapse">
                                <div v-for="content in bookmark.contents" :key="content.id">
                                    <p>@{{ content.contents_index }}</p>
                                    <a :href=" content.contents_url " target="_blank" rel="noopener noreferrer">@{{ content.contents_title }}</a>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
        </div>
        
        
        <script>
            let a="1 2 3 4 ";
            var separatorString = /\s+/;
            var arrayStrig = a.split(separatorString);
            console.log(arrayStrig);
        </script>
        
        <script src="https://cdn.jsdelivr.net/npm/vue@2.6.11"></script>
        <script type="text/javascript" src="https://unpkg.com/vue-simple-suggest"></script>
        
        
        <script>
            console.log(@json($bookmarks));
            const vue = new Vue({
                el: '#search',
                data: {
                    bookmarks: @json($bookmarks),
                    fullText: false,
                    currentTextIndex: 1,
                    activeComment: -1
                },
                methods: {
                    contentClick: function(index) {
                        let bookmark=this.bookmarks[index];
                        console.log(bookmark.comment);
                        if (bookmark.comment.length>0){
                            this.fullText=true;
                            this.currentTextIndex=index;
                        }
                    },
                    
                    backgroundClick: function(index) {
                        this.fullText=false;
                    },
                    commentMouseover: function(index) {
                        let bookmark=this.bookmarks[index];
                        if (bookmark.comment.length>0){
                            this.activeComment=index;
                        }
                    }
                },
                computed: {
                    currentText() {
                        let bookmark = this.bookmarks[this.currentTextIndex];
                        console.log(bookmark.comment);
                        return bookmark.comment;
                    }
                }
            });
        </script>
    </body>
</html>
