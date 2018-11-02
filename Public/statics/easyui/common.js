/**
 * Created by Liarman on 2017/9/7.
 */
function closes(){
    $("#Loading").fadeOut("fast",function(){
        $(this).remove();
    });
}
var pc;
$.parser.onComplete = function(){
     if(pc) clearTimeout(pc);
    pc = setTimeout(closes, 500);
}

//图片添加路径
function imgFormatter(value,row,index){
    if('' != value && null != value){
        var strs = new Array(); //定义一数组
        if(value.substr(value.length-1,1)==","){
            value=value.substr(0,value.length-1)
        }
        strs = value.split(","); //字符分割
        var rvalue ="";
        for (i=0;i<strs.length ;i++ ){
            rvalue += "<img onclick=showimg(\""+strs[i]+"\") style='width:66px; height:60px;margin-left:3px;' src='" + strs[i] + "' title='点击查看图片'/>";
        }
        return  rvalue;
    }
}
//这里需要自己定义一个div   来创建一个easyui的弹窗展示图片
function showimg(img){
    var simg =  img;
    $('.imgdlg').dialog({
        title: '预览',
        width: 400,
        height:400,
        resizable:true,
        closed: false,
        cache: false,
        modal: true
    });
    $(".simg").attr("src",simg);

}
function resetFileInput(file){
    file.after(file.clone().val("")); file.remove();
}
// $.fn.uploadFiles = function(b, g) {
//     var a = $(this);
//     var f = {
//         btnText: "添加",
//         describe: false,
//         link: false,
//         files: []
//     };
//     var d = $.extend({},
//         f, b);
//     a.data("files", []);
//     a.addClass("upload-files-box").html('<span class="btn btn-default mR5 add-btn">' + d.btnText + '</span><input type="file" class="hide"><div class="file-show"></div>');
//     if (d.files.length > 0) {
//         $.each(d.files,
//             function(j, k) {
//                 var h = k.describe ? k.describe: k.substring(k.lastIndexOf("/") + 1);
//                 e(h, k);
//             });
//         a.data("files", d.files);
//     }
//     function e(h, i) {
//         var j = a.data("files");
//         j.push(i);
//         a.data("files", j);
//         a.find("div.file-show").append('<span class="tag">' + h + '<i class="fa fa-close"></i></span>');
//     }
//     a.on("click", "span.add-btn",
//         function() {
//             if (d.describe) {
//                 var h = '<div class="modal inmodal fade" id="addFilesModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">' +
//                     '<div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">' +
//                     '<span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button><h6 class="modal-title">添加资料</h6></div>' +
//                     '<div class="modal-body"><p class="text-danger"><i class="fa fa-exclamation-circle"></i> 请注意，“从本地上传”和“资料地址”任选其一<br/>&nbsp;&nbsp;&nbsp;&nbsp;支持小于10M的Word、PowerPoint和PDF，小于5M的Excel</p>' +
//                     '<div class="form-group note-form-group note-group-select-from-files"><label class="note-form-label">显示文本</label>' +
//                     '<input class="note-image-input form-control note-form-control note-input" type="text" id="sourceName"></div><div class="form-group note-form-group note-group-select-from-files">' +
//                     '<label class="note-form-label">从本地上传</label><input class="note-image-input form-control note-form-control note-input" type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-powerpoint,application/vnd.ms-excel,application/msword,application/pdf" name="files" id="sourceIpt"></div>' +
//                     '<div class="form-group note-group-image-url" style="overflow:auto;"><label class="note-form-label">资料地址</label><input class="note-image-url form-control note-form-control note-input  col-md-12" type="text" id="sourceLink">' +
//                     '<div class="clearfix"></div><span class="help-block">输入格式如：https://www.baidu.com/ 或 http://www.baidu.com/</span></div></div><div class="modal-footer"><button id="writeSelectFile" class="btn btn-primary note-btn note-btn-primary note-image-btn">插入资料</button></div></div></div></div>';
//                 top.$("body").append(h);
//                 top.$("#addFilesModal").modal("show").data({}).on("hide.bs.modal",
//                     function(k) {
//                         var i = k.target.id;
//                         if (i == "addFilesModal") {
//                             var j = top.$("#addFilesModal");
//                             j.remove();
//                         }
//                     }).on("shown.bs.modal",
//                     function(j) {
//                         var i = $(j.target);
//                         i.find("#sourceIpt").on("change",
//                             function() {
//                                 var m = $(this),
//                                     l = m[0].files[0],
//                                     p = l.name,
//                                     k = l.size,
//                                     n = /.*.xlsx$|.*.xls$|.*.XLSX$|.*.XLS$/,
//                                     o = /.*.docx$|.*.doc$|.*.xlsx$|.*.xls$|.*.pptx$|.*.ppt$|.*.pdf$|.*.DOCX$|.*.DOC$|.*.XLSX$|.*.XLS$|.*.PPTX$|.*.PPT$|.*.PDF$/;
//                                 if (!o.test(p)) {
//                                     common.nomalPrompt("请上传word、excel、ppt、pdf类型的文件");
//                                     m.val("");
//                                     return;
//                                 }
//                                 if (n.test(p) && k > 1024 * 1024 * 5) {
//                                     common.nomalPrompt("Excel不能超过5M");
//                                     m.val("");
//                                     return true;
//                                 }
//                                 if (!n.test(p) && k > 1024 * 1024 * 10) {
//                                     common.nomalPrompt("Word、PowerPoint和PDF不能超过10M");
//                                     m.val("");
//                                     return true;
//                                 }
//                                 i.find("#sourceLink").attr("disabled", "disabled");
//                                 c(l,
//                                     function(r, q) {
//                                         i.data("file", q);
//                                     });
//                             });
//                         i.find("#sourceLink").on("keyup",
//                             function() {
//                                 $(this).val() ? i.find("#sourceIpt").attr("disabled", "disabled") : i.find("#sourceIpt").removeAttr("disabled");
//                             });
//                         i.find("#writeSelectFile").on("click",
//                             function() {
//                                 var l = i.find("#sourceName").val(),
//                                     k = i.data("file"),
//                                     n = i.find("#sourceLink").val();
//                                 if (!l) {
//                                     common.nomalPrompt("请输入显示文本");
//                                     return;
//                                 }
//                                 var m = /^[a-zA-z]+:\/\/[^\s]*$/;
//                                 if (n && !m.test(n)) {
//                                     common.nomalPrompt("资料地址格式不正确");
//                                     return;
//                                 }
//                                 if (!k && !n) {
//                                     common.nomalPrompt("请上传资料或者填写资料地址");
//                                     return;
//                                 }
//                                 top.$("#addFilesModal").modal("hide");
//                                 e(l, {
//                                     describe: l,
//                                     url: k ? k: n
//                                 });
//                             });
//                     });
//             } else {
//                 a.find("input").click();
//             }
//         });
//     a.on("change", "input",
//         function() {
//             var h = $(this),
//                 i = h[0].files;
//             if (i.length > 0) {
//                 c(i[0],
//                     function(k, j) {
//                         e(k, j);
//                         h.val("");
//                     });
//             }
//         });
//     function c(h, j) {
//         var i = new FormData();
//         i.append("upfile", h);
//         common.loadBox.add("上传中，请稍后");
//         common.ajax.upload("upload/file", i,
//             function(k) {
//                 k = JSON.parse(k);
//                 common.loadBox.hide();
//                 if (k.code == "1001") {
//                     j(h.name, k.result);
//                 } else {
//                     if (k.code == "1002") {
//                         common.nomalPrompt(k.message);
//                     } else {
//                         common.nomalPrompt(k.result);
//                     }
//                 }
//             },
//             function() {
//                 common.nomalPrompt("请检查文件命名是否规范",
//                     function() {
//                         common.loadBox.hide();
//                     });
//             });
//     }
//     a.on("click", "i.fa-close",
//         function() {
//             var i = a.data("files"),
//                 h = $(this).parent();
//             i.splice(h.index(), 1);
//             a.data("files", i);
//             h.remove();
//         });
// };
// $(".upload-pic-btn").each(function() {
//     var c = $(this).find("input[type=file]"),
//         b = c.attr("accept").indexOf("image") >= 0;
//     if (b) {
//         var a = c.attr("number");
//         if (a == "" || a == 0 || a > 1) {
//             c.attr("multiple", "multiple");
//         }
//     }
//     $(this).off("click").on("click",
//         function() {
//             c.UploadImg({
//                 url: uploadimgurl,
//                 maxwidth: "1000",
//                 quality: "0.8",
//                 mixsize: "10485760",
//                 element: c,
//                 files: [],
//                 before: function(d) {
//                     common.loadBox.add("图片上传中，请稍后");
//                 },
//                 error: function(d) {
//                     common.nomalPrompt(d,
//                         function() {
//                             common.loadBox.hide();
//                         });
//                 },
//                 success: function(d) {
//                     console.log(d);
//                     c.val("");
//                     common.loadBox.hide();
//                     var e = c.parent("li");
//                     a == 1 && e.prev().length > 0 ? e.prev().remove() : "";
//                     console.log(e);
//                     $.each(d,
//                         function(f, g) {
//                             if (g.url) {
//                                 common.writeUploadPic(g.url, e);
//                             }
//                             a == 1 ? e.addClass("edit") : "";
//                         });
//                 }
//             });
//         });
// });
// $(".upload-picture-box").on("click", "li i.fa-times-circle",
//     function() {
//         var a = $(this).parent();
//         a.siblings("li.upload-pic-btn").removeClass("edit");
//         a.remove();
//     });