$(document).ready(function(){
	//on close modal
    $(document).on('hidden.bs.modal', '.modal', function () {
        $('.modal:visible').length && $(document.body).addClass('modal-open');
        
        if(refresh === true){
            location.reload();
        }
        dialog_open = false;
        
        $("#notification_dialog").removeClass("bs-example-modal-lg").find(".modal-dialog").removeClass("modal-lg");
    });
    //when click the user-settings
    $('#user-settings').click(function(e){
        e.preventDefault();
        $.ajax({
            url: site_link+'/app/system/user-settings/get',
            type: 'get',
            dataType: 'json',
            beforeSend:function(){
                j_loading('on');
            },
            beforeSend:function(){
                j_loading('off');
            },
            success:function(e){
                notification('SETTINGS',$('#user-settings-container').html());

                var custom = '<div class="display-table">';
                $.each(e.settings,function(i,e){
                    var checked = e.settings_value==='1'?'checked':'',
                        chk_val = '',
                        type = '';
                    switch(e.settings.type){
                        case 'checkbox' :
                            chk_val = checked==='checked'?'1':'0';
                            type = '<input type="checkbox" class="settings-checkbox" '+checked+'><input type="hidden" name="settings['+e.id+']" value="'+chk_val+'">';
                            break;
                        case 'input' :
                            chk_val = e.settings_value;
                            type = '<input type="text" name="settings['+e.id+']" value="'+chk_val+'" class="form-control">';
                            break;
                        case 'textarea':
                            chk_val = e.settings_value;
                            type = '<textarea name="settings['+e.id+']" class="form-control">'+chk_val+'</textarea>';
                            break;
                        default:
                            console.log("settings error");
                    }
                    custom+='<div class="display-row"> <div class="display-cell padding-right10px padding-bottom5px text-transform-capitalize font-700">'+e.settings.settings_name+'</div><div class="display-cell padding-bottom5px">'+type+'</div></div>';
                });
                custom+='</div>'
                $('#notification-dialog form fieldset').html(custom);
            }
        })
    });

    //when click on the settings checkbox
    $(document).on('change','#notification-dialog .settings-checkbox',function(){
        if($(this).is(":checked")){
            $(this).next('input[type="hidden"]').val(1);
        }else{
            $(this).next('input[type="hidden"]').val(0);
        }
    });
});
$(window).load(function(){
    init_components();
});
function format_datetime(e,f){
    return moment(e).format(f);
}

//hide the fieldset and the buttons holder
function hide_pokemon(){
 if($("#extra-modal").is(":visible")){
     $("#extra-modal fieldset").next().hide(function(){
        $("#extra-modal fieldset").fadeOut(200);
     });
 }else{
     $("#notification-dialog fieldset").next().hide(function(){
        $("#notification-dialog fieldset").fadeOut(200);
     });
 }
}
function init_components(){
	$('[data-toggle="tooltip"]').tooltip();
}
function user_settings(e){
    if(e.success){
        console.log(e);
    }
}