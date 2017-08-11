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
      $('#tag-input').find('input[type="text"]').before('<div class="tag" data-toggle="tooltip" title="Click to remove" data-tag-id="'+dis.attr('data-tag-id')+'">'+dis.text()+'</div>');
      dis.remove();
      $('#tag-input input').val('');
      $("#tag-suggestion .tooltip").remove();
      $('#tag-suggestion a').tooltip();
      $('#tag-suggestion').append('<input type="hidden" name="tag[]" value="'+dis.attr('data-tag-id')+'">');
    });
    //when click on tag then remove it.
    $(document).on('click','#tag-input .tag',function(e){
      e.preventDefault();
      var dis = $(this);
      dis.remove();
      $('#tag-suggestion input[value="'+dis.attr('data-tag-id')+'"]').remove();
      $("#tag-input .tooltip").remove();
      $('#tag-input .tag').tooltip();
    });
    //when click the item category
    $(document).on('click','#categories-holder a',function(){
      var dis = $(this);
      if($(this).hasClass('active-cat')){
        dis.removeClass('active-cat bg-gray').appendTo('#categories-holder');
        $('#categories-holder input[value="'+dis.attr('data-cat-id')+'"]').remove();
      }else{
        dis.addClass('active-cat bg-gray').prependTo('#categories-holder');
        $('#categories-holder').append('<input type="hidden" name="category[]" value="'+dis.attr('data-cat-id')+'">');
      }

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
    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
    '//www.tinymce.com/css/codepen.min.css'
  ]
 });

function create_item(e){
  if(e.success){
    location.replace(e.link);
  }
}