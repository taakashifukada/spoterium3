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
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.8.4/Sortable.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Vue.Draggable/2.20.0/vuedraggable.umd.min.js"></script>
        
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        
        <!-- script -->
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
        <main>
            <div id='menu_top'>
                
                <draggable tag='ul' class=bookmarks_top id='draggable' v-model='items'>
                    <div v-for='(item,index) in items' v-if='index < 6' :key="item.id">
                        <div class=bookmark_top>
                            <div class='deletefav' @click='doDelete(index)'>x</div>
                            <a :href="item.url" target="_blank" rel="noopener noreferrer">
                                <img :src="item.img_path" class=icon_top>
                            </a>
                            <div class=title_top>@{{ item.title }}</div>
                        </div>
                    </div>
                    <div id='add_favs' v-if='items.length<6' draggable="false">
                        <div id='add_button_top' @click='change_add'>add</div>
                        <ul class=favs_dropdown_add v-if="isOpen" v-for='(fav,index) in filteredFavs'>
                            <li v-text='fav.title' @click="doAdd(index)"></li>
                        </ul>
                    </div>
                </draggable>
                
                <span id=favs_list_top @mouseover='open' @mouseleave='close'>
                    <a href="/folders?folder_id=2" id='link_more_top'>All</a>
                    <ul class=favs_dropdown v-if="isOpen" v-for='fav in favs'>
                        <li><a :href='fav.url' target="_blank" rel="noopener noreferrer" v-text='fav.title'></a></li>
                    </ul>
                </span> 
            </div>
            <div class=history_top>
                <p>更新履歴</p>
                @foreach ($bookmarks_new as $bookmark)
                    <div class=news_top>
                        <a href="{{ $bookmark->url }}" class=news_title_top target="_blank" rel="noopener noreferrer">・{{ $bookmark->title }}</a>
                        <div class=news_time_top>{{ $bookmark->updated_at }}</div>
                    </div>
                @endforeach
            </div>
        </main>
        <!-- bootstrap js -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script>
            new Vue({
                el: '#favs_list_top',
                data: {
                    isOpen: false,
                    favs: @json($bookmarks_fav)
                },
                methods: {
                    open: function(){
                        this.isOpen=true
                    },
                    close: function(){
                        this.isOpen=false
                    }
                }
            });
            
            let id_lis=@json($favs_saved[0]->favs_id);
            console.log(@json($bookmarks_fav));
            let drag = new Vue({
                el: '#draggable',
                data: {
                    favs: @json($bookmarks_fav),
                    items: [],
                    isOpen: false,
                },
                methods: {
                    post: function() {
                        const data = {favs_id: this.items_id};
                        axios
                            .post("/", data)
                            .then((res) => {
                                console.log(res.data);
                            })
                            .catch((err) => {
                                console.log(err);
                            });
                    
                    },
                    doDelete: function(index){
                        this.items.splice(index, 1);
                    },
                    doAdd: function(index){
                        this.items.push(this.filteredFavs[index]);
                    },
                    change_add: function(){
                        this.isOpen= !this.isOpen
                    },
                },
                beforeMount() {
                    if(id_lis != null){
                        id_lis=id_lis.split(',');
                        for (let idx of id_lis){
                            for (let fav of @json($bookmarks_fav)){
                                if(fav.id == idx){
                                    this.items.push(fav);
                                    break;
                                }
                            }
                        }
                    }else{
                        this.items=@json($bookmarks_fav);
                    }
                },
                computed: {
                    items_id: function(){
                        let items_id = [];
                        for (let item of this.items){
                            items_id.push(item.id);
                        }
                        return items_id;
                    },
                    
                    filteredFavs: function(){
                        let favs=this.favs.slice();
                        console.log(this.favs);
                        for (id of this.items_id){
                            for (idx in favs){
                                if(favs[idx].id==id){
                                    favs.splice(idx,1);
                                    break;
                                }
                            }
                        }
                        return favs;
                    }
                },
                watch: {
                    items: function(value){
                        console.log(this.items_id);
                        this.post();
                    }
                }
                
                
            });
        </script>
    </body>
</html>