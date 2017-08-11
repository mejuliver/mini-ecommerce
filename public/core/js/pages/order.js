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

function reload_orders(e){
    if(e.success){
        $('#orders-table').dataTable().fnClearTable();
        $.each(e.orders,function(i,e){
            $('#orders-table').DataTable().row.add([
                e.order_id,
                e.order_name,
                e.payment_type,
                e.status,
                e.note,
                e.created_at,
                '<form action="'+'/app/system/admin/sale-items/item/view-orders/orders'+'" method="post" class="ejex-form" data-type="json" data-onsuccess="view_orders_order"> <input type="hidden" name="order_id" value="'+e.order_id+'"> <div class="display-table center"> <div class="display-row"> <div class="display-cell padding-right7px"> <button class="btn btn-info" data-toggle="tooltip" title="View orders" style="font-size:10px;padding:5px 8px;"> View Orders </button> </div></div></div></form>',
                e.format_date
            ]).draw().order( [[ 5, 'asc' ]] ).nodes().to$().find('td:nth-child(5)').hide();
        });
    }
}

function view_orders_items(e){
    if(e.success){
        notification('ITEMS',$('#view-order-items-container').html());
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

        

        $('#notification-dialog table').dataTable().fnClearTable();
        $.each(e.items,function(i,e){
            $('#notification-dialog table').DataTable().row.add([
                e.item_name,
                e.created_at,
                e.quantity,
                e.status,
                e.note,
            ]).draw().order( [[ 1, 'asc' ]] ).nodes().to$().find('td:nth-child(2)').hide();
        });
    }
}