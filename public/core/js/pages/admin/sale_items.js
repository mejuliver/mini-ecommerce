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
    });
    
});

function reload_sale_items(e){
    if(e.success){
        $('#sale-items-table').dataTable().fnClearTable();
        $.each(e.items,function(i,e){
            $('#sale-items-table').DataTable().row.add([
                '<a href="/app/system/sale-items/item/'+e.item_id+'">'+e.item_name+'</a>',
                e.item_images.length+' pics',
                e.item_review.length+' reviews',
                e.status==="pending"?'a':'b',
                e.discounted ? '<span style="text-decoration:line-through;">'+e.price+'</span><span>'+e.discounted+'</span>' : e.price,
                e.status==='approved' ? e.order?'<form action="'+site_link+'/app/system/admin/sale-items/item/view-orders'+'" method="post" class="ejex-form" data-type="json" data-onsuccess="view_orders"> <input type="hidden" name="id" value="'+e.item_id+'"> <div class="display-table center"> <div class="display-row"> <div class="display-cell padding-right7px"> <button class="btn btn-info" data-toggle="tooltip" title="View orders" style="font-size:10px;padding:5px 8px;"> View Orders </button> </div></div></div></form>':'0 Orders' : '<i class="fa fa-exclamation-circle c-red" aria-hidden="true" data-toggle="tooltip" title="Pending item."></i>' ,
                e.quantity,
                e.format_date,
                e.status==='pending' ? '<div class="display-table center"><div class="display-row"><div class="display-cell padding-right7px"><form action="'+site_link+'/app/system/admin/sale-items/item/approve'+'" method="post" class="ejex-form" data-type="json" data-onsuccess="approve_item"> <input type="hidden" name="id" value="'+e.item_id+'"> <button class="btn btn-success" data-toggle="tooltip" title="Approve item" style="font-size:10px;padding:5px 8px;"><i class="fa fa-check"></i></button></form></div><div class="display-cell"><form action="'+site_link+'/app/system/admin/sale-items/item/reject'+'" method="post" class="ejex-form" data-type="json" data-onsuccess="reject_item"> <input type="hidden" name="id" value="'+e.item_id+'"> <button class="btn btn-danger" data-toggle="tooltip" title="Reject item" style="font-size:10px;padding:5px 8px;"> <i class="fa fa-times"></i></button></form></div></div></div>' : '<div class="display-table center c-megamitch"><div class="display-row"><div class="display-cell padding-right7px"><i class="fa fa-check"></i></div><div class="display-cell"> Approved </div></div></div>'
            ]).draw()
                .order( [[ 3, 'asc' ]] )
                .nodes().to$()
                .find('td:nth-child(4)')
                .hide()
                .closest("tr")
                .attr('data-id',e.item_id);
        });
    }
}
function view_orders(e){
    if(e.success){        
        notification('VIEW ORDER',$('#view-order-container').html());
        $("#notification-dialog table").each(function(){
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
        });

        $('#notification-dialog #total_orders .order-status-counter').text(e.total_orders);
        $('#notification-dialog #pending_orders .order-status-counter').text(e.pending_orders);
        $('#notification-dialog #in_process_orders .order-status-counter').text(e.in_process_orders);
        $('#notification-dialog #completed_orders .order-status-counter').text(e.completed_orders);
        $('#notification-dialog #error_orders .order-status-counter').text(e.error_orders);

        $('#notification-dialog table').dataTable().fnClearTable();
        $.each(e.orders,function(i,e){
            $('#notification-dialog table').DataTable().row.add([
                e.order_id,
                e.created_at,
                e.order_name,
                e.profile.first_name+' '+e.profile.last_name,
                e.profile.address,
                e.payment_type.name,
                e.status,
                e.note,
                '<form action="'+site_link+'/app/system/admin/sale-items/item/view-orders/orders'+'" method="post" class="ejex-form" data-type="json" data-onsuccess="view_orders_items"><input type="hidden" name="id" value="'+e.order_id+'"><div class="display-table center"><div class="display-row"><div class="display-cell padding-right7px"><button class="btn btn-info" data-toggle="tooltip" title="View orders" style="font-size:10px;padding:5px 8px;">View Items</button></div></div></div></form>',
                e.format_date
            ]).draw().order( [[ 1, 'asc' ]] ).nodes().to$().find('td:nth-child(2)').hide();
        });

    }
}

function view_orders_items(e){
    if(e.success){
        extra_modal('ITEMS',$('#view-order-items-container').html());
        $("#extra-modal table").each(function(){
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
        });

        

        $('#extra-modal table').dataTable().fnClearTable();
        $.each(e.items,function(i,e){
            $('#extra-modal table').DataTable().row.add([
                e.item_name,
                e.created_at,
                e.quantity,
                e.status,
                e.note,
            ]).draw().order( [[ 1, 'asc' ]] ).nodes().to$().find('td:nth-child(2)').hide();
        });
    }
}
//when approve item
function approve_item(e){
    $('#sale-items-table').DataTable()
        .cell($('#sale-items-table tbody tr[data-id="'+e.id+'"] td:nth-child(6)'))
        .data('0 Orders')
        .cell($('#sale-items-table tbody tr[data-id="'+e.id+'"] td:nth-child(9)'))
        .data('<div class="display-table center c-megamitch"><div class="display-row"><div class="display-cell padding-right7px"><i class="fa fa-check"></i></div><div class="display-cell">Approved</div></div></div>')
        .draw().order( [[ 3, 'asc' ]] );
}
//when reject item
function reject_item(e){
     $('#sale-items-table').DataTable().row( $('#sale-items-table tbody tr[data-id="'+e.id+'"]')).remove().draw().order( [[ 3, 'asc' ]] );
}