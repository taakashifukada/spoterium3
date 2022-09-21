<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bookmark;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Content;
use App\Models\Folder;
use App\Models\User;

use Illuminate\Support\Facades\Storage;

class BookmarkController extends Controller
{
    //Bookmarkモデルのインスタンスを$bookmarkに格納
    //
    public function welcome()
    {
        //タイトルを取得したいURL
        $url = 'https://www.youtube.com/';
         
        //ソースの取得
        $source = @file_get_contents($url);
        //文字コードをUTF-8に変換し、正規表現でタイトルを抽出
        if (preg_match('/<title>(.*?)<\/title>/i', mb_convert_encoding($source, 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS'), $result)) {
            $title = $result[1];
        } else {
            //TITLEタグが存在しない場合
            $title = null;
        }
        echo $title;
        return view('welcome');
    }
    
    public function search()
    {
        $tag_names=Tag::with(['bookmarks'])->whereHas('bookmarks', function($query) {
            $query->where('user_id',Auth::user()->id);
        })->select('id','name')->orderBy('name')->get()->toArray();
        
        $folder_names=Folder::with(['bookmarks'])->whereHas('bookmarks', function($query) {
            $query->where('user_id',Auth::user()->id);
        })->select('name')->get()->toArray();
        
        $bookmark_titles=Bookmark::where('user_id',Auth::user()->id)->select('title')->get()->toArray();
        
        $bookmarks=Bookmark::with(['category', 'tags', 'contents','folder'])->where('user_id',Auth::user()->id)->orderBy('title')->get();
        
        return view('bookmarks.search')->with(['tag_names'=>$tag_names,'folder_names'=>$folder_names,'bookmark_titles'=>$bookmark_titles,"bookmarks"=>$bookmarks]);
    }
    
    public function toppage(Bookmark $bookmark)
    {
        $favs_saved=User::where('id',Auth::user()->id)->select('favs_id')->get();
        $bookmarks_fav=Bookmark::with(['category', 'tags', 'contents','folder'])->where('folder_id','=',2)
        ->where('user_id',Auth::user()->id)->orderby('updated_at', 'desc')->get();
        
        $bookmarks_new=Bookmark::where('user_id',Auth::user()->id)->orderby('updated_at', 'desc')->limit(5)->get();

        return view('bookmarks.top')->with(['bookmarks_fav'=>$bookmarks_fav,'bookmarks_new'=>$bookmarks_new, 'favs_saved'=>$favs_saved]);
    }
    
    public function saveFavsTop(Request $request)
    {
        $ids=request()->all();
        $str_ids=implode(",",$ids['favs_id']);
        User::where('id',Auth::user()->id)->update([
                "favs_id"=> $str_ids,
            ]);
        
    }
    
    public function historyPage(Bookmark $bookmark)
    {
        //dd($bookmark->getPaginateByLimit()->where('user_id',Auth::user()->id)->first());
        return view('bookmarks.history')->with(['bookmarks'=>$bookmark->getPaginateByLimit()->where('user_id',Auth::user()->id)]);
    }
    
    public function showTagpage(Request $request, Bookmark $bookmark)
    {
        $tid=$request->tag_id;
        $bookmarks=Bookmark::with(['category', 'tags', 'contents'])->whereHas('tags', function($query) use ($request){
            $query->where('id','=',$request->tag_id);
        })->where('user_id',Auth::user()->id)->orderby('updated_at', 'DESC')->paginate(20);
        //dd($bookmarks);
        return view('bookmarks.tagpage')->with(['bookmarks'=>$bookmarks]);
    }
    
    public function showCategorypage(Request $request, Bookmark $bookmark)
    {
        $bookmarks=Bookmark::with(['category', 'tags', 'contents'])->where('category_id','=',$request->category_id)
        ->where('user_id',Auth::user()->id)->orderby('updated_at', 'DESC')->paginate(20);
        //dd($bookmarks);
        return view('bookmarks.tagpage')->with(['bookmarks'=>$bookmarks]);
    }
    
    public function showFolderpage(Request $request, Bookmark $bookmark)
    {
        $bookmarks=Bookmark::with(['category', 'tags', 'contents','folder'])->where('folder_id','=',$request->folder_id)
        ->where('user_id',Auth::user()->id)->orderby('updated_at', 'DESC')->paginate(20);
        //dd($bookmarks);
        return view('bookmarks.folderpage')->with(['bookmarks'=>$bookmarks]);
    }
    
    public function addUrl()
    {
        return view('bookmarks.addUrl');
    }
    
    public function storeUrl(Request $request, Bookmark $bookmark)
    {
        $input=$request['bookmark'];
        //storage/framework/sessions/ディレクトリ内のファイルにセッションを保存
        //sessionのkey'url'の値を更新
         
        //ソースの取得
        $source = @file_get_contents($input['url']);
        //文字コードをUTF-8に変換し、正規表現でタイトルを抽出
        if (preg_match('/<title>(.*?)<\/title>/i', $source, $result)) {
            $title = $result[1];
        } else {
            //TITLEタグが存在しない場合
            $title = '';
        }
        echo $title;
        
        //storage/framework/sessions/ディレクトリ内のファイルにセッションを保存
        //sessionのkey'url'の値を更新
        session()->put(['url' => $input['url'], 'title' => $title]);
        
        return redirect('/add/details');
    }
    
    public function storeContent(Request $request, Bookmark $bookmark)
    {
        $input=$request['mokuji_url'];
        //storage/framework/sessions/ディレクトリ内のファイルにセッションを保存
        //sessionのkey'url'の値を更新
        session()->put(['mokuji' => $input['mokuji_url']]);
        
        return redirect('/add/details');
    }
    
    public function addDetails(Bookmark $bookmark)
    {
        $url = session()->get('url');
        $title = session()->get('title');
        return view('bookmarks.addDetails')->with(['url' => $url, 'title' => $title]);
    }
    
    public function storeBookmark(Request $request, Bookmark $bookmark)
    {
        $input = $request['bookmark'];
        
        //folderの管理
        if(isset($input["folder_name"])){
            $folder_name=$input['folder_name'];
            if(\DB::table('folders')->where('name', $folder_name)->exists() != True){
                $folder = New Folder();
                $folder->name=$folder_name;
                $folder->save();
            };
            //\App\Category = DB::table(categories)
            $input['folder_id']=\App\Models\Folder::where('name',$input['folder_name'])->first()->id;
        };
        
        //Tagの整理
        $tag=mb_convert_kana($input['tag_names'],'s','utf-8');
        $tags=preg_split('/[\s]+/', $tag, -1, PREG_SPLIT_NO_EMPTY);
        $tag_ids=[];
        foreach($tags as $tag_name){
            if(\DB::table('tags')->where('name', $tag_name)->exists() != True){
                $tag = New Tag();
                $tag->name=$tag_name;
                $tag->save();
            };
            $tag_ids[]=\App\Models\Tag::where('name',$tag_name)->first()->id;
        };
        
        //画像をs3バケットに保存&パスを取得
        //s3のthumbnailsフォルダに保存
        //dd($request['img']);
        if ($request['img']==null){
            $input['img_path']='https://spoterium-imgs.s3.amazonaws.com/prepared/noimage.jpg';
        }else{
            $path = Storage::disk('s3')->putFile('thumbnails', $request->file('img'),'public');
            $full_path = Storage::disk('s3')->url($path);
            $input['img_path']=$full_path;
        }
        
        //目次の有無
        if($request->has('contents_url')){
            $input['contents_flag']=1;
        }
        
        //セッションからurlを取得
        $url = session()->get('url');
        $input['url']=$url;
        
        $input['user_id']=Auth::user()->id;
        //dd($tag_ids);
        $bookmark->fill($input)->save();
        
        //目次の処理
        if($request->has('contents_url')){
            $contents_url=$request['contents_url'];
            $contents_title=$request['contents_title'];
            for($i=0; $i<count($contents_url); $i++){
                $content=New Content();
                $content->contents_url=$contents_url[$i];
                $content->contents_title=$contents_title[$i];
                $content->contents_index=$i+1;
                $content->bookmark_id=\App\Models\Bookmark::where('user_id',Auth::user()->id)->latest('id')->first()->id;
                $content->save();
            }
        }
        
        $bookmark->tags()->attach($tag_ids);
        return redirect('/history');
    }
}
