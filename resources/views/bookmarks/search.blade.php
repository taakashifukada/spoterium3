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
            
            <div class="search_form">
                <form @submit.prevent>
                    <input type='search' id="search_box" @input="bindKeyword" :value="keyword" @keydown.enter='getSuggestion(0)' autocomplete="off" placeholder="検索ワードで絞込み">
                </form>
                <div class="suggest_panel" v-if="keywordLast.length && open">
                    <ul class="list-group">
                        <li
                            v-for="(suggest, index) in filteredSuggestion"
                            class="list-group-item list-group-item-action"
                            @click='getSuggestion(index)'
                            v-text="suggest"
                        ></li>
                    </ul>
                </div>
            </div>
            
            <div class='fullTextWrap' v-if='fullText' @click.self='backgroundClick'>
                <p class='text_viewer'>@{{ currentText }}</p>
            </div>
            
            <div id='search_tab'>
                <ul class="tab_list">
                    <li @click="isSelect('1')" :class="{'active': isActive == '1'}">ブックマーク</li>
                    <li @click="isSelect('2')" :class="{'active': isActive == '2'}">タグ</li>
                </ul>
            </div>
            <div class="tabContents">
                <div v-if="isActive === '1'">
                    <div v-for="(bookmark, index) in filteredBookmarks" v-if='index < 200' :key="bookmark.id">
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
                <div v-else-if="isActive === '2'">
                    <div v-for="(tag, index) in filteredTags" v-if='index < 1000' :key="tag.id" class="search_tags">
                        <a :href="'/tags?tag_id=' + tag.id">@{{ tag.name }}</a>
                    </div>
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
            const tags_data = @json($tag_names);
            const data2 = @json($folder_names);
            const data3 = @json($bookmark_titles);
            
            const tags=[];
            for (let i=0; i<tags_data.length; i++){
                tags.push(tags_data[i]['name']);
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
        
            const vue = new Vue({
                el: '#search',
                data: {
                    keyword: "",
                    suggestionList: list,
                    bookmarks: @json($bookmarks),
                    isActive: "1",
                    tags: tags_data,
                    fullText: false,
                    currentTextIndex: 1,
                    activeComment: -1
                },
                beforeMount: function() {
                    this.fullText = false;
                },
                methods: {
                    bindKeyword({ target }) {
                        this.keyword =  target.value;
                    },
                    
                    getSuggestion: function(index) {
                        let keywords=this.keywords;
                        keywords[keywords.length - 1]=this.filteredSuggestion[index];
                        let keywordString=keywords.join(" ")+ " ";
                        this.keyword = keywordString;
                    },
                    
                    isSelect: function (num) {
                        this.isActive = num;
                    },
                    
                    contentClick: function(index) {
                        let bookmark=this.filteredBookmarks[index];
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
                        let bookmark=this.filteredBookmarks[index];
                        if (bookmark.comment.length>0){
                            this.activeComment=index;
                        }
                    }
                },
                computed: {
                    filteredBookmarks: function() {
                        let bookmarks = [];
                        
                        for (let i in this.bookmarks) {
                            let bookmark = this.bookmarks[i];
                            
                            let tags="";
                            for (const elem of this.bookmarks[i].tags) {
                                tags = tags + elem.name + " ";
                            }
                            
                            let push_flag=Boolean("true");
                            for (let j in this.keywords){
                                if(bookmark.title.indexOf(this.keywords[j]) == -1 &&
                                    bookmark.folder.name.indexOf(this.keywords[j]) == -1 &&
                                    tags.indexOf(this.keywords[j]) == -1) {
                                    push_flag=Boolean("");
                                }
                            }
                            if (push_flag) {
                                bookmarks.push(bookmark)
                            }
                        }
                        return bookmarks;
                    },
                    
                    filteredTags: function() {
                        let tags = [];
                        
                        for (let i in this.tags) {
                            let tag = this.tags[i];
                            
                            let push_flag=Boolean("true");
                            for (let j in this.keywords){
                                if(tag.name.indexOf(this.keywords[j]) == -1) {
                                    push_flag=Boolean("");
                                }
                            }
                            if (push_flag) {
                                tags.push(tag)
                            }
                        }
                        return tags;
                    },
                        
                    filteredSuggestion: function() {
                        let list = [];
                        
                        for (let i=0; i<this.suggestionList.length; i++) {
                            let suggest = this.suggestionList[i];
                            key_kata=this.keywordLast.replace(/[\u3041-\u3096]/g, function(match) {
                                    var chr = match.charCodeAt(0) + 0x60;
                                    return String.fromCharCode(chr);
                                });
                            key=new RegExp(this.keywordLast, "gi");
                            key_kata=new RegExp(key_kata, "gi");
                            match=key.test(suggest) || key_kata.test(suggest);
                            if(match && suggest.length<20) {
                                list.push(suggest)
                            }
                        }
                        console.log(list);
                        return list;
                    },
                    
                    keywords: function() {
                        let keyword=this.keyword.replace(/　/g, " ");
                        let separatorString = /\s+/;
                        let keywords = keyword.split(separatorString);
                        
                        return keywords;
                    },
                    
                    keywordLast: function() {
                        let keywordLast=this.keywords.slice(-1)[0];
                        
                        return keywordLast;
                    },
                    
                    currentText() {
                        let bookmark = this.filteredBookmarks[this.currentTextIndex];
                        console.log(bookmark.comment);
                        return bookmark.comment;
                    }
                }
            });
        </script>
    </body>
</html>
