$(document).ready(function(){
  //loop through all the tables that has a class of dtables and make a datatables
    $(".dtable").each(function(){
        var sort_c = $(this).attr("data-sort-column"), sort_t = $(this).attr("data-sort-type");
        if(typeof sort_c !== typeof undefined && sort_c !== false && sort_c !== ""){
            sort_c = parseInt($(this).attr("data-sort-column"));
        }else{
            sort_c = false;
        }
        if(typeof sort_t !== typeof undefined && sort_t !== false && sort_t !== ""){
            sort_t = sort_t;
        }else{
            sort_t = false;
        }
        if(sort_c === false) sort_c = 0;
        if(sort_t === false) sort_t = "asc";
        var sorting = [[sort_c,sort_t]],
          table = $(this).DataTable( {
            "pagingType": "full_numbers",
            "lengthMenu": [[10, 25, 50, -1],[10, 25, 50, "All"]],
            "aaSorting": sorting,
            "ordering" : sorting,
            "oLanguage": {
            "oPaginate": {
              "sPrevious": '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
              "sNext": '<i class="fa fa-chevron-right" aria-hidden="true"></i>' 
            }
          }
        });
        $(this).find(".dt_dont_click").off("click.DT");

        $(this).addClass("table-responsive").wrap('<div class="table-responsive"></div>');
        $(this).find(".dataTables_empty").attr("colspan",$(this).find("th").length);
    });
    $('.currency').inputmask({ alias : "currency", prefix: '' });
    $('.number').inputmask('numeric', { rightAlign: false });
    $(document).on('click','.item-review-view-review',function(){
        notification('<strong>REVIEW</strong>',$(this).attr("data-review"));
    });
    //item tags creation, when interact unto the tag input
    // $('#tag-input input').keypress(function (e) {
    //   if (e.keyCode === 13 || e.which === 13) {
    //     e.preventDefault()
    //     if($(this).val()!==""){
    //       $('#tag-form input[name="tag_name"]').val($(this).val());
    //       $('#tag-form input[name="tag_id"]').val($(this).val());
    //       $(this).val('');
    //       $('#tag-form').trigger('submit');
    //     }else{
    //       notification('Tag Error','<strong class="c-red">Tag must not be empty!</span>');
    //     }
    //   }
    // });
    $('#tag-input input').on('input',function (e) {
      var dis = $(this);
      if($(this).val()!==''){
        $.ajax({
          url:site_link+'/app/system/sale-items/item/tags/search',
          data: { item_id : $(".item-view").attr("data-item-id"), ss : dis.val() },
          type:'post',
          dataType:'json',
          success:function(e){
            if(e.success){
              $("#tag-suggestion").html("");
              $.each(e.tags,function(i,e){
                $('#tag-suggestion').append('<a href="#" class="font-size9px padding-zero display-table align-left" style="margin:1.5px 2px;" data-tag-id="'+e.tag_id+'" data-toggle="tooltip" title="Click to attach this tag to your item">'+e.tag_name+'</a>');
              });
              $('#tag-suggestion a').tooltip();
            }
          }
        });
      }else{
        $("#tag-suggestion").html("");
      }
          
      // Put it in the hidden span using the same font information
      var $hidden = $("#tag-input span");
      $hidden.text(dis.val());
      
      // Set the input to the span's width plus a buffer
      dis.css('width', $hidden.width() + 30);
    });
    //when click on tag then remove it.
    $(document).on('click','#tag-suggestion a',function(e){
      e.preventDefault();
      var dis = $(this);
      $.ajax({
        url:site_link+'/app/system/sale-items/item/tags/add-tag',
        data:{ tag_id : dis.attr("data-tag-id"), item_id : $(".item-view").attr("data-item-id") },
        type: 'post',
        dataType: 'json',
        beforeSend:function(){j_loading("on")},
        complete:function(){j_loading("off")},
        success: function(e){
          if(e.success){
            $('#tag-input').find('input[type="text"]').before('<div class="tag" data-toggle="tooltip" title="Click to remove" data-tag-id="'+dis.attr('data-tag-id')+'">'+dis.text()+'</div>');
            dis.remove();
            $('#tag-input input').val('');
            $("#tag-suggestion .tooltip").remove();
            $('#tag-suggestion a').tooltip();
            $('#tag-count').text($('#tag-input .tag').length);
          }
        }
      })
    });
    //when click on tag then remove it.
    $(document).on('click','#tag-input .tag',function(e){
      e.preventDefault();
      var dis = $(this);
      $.ajax({
        url:site_link+'/app/system/sale-items/item/tags/remove-tag',
        data:{ tag_id : dis.attr("data-tag-id"), item_id : $(".item-view").attr("data-item-id") },
        type: 'post',
        dataType: 'json',
        beforeSend:function(){j_loading("on")},
        complete:function(){j_loading("off")},
        success: function(e){
          if(e.success){
            dis.remove();
            $("#tag-input .tooltip").remove();
            $('#tag-input .tag').tooltip();
            $('#tag-count').text($('#tag-input .tag').length);
          }
        }
      })
    });
    //when click on the #submit-update-form then trigger the #item-update-form
    $('#submit-update-form').click(function(){
      $('#item-update-form').trigger('submit');
    });
    //when click on the #submit-update-form then trigger the #item-update-form
    $('#delete-sale-item-button').click(function(){
      notification('Delete',$('#delete-sale-item-container').html());
    });
    // ##################### GALLERY
    //when click the item image
    $(document).on('click','#item-pictures-container a',function(e){
      e.preventDefault();
      $("#notification-dialog").addClass("bs-example-modal-lg").find(".modal-dialog").addClass("modal-lg");
      var image_name = $(this).attr("data-image-name").substring(35,0)+'...',
          custom ='<div class="display-table full-width"><img src="'+$(this).find('img').attr("src")+'" class="display-block center extend"></div>'+
                  '<div class="display-table full-width margin-top9px">'+
                  '<div class="display-table align-left margin-top8px"><div class="display-row"><div class="display-cell padding-right10px"><a href="#" id="gallery-prev" data-toggle="tooltip" title="Move to previous photo" data-placement="left"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></div><div class="display-cell"><a href="#" id="gallery-next" data-toggle="tooltip" title="Move to next photo" data-placement="right"><i class="fa fa-chevron-right" aria-hidden="true"></i></a></div></div></div>'+
                  '<div class="display-table align-right" id="product-image-footer"> <div class="display-table align-left padding-7px c-megamitch" id="product-primary-image-label"><i class="fa fa-check"></i> Primary product picture</div><form id="product-primary-image-form" action="'+site_link+'/app/system/sale-items/item/image/set-primary" method="post" class="ejex-form display-table align-left margin-right5px" data-type="json" data-onsuccess="product_set_primary_image"> <input type="hidden" name="image_id" value="'+$(this).attr("data-image-id")+'"> <input type="hidden" name="item_id" value="'+$('.item-view').attr("data-item-id")+'"> <button class="btn btn-default" data-toggle="tooltip" title="Click to set this pic as product primary picture" data-placement="left">Set as a product primary picture</button> </form> <form id="product-image-delete-form" action="'+site_link+'/app/system/sale-items/item/image/delete" method="post" class="ejex-form display-table align-left" data-type="json" data-onsuccess="delete_image"> <input type="hidden" name="image_id" value="'+$(this).attr("data-image-id")+'"> <button class="btn btn-default" data-toggle="tooltip" title="Click to delete" data-placement="left"><i class="fa fa-trash" aria-hidden="true"></i></button> </form></div>'+
                  '</div>';
      notification(image_name,custom);
      $('#notification-dialog [data-toggle="tooltip"]').tooltip();
      if($(this).hasClass("primary-item-pic")){
          $('#product-primary-image-form').hide();
          $('#product-primary-image-label').show();
      }else{
          $('#product-primary-image-label').hide();
          $('#product-primary-image-form').show();
      }
    });
    //when click the gallery prev photo
    $(document).on('click','#gallery-prev',function(e){
      e.preventDefault();

      if($('#item-pictures-container a[data-image-id="'+$('#product-image-delete-form input[name="image_id"]').val()+'"]').prev("a").length){
        $('#notification-dialog img').attr("src",$('#item-pictures-container a[data-image-id="'+$('#product-image-delete-form input[name="image_id"]').val()+'"]').prev('a').find("img").attr("src"));
        $('input[name="image_id"]').val($('#item-pictures-container a[data-image-id="'+$('#product-image-delete-form input[name="image_id"]').val()+'"]').prev('a').attr("data-image-id"));
        
        if($('#item-pictures-container a[data-image-id="'+$('#product-image-delete-form input[name="image_id"]').val()+'"]').hasClass("primary-item-pic")){
            $('#product-primary-image-form').hide();
            $('#product-primary-image-label').show();
        }else{
            $('#product-primary-image-label').hide();
            $('#product-primary-image-form').show();
        }


        $('#notification-dialog .tooltip').remove();
        $('#notification-dialog [data-toggle="tooltip"]').tooltip();
      }
    });
    //when click the gallery next photo
    $(document).on('click','#gallery-next',function(e){
      e.preventDefault();
      if($('#item-pictures-container a[data-image-id="'+$('#product-image-delete-form input[name="image_id"]').val()+'"]').next("a").length){
        $('#notification-dialog img').attr("src",$('#item-pictures-container a[data-image-id="'+$('#product-image-delete-form input[name="image_id"]').val()+'"]').next('a').find("img").attr("src"));
        $('input[name="image_id"]').val($('#item-pictures-container a[data-image-id="'+$('#product-image-delete-form input[name="image_id"]').val()+'"]').next('a').attr("data-image-id"));
        
        if($('#item-pictures-container a[data-image-id="'+$('#product-image-delete-form input[name="image_id"]').val()+'"]').hasClass("primary-item-pic")){
            $('#product-primary-image-form').hide();
            $('#product-primary-image-label').show();
        }else{
            $('#product-primary-image-label').hide();
            $('#product-primary-image-form').show();
        }

        $('#notification-dialog .tooltip').remove();
        $('#notification-dialog [data-toggle="tooltip"]').tooltip();
      }
    });
    //when click the item category
    $(document).on('click','#categories-holder a',function(){
      var dis = $(this),ty = 'add';
      if($(this).hasClass('active-cat')){
        ty = 'remove';
      }
      $.ajax({
        url:site_link+'/app/system/sale-items/item/category',
        data:{type:ty,cat_id:dis.attr("data-cat-id"),item_id:$('.item-view').attr("data-item-id")},
        type:'post',
        dataType:'json',
        beforeSend:function(){
          j_loading("on");
        },
        complete:function(){
          j_loading("off");
        },success:function(e){
          if(e.success){
            if(ty==='remove'){
              dis.removeClass('active-cat bg-gray').appendTo('#categories-holder');
            }else{
              dis.addClass('active-cat bg-gray').prependTo('#categories-holder');
            }
          }
        }
      });

    });
});

