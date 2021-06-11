$(function() {
    const texts = ["Vedio", "SNS", "Live", "Link", "Festival", "Community"];
    const btn_colors = ["#db954f", "#d37765", "#df6587", "#a166ab", "#5073b8", "#24abc0", "#22bda8", "#78cf8e"];
    var newColor = Array.from(btn_colors);
    const duration = 500;
    var timer;

    //立方体hover
    for (let count = 0 ; count < texts.length ; count++) {
        $(".side").eq(count).html('<div class="text">'+ texts[count] + '</div>');

        $(".side").eq(count).hover(
            function() {
                $('.light').children('style').remove();
                $('.light').append('<style>.light:after { transform: scale(1.8); } </style>');
            },
            function() {
                $('.light').children('style').remove();
            }
        )
    }
    
    //ボタンhover
    $("#FeatBtn").hover( 
        function() {
            $(this).css('transform', 'scale(1.05)');
            //$(this).css('transition-duration', '5s');
            timer = setInterval(
                function () {
                    colorGradation();
                }
            , duration);
        },
        function() {
            $(this).stop();
            clearInterval(timer);
            $(this).css('transform', 'scale(1.0)');

        }
    )

    //ボタングラデーション
    function colorGradation() {
            newColor[8] = newColor[0];
            newColor.shift();
            $('#FeatBtn').children('style').remove();
            $('#FeatBtn').append('<style>#FeatBtn { background: linear-gradient(60deg,'+newColor[0]+','+newColor[1]+','+newColor[2]+','+newColor[3]+','+newColor[4]+','+newColor[5]+','+newColor[6]+','+newColor[7]+'); } </style>');  
    }
});