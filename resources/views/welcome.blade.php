<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <!--JavaScript-->
        <script src="{{asset('js/suggest.js')}}"></script>
    </head>
    <body>
       <form onsubmit="return false;">
          <table>
            <tr>
              <td>入力:</td>
              <td>
                <!-- 入力フォーム -->
                <input id="text" type="text" name="pattern" value="" autocomplete="off" size="10" style="display: block">
                <!-- 補完候補を表示するエリア -->
                <div id="suggest" style="display:none;"></div>
              </td>
            </tr>
          </table>
        </form>
        
        <script>
            function startSuggest() {
              new Suggest.Local(
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