//init tinymce
tinymce.init({
  selector: 'textarea',
  height: 500,
  theme: 'modern',
  plugins: [
    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
    'searchreplace wordcount visualblocks visualchars code fullscreen',
    'insertdatetime media nonbreaking save table contextmenu directionality',
    'emoticons template paste textcolor colorpicker textpattern imagetools codesample'
  ],
  toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  toolbar2: 'print preview media | forecolor backcolor emoticons | codesample',
  image_advtab: true,
  templates: [
    { title: 'Test template 1', content: 'Test 1' },
    { title: 'Test template 2', content: 'Test 2' }
  ],
  content_css: [
    site_link+'/core/plugins/webfonts/roboto/roboto.css',
    site_link+'/core/css/tinymce.css'
  ]
 });

//when item review has been approve
function item_review_approve(e){
    $('#item-review-table').DataTable()
        .cell($('#item-review-table tbody tr[data-id="'+e.id+'"] td:nth-child(5)'))
        .data('<div class="display-table center c-megamitch font-700"> <div class="display-row"> <div class="display-cell padding-right5px"><i class="fa fa-check"></i></div><div class="display-cell">Approved</div></div></div>')
        .cell($('#item-review-table tbody tr[data-id="'+e.id+'"] td:nth-child(2)'))
        .data('a')
        .draw().order( [[ 1, 'desc' ]] );
    
    $("#reviews-count").text(e.reviews_count+' Reviews');
}
//when item review has been disapprove
function item_review_disapprove(e){
   $('#item-review-table').DataTable().row( $('#item-review-table tbody tr[data-id="'+e.id+'"]')).remove().draw().order( [[ 1, 'desc' ]] );
   $("#reviews-count").text(e.reviews_count+' Reviews');

}
//when image has been deleted
function delete_image(e){
  if(e.success){
    if($('#item-pictures-container a[data-image-id="'+e.image_id+'"]').prev("a").length){
      $('#gallery-prev').trigger('click');
      $('#item-pictures-container a[data-image-id="'+e.image_id+'"]').remove();
    }else if($('#item-pictures-container a[data-image-id="'+e.image_id+'"]').next("a").length){
      $('#gallery-next').trigger('click');
      $('#item-pictures-container a[data-image-id="'+e.image_id+'"]').remove();
    }else{
      $('#item-pictures-container').html('<span>Click the file browser to browse or you can drop item pictures here...</span>');
      $('#notification-dialog [data-dismiss="modal"]').trigger('click');
    }
  }
}
function product_set_primary_image(e){
  $('#product-primary-image-form').hide();
  $('#product-primary-image-label').show();
  $('#item-pictures-container a').removeClass('primary-item-pic');
  $('#item-pictures-container a[data-image-id="'+e.image_id+'"]').addClass('primary-item-pic').prependTo('#item-pictures-container');
}
function save_tinymce_textarea(){
  tinyMCE.triggerSave();
}
// function item_add_tags(e){
//   if(e.success){
//       $('#tag-input').find('input[type="text"]').before('<div class="tag" data-toggle="tooltip" title="Click to remove" data-tag-id="'+e.tag_id+'">'+e.tag_name+'</div>');
//       $('#tag-input input[type="text"]').val('');
//       $('#tag-input .tag').tooltip();
//   }else{
//     notification('Tag Error','<strong class="c-red">'+e.message+'</strong>');
//   }
// }

