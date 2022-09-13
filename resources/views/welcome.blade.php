<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <!--JavaScript-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js" integrity="sha512-ooSWpxJsiXe6t4+PPjCgYmVfr1NS5QXJACcR/FPpsdm6kqG1FmQ2SVyg2RXeVuCRBLr0lWHnWJP6Zs1Efvxzww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.css" integrity="sha512-+VDbDxc9zesADd49pfvz7CgsOl2xREI/7gnzcdyA9XjuTxLXrdpuz21VVIqc5HPfZji2CypSbxx1lgD7BgBK5g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body>
        <input type="file" name="img" accept="image/jpg", "image/png" onchange="previewFile(this);">
        <img id="cropper-tgt" src="{{ asset('img/test.jpg') }}">
        <div class="control">
          <button type="button" id="btn-crop-action">切り取り</button>
          <label>切り抜き画像ファイル<input type="file" name="cropped-img"></label>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function (){
                // 先程同様に Cropper を準備
                const cropper = new Cropper(
                  document.getElementById('cropper-tgt'),
                  {aspectRatio: 16 / 9}
                );
                document.getElementById('btn-crop-action').addEventListener('click', function(){
                  // Cropper インスタンスから現在の切り抜き範囲の画像を canvas 要素として取れます。
                  /** @var {HTMLCanvasElement} croppedCanvas */
                  const croppedCanvas = cropper.getCroppedCanvas();
                  // canvas 要素には描画されているデータを Blob としてを扱える様にするメソッド toBlob があります。
                  // これを img 要素に渡すことで切り抜き結果を画面に表示できます。
                  // @see https://developer.mozilla.org/ja/docs/Web/API/HTMLCanvasElement/toDataURL
                  croppedCanvas.toBlob(function(imgBlob){
                    // Blob を元に File 化します。
                    const croppedImgFile = new File([imgBlob], '切り抜き画像.png' , {type: "image/png"});
                    // DataTransfer インスタンスを介することで input 要素の　files に
                    // JavaScript 内で作った File を渡せます。
                    // 直に new FileList から作って渡そうとすると失敗します。
                    const dt = new DataTransfer();
                    dt.items.add(croppedImgFile);
                    document.querySelector('input[name="cropped-img"]').files = dt.files;
                  });
                })
              })
        </script>
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
                    document.getElementById('cropper-tgt').src = file_data.result;
                    console.log(file_data.result);
                });
                //pc上の画像ファイルの場所をローカルURLとして読み込む
                file_data.readAsDataURL(e.files[0]);
            }
        </script>
    </body>
</html>
