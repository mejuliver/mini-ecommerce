$(document).ready(function(){
      //when click on tag then remove it.
    $(document).on('click','.cat',function(e){
      e.preventDefault();
      var dis = $(this);
      $.ajax({
        url:site_link+'/app/system/admin/categories/delete-category',
        data:{ cat_id : dis.attr("data-cat-id") },
        type: 'post',
        dataType: 'json',
        beforeSend:function(){j_loading("on")},
        complete:function(){j_loading("off")},
        success: function(e){
          if(e.success){
            dis.remove();
            $('.cat[data-cat-id="'+e.tag_id+'"]').remove();
            $('#cat-count').text($('#cat-count .cat').length);
          }
        }
      })
    });
});
function create_category(e){
  if(e.success){
      $('#categories-container').prepend('<a href="#" class="cat radius-15px" style="font-size:15px;padding:4px 8px;" data-cat-id="'+e.cat_id+'" data-toggle="tooltip" title="'+e.cat_desc+'">'+e.cat_name+'</a>');
      $('#create-category-form input').val('');
      $('#categories-container .tooltip').remove();
      $('#categories-container .cat').tooltip();
      $('#cat-count').text($('#categories-container .cat').length);
  }
}
