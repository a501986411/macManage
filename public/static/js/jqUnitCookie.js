/**
 * 基于jquery的cookie插件
 * Created by knight on 2017/5/3.
 */
;(function(){
    $.extend({
        cookie : function(key,value) {
            var cookieStr = document.cookie;
            var cookieArr = cookieStr.split(';');
            var arr = [];
            $.each(cookieArr,function(k,v){
                var str = v.split('=');
                arr[$.trim(str[0])] = str[1];
            });
            if (key != undefined && value != undefined) {//设置cookie

            }
            if (key == undefined && value == undefined) { //获取所有cookie信息
                return arr;
            }
            if (key != undefined && value == undefined) { //获取cookie
                if(cookieArr){
                    return arr[key];
                } else {
                    return null;
                }
            }
        }
    });
})(jQuery,window,document);

