# 概要
ソーシャルブックマークサイト「spoterium」のリポジトリです。
ブラウザのブックマーク機能のブックマークの判別のしづらさに感じていた不満を解消するために作りました。

- タグ+フォルダによる柔軟なブックマークの分類機能
- リアルタイム検索,予測変換,複数キーワード検索,を持つブックマーク検索ページ
- 1つのブックマークに複数のURLを紐づけられる目次機能
- ブックマークへのコメント、アイコン追加機能

等を有し、1年2年前に登録したwebサービスや、タイトルからは内容が判別しづらい論文、個人サイトもキーワードから簡単に発見し、コメントや目次機能から詳しく内容を振り返れるように設計しています。

# サイトURL
https://spoterium.herokuapp.com/

現在メールサーバーを一般に開放していないため、新規のアカウント作成は僕が許可しないとできないようになっています。
新規アカウントを作成したい方、テストアカウントでサイトを試してみたい方は末尾の連絡先までご相談ください。

# サイト構成 - 各ページの説明
### Top
Topページです。「favs」フォルダに登録したブックマークにアクセスできるようになっています。「ALL」からfavsフォルダの全ブックマークにアクセスできるほか、favsフォルダから6つまでブックマークを選んでアイコンで表示できるようになっています。各アイコンのx印をクリックすると選択が解除され「add」から選択しなおすことができます。

### 履歴
更新が新しいものから順に並べられています。50件以上はぺジネイトされます。

### Folders(未実装)
フォルダのエクスプローラ。windowsのエクスプローラ的なのをイメージしています。

### 検索
検索フォームに文字を入力することでリアルタイム検索ができます。またスペース区切りの複数キーワード検索にも対応しています。予測変換は登録しているタグ名、フォルダー名、ブックマーク名(20字以内)から部分一致検索しています。

### NEW
新しいブックマークを追加します。URLを入力し次へを押すと詳細入力画面に遷移し、タイトル、タグ、フォルダ、サムネイル(アイコン)、コメント、目次の編集ができます。

※目次：一つのブックマークに複数のページを追加できる機能。1つのサイトの複数のページを登録したり、1つの調べもので訪問した一連のサイト群をまとめて振り返るために使うことを想定しています。



# 使用技術
### バックエンド
Laravel9(PHP)

### フロントエンド
CSS,HTML,Vue.js(JavaScript)

### 開発環境
AWS(cloud9)

# 進捗
現在コスト削減のため開発環境をAWSからローカルに移行中。
それに伴いコードも整理しようと思っています。(とりあえず作り上げることを優先していたため非常に見づらい...)

### 未実装の機能
- フォルダのエクスプローラ
- コメントの拡大表示機能
- ブックマークアイコン画像の自動リサンプリング(軽量化)
- 検索ページの無限スクロール機能
- ログイン画面のデザイン

# 連絡先
spoterium.test@gmail.com