// ##################### U P L O A D ** S C R I P T #####################

function sendFileToServer(formData,status)
{
    formData.append('item_id',$(".item-view").attr("data-item-id"));
    var uploadURL=site_link+'/app/system/sale-items/item/image-upload', //Upload URL 
        extraData ={}, //Extra Data.
        jqXHR=$.ajax({
      xhr: function() {
      var xhrobj = $.ajaxSettings.xhr();
      if (xhrobj.upload) {
              xhrobj.upload.addEventListener('progress', function(event) {
                  var percent = 0;
                  var position = event.loaded || event.position;
                  var total = event.total;
                  if (event.lengthComputable) {
                      percent = Math.ceil(position / total * 100);
                  }
                  //Set progress
                  status.setProgress(percent);
              }, false);
          }
        return xhrobj;
      },
      url: uploadURL,
      type: "POST",
      dataType: 'json',
      contentType:false,
      processData: false, 
      cache: false,
      data: formData,
      beforeSend:function(){
        j_loading("on");
      },
      success: function(e){
          if(e.success){
            $("#notification_dialog .statusBar").remove();
            $("#item-pictures-container").html("");
            $.each(e.images,function(index,value){
               $("#item-pictures-container").append('<a href="#" data-image-id="'+value.id+'" data-image-name="'+value.image_name+'"><figure><img src="'+site_link+'/app/system/sale-items/item/image/'+value.username+'/'+value.item_id+'/'+value.image_name+'"> </figure></a>');
            });
            //check for the primary pic
            $('#item-pictures-container a[data-image-id="'+e.default_item_image+'"]')
              .addClass('primary-item-pic').prependTo("#item-pictures-container");
            
            $('#pictures-count').text($("#item-pictures-container a").length);
          }else{
            $('#item-pictures-container').prepend('<table cellpadding="0" cellspacing="0" class="c-red margin-zero padding-zero"><tr><td class="padding-right5px" style="width:10px;" valign="top"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></td><td>'+e.message+'</td></tr></table>');
          }
        }
  });
}
 
