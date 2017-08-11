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
            "fnDrawCallback": function( oSettings ) {
              $('.dtable [data-toggle="tooltip"]').tooltip();
            },
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
    });

    //activate-account click
    $(document).on('click','.reject-account',function(){
        notification('Reject Account',$('#reject-account-container').html());
        $('#notification-dialog input[name="id"]').val($(this).attr("data-id"));
    });
    $(document).on('click','.new-pic-img-review',function(){
        notification('',$('#new-pic-img-review-container').html());
        $('#notification-dialog .container-view img').attr("src",$(this).find('img').attr("src"));
        $('#notification-dialog .container-view input[name="id"]').val($(this).attr("data-id"));
    });
});

function reject_account(e){
    if(e.success){
        $('#users-table').DataTable().row( $('#users-table tbody tr[data-id="'+e.id+'"]')).remove().draw().order( [[ 2, 'asc' ]] );
    }
}
function activate_account(e){
    if(e.success){
        $('#users-table').DataTable()
            .cell($('#users-table tbody tr[data-id="'+e.id+'"] td:nth-child(2)'))
            .data('b')
            .cell($('#users-table tbody tr[data-id="'+e.id+'"] td:nth-child(7)'))
            .data('<div class="display-table center c-megamitch"> <div class="display-row"> <div class="display-cell padding-right5px"> <i class="fa fa-check"></i> </div><div class="display-cell"> Active </div></div></div>')
            .cell($('#users-table tbody tr[data-id="'+e.id+'"] td:nth-child(9)'))
            .data('<div class="display-table center"> <a href="'+site_link+'/app/system/admin/users/'+e.id+'" class="btn btn-info" data-toggle="tooltip" title="View profile" style="font-size:10px;padding:5px 8px;"> <i class="fa fa-eye" aria-hidden="true"></i> </div>')
            .draw().order( [[ 2, 'asc' ]] );
    }
}
function new_img_review(e){
    var new_img = e.img||e.img!==""?'/app/system/user/'+e.id+'/profile/'+e.img:'/core/media/images/no_img.jpg';
    $('#users-table').DataTable()
        .cell($('#users-table tbody tr[data-id="'+e.id+'"] td:nth-child(1)'))
        .data('<img src="'+new_img+'" alt="'+e.name+'" class="display-block center radius-circle" style="width:30px;height:30px;">')
        .draw().order( [[ 2, 'asc' ]] );
}
 function hide_container_view(){
    $("#notification-dialog .container-view").fadeOut(200);
 }