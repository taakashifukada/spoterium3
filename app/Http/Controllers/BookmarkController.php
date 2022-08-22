<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bookmark;
use App\Models\Category;
use App\Models\Tag;

use Illuminate\Support\Facades\Storage;

class BookmarkController extends Controller
{
    //Bookmarkモデルのインスタンスを$bookmarkに格納
    //
    public function topPage(Bookmark $bookmark)
    {
        //dd($bookmark->getPaginateByLimit()->where('user_id',Auth::user()->id)->first());
        return view('bookmarks.top')->with(['bookmark'=>$bookmark->getPaginateByLimit()->where('user_id',Auth::user()->id)->first()]);
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
        session()->put(['url' => $input['url']]);
        
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
        return view('bookmarks.addDetails')->with(['url' => $url]);
    }
    
    public function storeBookmark(Request $request, Bookmark $bookmark)
    {
        $input = $request['bookmark'];
        $category_name=$input['category_name'];
        //Categoryの存在確認->なければ追加
        if($category_name!=null){
            if(\DB::table('categories')->where('name', $category_name)->exists() != True){
                $category = New Category();
                $category->name=$category_name;
                $category->save();
            };
            //\App\Category = DB::table(categories)
            $input['category_id']=\App\Models\Category::where('name',$input['category_name'])->first()->id;
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
        $path = Storage::disk('s3')->putFile('thumbnails', $request->file('img'),'public');
        $full_path = Storage::disk('s3')->url($path);
        $input['img_path']=$full_path;
        
        //セッションからurlを取得
        $url = session()->get('url');
        $input['url']=$url;
        
        $input['user_id']=Auth::user()->id;
        //dd($tag_ids);
        $bookmark->fill($input)->save();
        
        $bookmark->tags()->attach($tag_ids);
        return redirect('/');
    }
}
