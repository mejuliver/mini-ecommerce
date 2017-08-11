$(document).ready(function(){
	$.ajax({
		url:site_link+'/app/system/admin/orders-chart',
		type:'post',
		dataType:'json',
		success:function(e){
			if(e.success){
				$('#orders-chart').html('');
				refresh_orders_chart(e);
			}
		}
	});
});

$(window).resize(function(){
   chart_size();
});
function chart_size(){
    $(".chart").each(function(){
       $(this).addClass('center').css('width',$(this).closest(".perent").width() + 'px');
       $(this).find(".highcharts-container").addClass('center').css('width',$(this).closest(".perent").width() + 'px');
    });
}
function refresh_orders_chart(e){
     var jan=0,feb=0,mar=0,apr=0,may=0,jun=0,jul=0,aug=0,sep=0,oct=0,nov=0,dec=0;
    //in stock array
    $.each(e.pending_orders,function(index,value){
        switch(moment(value.created_at).format('MMM').toLowerCase()){
            case "jan":
                jan=+1;
                break;
            case "feb":
                feb=+1;
                break;
            case "apr":
                apr=+1;
                break;
            case "may":
                may=+1;
                break;
            case "jun":
                jun=+1;
                break;
            case "jul":
                jul=+1;
                break;
            case "aug":
                aug=+1;
                break;
            case "sep":
                sep=+1;
                break;
            case "oct":
                oct=+1;
                break;
            case "nov":
                nov=+1;
                break;
            case "dec":
                dec=+1;
                break;
        }
    });
    var pending_orders=[jan,feb,mar,apr,may,jun,jul,aug,sep,oct,nov,dec];
    jan=0,feb=0,mar=0,apr=0,may=0,jun=0,jul=0,aug=0,sep=0,oct=0,nov=0,dec=0;

    //out of stock array
    $.each(e.processing_orders,function(index,value){
        switch(moment(value.created_at).format('MMM').toLowerCase()){
            case "jan":
                jan=+1;
                break;
            case "feb":
                feb=+1;
                break;
            case "apr":
                apr=+1;
                break;
            case "may":
                may=+1;
                break;
            case "jun":
                jun=+1;
                break;
            case "jul":
                jul=+1;
                break;
            case "aug":
                aug=+1;
                break;
            case "sep":
                sep=+1;
                break;
            case "oct":
                oct=+1;
                break;
            case "nov":
                nov=+1;
                break;
            case "dec":
                dec=+1;
                break;
        }
    });
    var processing_orders=[jan,feb,mar,apr,may,jun,jul,aug,sep,oct,nov,dec];
    jan=0,feb=0,mar=0,apr=0,may=0,jun=0,jul=0,aug=0,sep=0,oct=0,nov=0,dec=0;

    //requested items array
    $.each(e.completed_orders,function(index,value){
        switch(moment(value.created_at).format('MMM').toLowerCase()){
            case "jan":
                jan=+1;
                break;
            case "feb":
                feb=+1;
                break;
            case "apr":
                apr=+1;
                break;
            case "may":
                may=+1;
                break;
            case "jun":
                jun=+1;
                break;
            case "jul":
                jul=+1;
                break;
            case "aug":
                aug=+1;
                break;
            case "sep":
                sep=+1;
                break;
            case "oct":
                oct=+1;
                break;
            case "nov":
                nov=+1;
                break;
            case "dec":
                dec=+1;
                break;
        }
    });
    var completed_orders=[jan,feb,mar,apr,may,jun,jul,aug,sep,oct,nov,dec];
    jan=0,feb=0,mar=0,apr=0,may=0,jun=0,jul=0,aug=0,sep=0,oct=0,nov=0,dec=0;

    //all items created in this year
    $.each(e.error_orders,function(index,value){
        switch(moment(value.created_at).format('MMM').toLowerCase()){
            case "jan":
                jan=+1;
                break;
            case "feb":
                feb=+1;
                break;
            case "apr":
                apr=+1;
                break;
            case "may":
                may=+1;
                break;
            case "jun":
                jun=+1;
                break;
            case "jul":
                jul=+1;
                break;
            case "aug":
                aug=+1;
                break;
            case "sep":
                sep=+1;
                break;
            case "oct":
                oct=+1;
                break;
            case "nov":
                nov=+1;
                break;
            case "dec":
                dec=+1;
                break;
        }
    });
    var error_orders=[jan,feb,mar,apr,may,jun,jul,aug,sep,oct,nov,dec];
    jan=0,feb=0,mar=0,apr=0,may=0,jun=0,jul=0,aug=0,sep=0,oct=0,nov=0,dec=0;
    
    
    $('#orders-chart').highcharts({
         chart: {
            borderColor: '#ff0000',
            width: $(this).closest(".perent").width(),
            height: 300
        },
        title: {
            text: false,
            x: -20 //center
        },
        subtitle: {
            text: false,
            x: -20
        },
        xAxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        yAxis: {
            title: {
                text: false
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            shared: true,
            crosshairs: true
        },
        exporting: {
            buttons: {
                contextButton: {
                    theme: {
                        zIndex: 100   
                    }
                }
            }

        },
        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom',
            borderWidth: 0,
            x : 0,
            y: 0
        },
        series: [{
            name: 'Pending Orders',
            data: pending_orders
        }, {
            name: 'In Process Orders',
            data: processing_orders
        }, {
            name: 'Completed Orders',
            data: completed_orders
        }, {
            name: 'Error Orders',
            data: error_orders
        }]
    });
    chart_size();
}