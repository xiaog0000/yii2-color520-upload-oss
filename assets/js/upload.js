


UP.getUpload = function (id, opt) {

};



$(".colorUpload input").on("click", function(){
    doUpload($(this), $(this).data("server"));
});


function doUpload(self, url) {
    var formData = new FormData();
    var fileObj = self.files[0];
    form.append("file", fileObj);
    $.ajax({
        method: "POST",
        url: url,
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            if (data.code == 0) {

            } else {
            }
        }
    })
}



$("#search_room_input").keyup(function(){
    if($("#search_room_input").val().length==0){
        $("#search_room_list").hide(0);
        return;
    }
    if(sh != null){
        clearTimeout(sh);
    }
    sh = setTimeout(function(){
        post_contact($(this), $("#search_room_list"), 'oc_room', 'id', {'name':$("#search_room_input").val()} , "/common/search");
    }, 500);
});
$("#search_room_list").on("click", "li", function(){
    $("#search_room_input").val($(this).html());
    $("#search_room_list").hide(0);
    $("#room_id").val($(this).data("id"));
});



$("#search_user_input").keyup(function(){
    if($("#search_user_input").val().length==0){
        $("#search_user_list").hide(0);
        return;
    }
    if(sh != null){
        clearTimeout(sh);
    }
    sh = setTimeout(function(){
        post_contact($(this), $("#search_user_list"), 'oc_user', 'id', {'full_name':$("#search_user_input").val(), 'mobile':""} , "/common/search");
    }, 500);
});
$("#search_user_list").on("click", "li", function(){
    $("#search_user_input").val($(this).html());
    $("#search_user_list").hide(0);
    $("#user_id").val($(this).data("id"));
});



$("#search_position_input").keyup(function(){
    if($("#search_position_input").val().length==0){
        $("#search_position_list").hide(0);
        return;
    }
    if(sh != null){
        clearTimeout(sh);
    }
    sh = setTimeout(function(){
        post_contact($(this), $("#search_position_list"), 'oc_position', 'id', {'street':$("#search_position_input").val(), 'district':"", 'custom_desc':""} , "/common/search");
    }, 500);
});

$("#search_position_list").on("click", "li", function(){
    $("#search_position_input").val($(this).html());
    $("#search_position_list").hide(0);
    $("#position_id").val($(this).data("id"));
});



$("#search_house_input").keyup(function(){
    if($("#search_house_input").val().length==0){
        $("#search_house_list").hide(0);
        return;
    }
    if(sh != null){
        clearTimeout(sh);
    }
    sh = setTimeout(function(){
        post_contact($(this), $("#search_house_list"), 'oc_house', 'id', {'name':$("#search_house_input").val()} , "/common/search");
    }, 500);
});

$("#search_house_list").on("click", "li", function(){
    $("#search_house_input").val($(this).html());
    $("#search_house_list").hide(0);
    $("#house_id").val($(this).data("id"));
});

function post_contact(self, list, target_table, target_filed, search_filed, url) {
    $.ajax({
        method: "GET",
        url: url,
        dataType: "json",
        data: {
            target_table:target_table,
            target_filed:target_filed,
            search_filed: search_filed,
            _csrf: $("meta[name=csrf-token]").attr("content")
        },
        success: function (data) {
            if(sh != null){
                clearTimeout(sh);
            }
            if (data.code == 0) {
                list.show(0);
                var contents="";
                var count = data.result.length;
                for(var i=0;i<count;i++){
                    var k = 0 ;
                    for(var key in data.result[i]){
                        var keywords = key;
                        var value = data.result[i][keywords];
                        if(k == 0){
                            contents=contents+"<li class= list_li"+(i+1)+" data-id="+value +">";
                        }else{
                            contents = contents + value + " ";
                        }
                        k++;
                    }
                    contents = contents +"</li>";
                }
                list.html(contents);
            } else {
                list.hide(0);
            }
        }
    })
}
