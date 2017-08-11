$(document).ready(function(){
      //when click on tag then remove it.
    $(document).on('click','#my-tags .tag',function(e){
      e.preventDefault();
      var dis = $(this);
      $.ajax({
        url:site_link+'/app/system/sale-items/tags/delete-tag',
        data:{ tag_id : dis.attr("data-tag-id") },
        type: 'post',
        dataType: 'json',
        beforeSend:function(){j_loading("on")},
        complete:function(){j_loading("off")},
        success: function(e){
          if(e.success){
            dis.remove();
            $('#all-tags .tag[data-tag-id="'+e.tag_id+'"]').remove();
            $('#my-tags-count').text($('#my-tags .tag').length);
            $('#all-tags-count').text($('#all-tags .tag').length);
          }
        }
      })
    });
});
function create_tag(e){
  if(e.success){
      $('#my-tags').prepend('<a href="#" class="tag" data-tag-id="'+e.tag_id+'" data-toggle="tooltip" title="Click to delete tag">'+e.tag_name+'</a>');
      $('#all-tags').prepend('<a href="#" class="tag bg-megamitch" data-tag-id="'+e.tag_id+'">'+e.tag_name+'</a>');
      $('#create-tag-form input').val('');
      $('#my-tags .tooltip').remove();
      $('#my-tags .tag').tooltip();
      $('#my-tags-count').text($('#my-tags .tag').length);
      $('#all-tags-count').text($('#all-tags .tag').length);
  }
}
