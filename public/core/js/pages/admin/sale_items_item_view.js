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
    $(document).on('click','.item-review-view-review',function(){
        notification('<strong>REVIEW</strong>',$(this).attr("data-review"));
    });
    // ##################### GALLERY
    //when click the item image
    $(document).on('click','#item-pictures-container a',function(e){
      e.preventDefault();
      $("#notification-dialog").addClass("bs-example-modal-lg").find(".modal-dialog").addClass("modal-lg");
      var image_name = $(this).attr("data-image-name").substring(35,0)+'...',
          custom ='<div class="display-table full-width"><img src="'+$(this).find('img').attr("src")+'" data-image-id="'+$(this).attr('data-image-id')+'" class="display-block center extend"></div>'+
                  '<div class="display-table full-width margin-top9px">'+
                  '<div class="display-table align-left margin-top8px"><div class="display-row"><div class="display-cell padding-right10px"><a href="#" id="gallery-prev" data-toggle="tooltip" title="Move to previous photo" data-placement="left"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></div><div class="display-cell"><a href="#" id="gallery-next" data-toggle="tooltip" title="Move to next photo" data-placement="right"><i class="fa fa-chevron-right" aria-hidden="true"></i></a></div></div></div>'+
                  '<div class="display-table align-right" id="product-image-footer"> <div class="display-table align-left padding-7px c-megamitch" id="product-primary-image-label"><i class="fa fa-check"></i> Primary product picture</div></div>'+
                  '</div>';
      notification(image_name,custom);
      $('#notification-dialog [data-toggle="tooltip"]').tooltip();
      if($(this).hasClass("primary-item-pic")){
          $('#product-primary-image-label').show();
      }else{
          $('#product-primary-image-label').hide();
      }
    });
    //when click the gallery prev photo
    $(document).on('click','#gallery-prev',function(e){
      e.preventDefault();

      if($('#item-pictures-container a[data-image-id="'+$('#notification-dialog img').attr("data-image-id")+'"]').prev("a").length){
        $('#notification-dialog img').attr({ "src":$('#item-pictures-container a[data-image-id="'+$('#notification-dialog img').attr("data-image-id")+'"]').prev('a').find("img").attr("src"),'data-image-id':$('#item-pictures-container a[data-image-id="'+$('#notification-dialog img').attr("data-image-id")+'"]').prev('a').attr("data-image-id") });
        
        if($('#item-pictures-container a[data-image-id="'+$('#notification-dialog img').attr("data-image-id")+'"]').hasClass("primary-item-pic")){
            $('#product-primary-image-label').show();
        }else{
            $('#product-primary-image-label').hide();
        }


        $('#notification-dialog .tooltip').remove();
        $('#notification-dialog [data-toggle="tooltip"]').tooltip();
      }
    });
    //when click the gallery next photo
    $(document).on('click','#gallery-next',function(e){
      e.preventDefault();
      if($('#item-pictures-container a[data-image-id="'+$('#notification-dialog img').attr("data-image-id")+'"]').next("a").length){
        $('#notification-dialog img').attr({ "src":$('#item-pictures-container a[data-image-id="'+$('#notification-dialog img').attr("data-image-id")+'"]').next('a').find("img").attr("src"),'data-image-id':$('#item-pictures-container a[data-image-id="'+$('#notification-dialog img').attr("data-image-id")+'"]').next('a').attr("data-image-id")});
        
        if($('#item-pictures-container a[data-image-id="'+$('#notification-dialog img').attr("data-image-id")+'"]').hasClass("primary-item-pic")){
            $('#product-primary-image-label').show();
        }else{
            $('#product-primary-image-label').hide();
        }

        $('#notification-dialog .tooltip').remove();
        $('#notification-dialog [data-toggle="tooltip"]').tooltip();
      }
    });  
});

