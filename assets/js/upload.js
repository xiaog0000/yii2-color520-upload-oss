$(".colorUpload").on("click", function(){

});

$(".colorUploadButton").on("click", function(){
    var file, fileArray;
    var id, url;

    id = '#'+$(this).parent().prev().children().data("id");
    file = $(this).parent().prev().children();
    fileArray = file[0].files[0];
    url = $(this).parent().prev().children().data("server") + '?action=uploadfile';
    doUpload($(this).parent().prev().children().eq(0), url, fileArray);
});

function doUpload(self, url, fileArray) {
    var self1=self,formData = new FormData();
    formData.append("file", fileArray);
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
                self1.parent().prev().children().val(data.url);
                var a = self.parents('table').eq(0).nextAll('img').eq(0);
                a.attr("src", data.url + '@!w480');
            } else {
                alert("上传失败");
            }
        }
    })
}