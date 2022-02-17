<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Save</title>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <button onclick="get_games()" class="btn btn-success">Get Games</button>
            <div id="games">

            </div>
        </div>
        <div class="col-md-4">
            <label for="game_id">Oyun IDsını girin </label>
            <input id="game_id" name="game_id" type="text">
            <button class="btn btn-primary" onclick="get_score_board()">Get Score Board </button>
            <div id="games_board">

            </div>
        </div>
        <div class="col-md-4">
            <label for="game_id">Oyun IDsını girin </label>
            <input id="game_ids" name="game_ids" type="text">
            <label for="user_id">User IDsını girin </label>
            <input id="user_id" name="user_id" type="text">
            <br>
            <label for="score">Score </label>
            <input id="score" name="score" type="number">
            <button class="btn btn-primary" onclick="add_score()">Add Score </button>
            <div id="games_boardlist">

            </div>
            <div id="oldrecord">

            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
<script>

    function get_games() {

        $.ajax({
            type: "POST",
            url: "http://127.0.0.1:8000/api/getgames",

            success: function (data) {

                var games="";

                data.item.forEach(function(element) {
                        var game='<div>'+'<b>title:'+element.id+'</b>'+'<span style="padding-left: 10px;">unique users:'+element.total_play_count+'</span>'+'<span style="padding-left: 10px;">total play count:'+element.unique_users+'</span>'+'</div>'+'<hr>';

                         games=games+game;
                    });

                $( "#games" ).append(games);


            },
            error: function () {
                alert("Error");
            }
        })


    }

    function get_score_board(){



        var game_id=document.getElementById('game_id').value
        $.ajax({
            type: "POST",
            url: "http://127.0.0.1:8000/api/getscoreboard",
            data: {"_token": "{{ csrf_token() }}",game_id:game_id},

            success: function (data) {

                var games="";

                data.item.forEach(function(element) {
                    var game='<div>'+'<b>User Id:'+element.user_id+'</b>'+'<span style="padding-left: 10px;">Rank:'+element.rank+'</span>'+'<span style="padding-left: 10px;">Score:'+element.score+'</span>'+'</div>'+'<hr>';

                    games=games+game;
                });

                $( "#games_board" ).append(games);



            },
            error: function () {
                alert("Error");
            }
        })

    }

    function add_score(){

        var score=document.getElementById('score').value
        var user_id=document.getElementById('user_id').value
        var game_id=document.getElementById('game_ids').value
        $.ajax({
            type: "POST",
            url: "http://127.0.0.1:8000/api/addscore",
            data: {"_token": "{{ csrf_token() }}",game_id:game_id,user_id:user_id,score:score},

            success: function (data) {
                var games;
                if(data.item===false){
                    games='<div>'+'<b>you failed</b>';
                }else{

                    games='<div>'+'<b>Game Id:'+data.item.game_id+'</b>'+'<span style="padding-left: 10px;">User Id:'+data.item.user_id+'</span>'+'<span style="padding-left: 10px;">Score:'+data.item.score+'</span>'+'</div>'+'<hr>';
                var record='<h3 style="padding-left: 10px;"> New Record:'+data.item.rank+'</h3>'+'<h3 style="padding-left: 10px;">Old record:'+data.oldrecord+'</h3>';
                $( "#oldrecord" ).append(record);
                }
                $( "#games_boardlist" ).append(games);
            },
            error: function () {
                alert("Error");
            }
        })

    }
</script>
</html>
