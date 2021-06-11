$(function() {
    var favChoice = new Array();
    var status = new Array();
    var duration = 10000;

    sync();
    //console.log(favChoice);
    
    //favタッチ機能
    $(".fav").change(
        function() {
            var target = $(this).parent().children('span').attr('id');
            var id = Number(target.substr(7)) - 1;
            //console.log(target);

            if (status[id]) {
                status[id] = false;
                favChoice[id] --;
                $('#' + target).html(favChoice[id]);
            }
            else {
                status[id] = true;
                favChoice[id] ++;
                $('#' + target).html(favChoice[id]);
            }
            //console.log(favChoice[id]);

            $.ajax({
                url:'https://hewlive.azurewebsites.net/user_operation.php',
                type:'post',
                data:{
                    'update_channelID':id + 1,
                    'update_fav':favChoice[id]
                }
            });
        }
    );

    //fav同期表示機能
    function sync() {
        $.ajax({
            url:'https://hewlive.azurewebsites.net/user_operation.php',
            type:'post',
            data:{
                'sync_fav':'sync_all'
            }
        })
        .done((data)=>{
            var result = JSON.parse(data);
            //console.log(result);

            for (var i in result) {
                favChoice[i] = result[i]['Favorites'];
                status[i] = false;
                html_id = "#favNum_" + result[i]['channelID'];

                $(html_id).html(result[i]['Favorites']);    
                console.log("Ch:" + result[i]['channelID'] + " fav:" + result[i]['Favorites']); 
            }
        });
      
        setTimeout(function() {
            sync()
        }, duration);
    }

});
