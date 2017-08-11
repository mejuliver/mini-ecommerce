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
                e.created_at,
                e.price,
                e.quantity,
                e.format_date
            ]).draw().order( [[ 3, 'asc' ]] ).nodes().to$().find('td:nth-child(4)').hide();
        });
    }
}