function createStatusbar()
{

    this.progressBar = $('<div class="statusBar"><div class="progressBar"><div></div></div></div>');
    $("#item-pictures-container").html(this.progressBar);
    this.setProgress = function(progress)
    {       
        var progressBarWidth =progress*this.progressBar.width()/ 100;  
        this.progressBar.find('.progressBar div').animate({ 'width' : progressBarWidth }, 10);
        if(parseInt(progress) >= 100)
        {
            j_loading("off");
            $('#browse-pics').prop("disabled",false);
            this.progressBar.find('.statusBar').remove();
        }
    }
}
function handleFileUpload(files)
{
  $('#browse-pics').prop("disabled",true);
  var status = new createStatusbar(),fd = new FormData();
  for (var i = 0; i < files.length; i++) 
  {
    fd.append('image[]', files[i]);
  }

   sendFileToServer(fd,status);
   
}
$(document).ready(function(){
    $(document).on('drop',"#item-pictures-container", function (e) 
    { 
      $(this).find('span').remove();
      e.preventDefault();
      var files = e.originalEvent.dataTransfer.files;
      //We need to send dropped files to Server
      handleFileUpload(files);
    });
    $(document).on('dragenter', "#item-pictures-container", function (e) 
    {
        e.stopPropagation();
        e.preventDefault();
    });
    $(document).on('dragover', function (e) 
    {
      e.stopPropagation();
      e.preventDefault();
    });
    $(document).on('drop', function (e) 
    {
        e.stopPropagation();
        e.preventDefault();
    });
    $(document).on("change",'#browse-pics',function(){
       if($(this).val()!==""){
          $('#item-pictures-container').find('span').remove();
            var files = this.files;
            //We need to send dropped files to Server
            handleFileUpload(files);
            $(this).val("");
       }
    });
});
