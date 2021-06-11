$(function() {
    'use strict';

    //side_menu part for all
    $("#open").click(
        function() {
            $("#sidemenu").toggleClass('hidden');
        }
    )

    //personal_information part for all
    $("#person").hover(
        function() {
            $(".personal_info").stop().slideToggle(250);
        }
    )


    //login,signin part
    var flg_login = [1, 1];
    $('input[name="login"]').prop('disabled', true);
    $('input[name="login"]').append('<style>input[name="login"] { background: gray; cursor: default;} </style>');
    $('.input_login').change(//"keyup blur",
        function () {
            var name = $(this).attr('name');
            var input_name = 'input[name="' + name + '"]';
            const style_str_1 = '<style>';
            const style_str_2 = ' { background-image: url("./image/mark_batsu.png"); background-size: 15px; background-repeat: no-repeat; background-position: right;} </style>';
            const style_str_3 = ' { background-image: url("./image/mark_ok.png"); background-size: 15px; background-repeat: no-repeat; background-position: right; } </style>';
            $(input_name).children('style').remove();
            var input_val = $(this).val().replace(/\s+/g, "");

            switch (name) {
                case 'UserID':
                    if (input_val.match(/^[0-9]{1,12}$/) && input_val.length > 0) {
                        $(input_name).append(style_str_1 + input_name + style_str_3);
                        flg_login[0] = 0;
                    }
                    else {
                        $(input_name).append(style_str_1 + input_name + style_str_2);
                        flg_login[0] = 1;        
                    }
                    break;

                case 'Password':
                    if (input_val.match(/^[A-Za-z0-9]{1,36}$/) && input_val.length > 0) {
                        $(input_name).append(style_str_1 + input_name + style_str_3);
                        flg_login[1] = 0;
                    }
                    else {
                        $(input_name).append(style_str_1 + input_name + style_str_2);
                        flg_login[1] = 1;
                    }
                    break;

                default:
                    break;
            }
            //console.log(flg_login);
            if($.inArray(1, flg_login) != -1) {
                $('input[name="login"]').prop('disabled', true);
                if ($('input[name="login"]').find('style').length <= 0) {
                    $('input[name="login"]').append('<style>input[name="login"] { background: gray; cursor: default; }</style>');
                }
            }
            else {
                $('input[name="login"]').prop('disabled', false);
            }
        }
    )

    //login,signin part
    var flg_create = [1, 1, 1];
    $('input[name="create"]').prop('disabled', true);
    $('input[name="create"]').append('<style>input[name="create"] { background: gray; cursor: default;} </style>');
    $('.input_signin').change(//"keyup blur",
        function () {
            var name = $(this).attr('name');
            var input_name = 'input[name="' + name + '"]';
            const style_str_1 = '<style>';
            const style_str_2 = ' { background-image: url("./image/mark_batsu.png"); background-size: 15px; background-repeat: no-repeat; background-position: right;} </style>';
            const style_str_3 = ' { background-image: url("./image/mark_ok.png"); background-size: 15px; background-repeat: no-repeat; background-position: right; } </style>';
            $(input_name).children('style').remove();
            var input_val = $(this).val().replace(/\s+/g, "");

            switch (name) {
                case 'UserName':
                    if (input_val.length > 0 && input_val.length <= 16) {
                        $(input_name).append(style_str_1 + input_name + style_str_3);
                        flg_create[0] = 0;
                    }
                    else {
                        $(input_name).append(style_str_1 + input_name + style_str_2);
                        flg_create[0] = 1;        
                    }
                    break;

                case 'Password':
                    if (input_val.match(/^[A-Za-z0-9]{1,36}$/) && input_val.length > 0) {
                        $(input_name).append(style_str_1 + input_name + style_str_3);
                        flg_create[1] = 0;
                    }
                    else {
                        $(input_name).append(style_str_1 + input_name + style_str_2);
                        flg_create[1] = 1;
                    }
                    break;

                case 'Email':
                    if (input_val.match(/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/) && input_val.length > 0) {
                        $(input_name).append(style_str_1 + input_name + style_str_3);
                        flg_create[2] = 0;
                    }
                    else {
                        $(input_name).append(style_str_1 + input_name + style_str_2);
                        flg_create[2] = 1;
                    }
                    break;

                default:
                    break;
            }
            console.log(flg_create);
            if($.inArray(1, flg_create) != -1) {
                $('input[name="create"]').prop('disabled', true);
                if ($('input[name="create"]').find('style').length <= 0) {
                    $('input[name="create"]').append('<style>input[name="create"] { background: gray; cursor: default; }</style>');
                }
            }
            else {
                $('input[name="create"]').prop('disabled', false);
            }
        }
    )

    //subscribe part
    //var linkHtml = document.getElementById("linkHtml"); 
    $(".plan_choice").click(
        function() {
            var plan = $(this).prev().children('div h3').html();
            plan = plan.substr(1);
            $("#plice").html(plan);
            $("#mask").removeClass();
            $("#modal").removeClass();
        }
    )

    $("#close").click(
        function() {
            $("#plice").html('');
            $("#mask").addClass('hidden');
            $("#modal").addClass('hidden');
        }
    )

    //payment part
    var choice_payment;
    $(".radio input").click(
        function() {
            if($(this).val() == choice_payment) {
                $(this).prop('checked', false);
                choice_payment = 'pay';
            }
            else {
                choice_payment = $(this).val();
            }
            //$(".submit input").attr('name', choice_payment);
            if (choice_payment == 'credit') {
                $(".billing_info").find('input').prop('disabled', true);
                $(".creditcard_info").find('input').prop('disabled', false);
                flg_p_check();
                if (flg_status == -1) {
                    $(".submit input").prop('disabled', false);
                    $(".submit input").children('style').remove();
                }     
            }
            else if (choice_payment == 'paypal' || choice_payment == 'amazon') {
                $(".info_area").find('input').prop('disabled', true);
                if (flg_status == -1) {
                    $(".submit input").prop('disabled', true);
                    $(".submit input").append('<style>.submit input { background: gray; cursor: default;} </style>');
                }
            }
            else {
                $(".info_area").find('input').prop('disabled', false);
                flg_p_check();
                if (flg_status == -1) {
                    $(".submit input").prop('disabled', false);
                    $(".submit input").children('style').remove();
                }
            }
        }
    )

    //入力フォームチェック part
    var flg_p = [1, 1, 1, 1, 1, 1, 1, 1, 1];
    var flg_status;
    $(".submit input").prop('disabled', true);
    $(".submit input").append('<style>.submit input { background: gray; cursor: default;} </style>');
    $('.input_payment').change(//"keyup blur",
        function () {
            var name = $(this).attr('name');
            var input_name = 'input[name="' + name + '"]';
            const style_str_1 = '<style>';
            const style_str_2 = ' { background-image: url("./image/mark_batsu.png"); background-size: 15px; background-repeat: no-repeat; background-position: right;} </style>';
            const style_str_3 = ' { background-image: url("./image/mark_ok.png"); background-size: 15px; background-repeat: no-repeat; background-position: right; } </style>';
            $(input_name).children('style').remove();

            /*
            String.prototype.trim = function () {
                return this.replace(/^[\s\xA0]+|[\s\xA0]+$/g, '');
            };
            var input_val = $(this).val().trim();
            */
            var input_val = [];
            input_val[1] = $(this).val();
            input_val[2] = $(this).val().replace(/\s+/g, "");
            
            switch (name) {
                case 'fullname':
                    if (input_val[1].match(/^[A-Z\s]{1,32}$/) && input_val[2].length > 0) {
                        $(input_name).append(style_str_1 + input_name + style_str_3);
                        flg_p[0] = 0;
                    }
                    else {
                        $(input_name).append(style_str_1 + input_name + style_str_2);
                        flg_p[0] = 1;        
                    }
                    break;

                case 'prefecture':
                    if (input_val[1].match(/^[A-Za-z\s]{1,16}$/) && input_val[2].length > 0) {
                        $(input_name).append(style_str_1 + input_name + style_str_3);
                        flg_p[1] = 0;
                    }
                    else {
                        $(input_name).append(style_str_1 + input_name + style_str_2);
                        flg_p[1] = 1;
                    }
                    break;

                case 'city':
                    if (input_val[1].match(/^[A-Za-z\s]{0,32}$/) && input_val[2].length > 0) {
                        $(input_name).append(style_str_1 + input_name + style_str_3);
                        flg_p[2] = 0;
                    }
                    else {
                        $(input_name).append(style_str_1 + input_name + style_str_2);
                        flg_p[2] = 1;
                    }
                    break;
                    
                case 'zipcode':
                    if (input_val[1].match(/^[0-9]{7}$/) && input_val[2].length > 0) {
                        $(input_name).append(style_str_1 + input_name + style_str_3);
                        flg_p[3] = 0;
                    }
                    else {
                        $(input_name).append(style_str_1 + input_name + style_str_2); 
                        flg_p[3] = 1;           
                    }
                    break;    
                    
                case 'address':
                    if (input_val[2].length > 0) {
                        $(input_name).append(style_str_1 + input_name + style_str_3);
                        flg_p[4] = 0;
                    }
                    else {
                        $(input_name).append(style_str_1 + input_name + style_str_2);
                        flg_p[4] = 1;         
                    }
                    break;

                case 'card_number':
                    if (input_val[1].match(/^[0-9]{16}$/) && input_val[2].length > 0) {
                        $(input_name).append(style_str_1 + input_name + style_str_3);
                        flg_p[5] = 0;
                    }
                    else {
                        $(input_name).append(style_str_1 + input_name + style_str_2);
                        flg_p[5] = 1;           
                    }
                    break;

                case 'expiredate':
                    if (input_val[1].match(/^[0-9]{6}$/) && input_val[2].length > 0) {
                        $(input_name).append(style_str_1 + input_name + style_str_3);
                        flg_p[6] = 0;
                    }
                    else {
                        $(input_name).append(style_str_1 + input_name + style_str_2);
                        flg_p[6] = 1;         
                    }
                    break;

                case 'holdername':
                    if (input_val[1].match(/^[A-Z\s]{1,32}$/) && input_val[2].length > 0) {
                        $(input_name).append(style_str_1 + input_name + style_str_3);
                        flg_p[7] = 0;
                    }
                    else {
                        $(input_name).append(style_str_1 + input_name + style_str_2);
                        flg_p[7] = 1;        
                    }
                    break;

                case 'card_cvv':
                    if (input_val[1].match(/^[0-9]{3}$/) && input_val[2].length > 0) {
                        $(input_name).append(style_str_1 + input_name + style_str_3);
                        flg_p[8] = 0;
                    }
                    else {
                        $(input_name).append(style_str_1 + input_name + style_str_2);
                        flg_p[8] = 1;       
                    }
                    break;

                default:
                    break;
            }
     
            flg_p_check();
            //console.log(flg_p);
        }
    )
    
    function flg_p_check() {
        if (choice_payment == 'credit') {
            flg_status = $.inArray(1, flg_p, 5);
        }
        else {
            flg_status = $.inArray(1, flg_p);
        }

        console.log(flg_p);
        if(flg_status != -1) {
            $(".submit input").prop('disabled', true);
            if ($(".submit input").find('style').length <= 0) {
                $(".submit input").append('<style>.submit input { background: gray; cursor: default;} </style>');
            }    
        }
        else {
            $(".submit input").prop('disabled', false);
            $(".submit input").children('style').remove();
        }      
    }
});
