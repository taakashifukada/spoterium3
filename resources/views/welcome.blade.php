<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    </head>
    <body>
       <img src="http://www.google.com/s2/favicons?domain=https://www.youtube.com/">
        
        <div id="app">
            <p v-bind:id="num"></p>
          @{{ message }}
          @{{ divId }}
          @{{ num }}
          @{{ divId }}
          
        </div>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
        
        <script>
            var app = new Vue({
              el: '#app',
              data: {
                message: 'Hello Vue!',
                num:1
              },
              computed: {
                divId: function()
                {
                    this.num += 1
                    return this.num
                }
              }
            })
        </script>
    </body>
</html>
