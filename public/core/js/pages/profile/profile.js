$(document).ready(function(){
	$('#browse-banner-button').click(function(){
		$('#browse-banner').trigger('click');
	});
	$('#browse-pic-button').click(function(){
		$('#browse-pic').trigger('click');
	});

	$('#browse-banner,#browse-pic').change(function(){
		if($(this).val()!==""){
			$(this).closest('form').trigger('submit');
		}
	});
	//when click on checkbox toggle password visibility
	$('#password-visibility').change(function(){
		if($(this).is(':checked')){
			$('#profile-info-form input[name="password"]').attr('type','text');
			$('#profile-info-form input[name="confirm_password"]').attr('type','text');
		}else{
			$('#profile-info-form input[name="password"]').attr('type','password');
			$('#profile-info-form input[name="confirm_password"]').attr('type','password');
		}
	});
	//when hit the #submit-update-form then trigger the #profile-info-form
	$('#submit-update-form').click(function(){
		$('#profile-info-form').trigger('submit');
	});
});
//when update profile banner
function banner_pic(e){
	if(e.success){
		$('.alert').remove();
		$('#user-banner').before('<div class="font-size13px alert alert-success" role="alert"><a href="#" data-dismiss="alert" style="color:rgba(0,0,0,0.3);display:block;float:right;"><i class="fa fa-times" aria-hidden="true"></i></a><table cellpadding="0" cellspacing="0" style="padding:0px;margin:0px"> <tr> <td class="padding-right10px" style="width:25px;" valign="top"><i class="fa fa-check-circle" aria-hidden="true"></i></td><td class="font-size13px text-align-left">Your profile banner has been successfully updated</td></tr></table> </div>');
		$('#user-banner').css('background-image','url('+e.image+')');
	}else{
		$('#user-banner').before('<div class="font-size13px alert alert-danger" role="alert"><a href="#" data-dismiss="alert" style="color:rgba(0,0,0,0.3);display:block;float:right;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></a><table cellpadding="0" cellspacing="0" style="padding:0px;margin:0px"> <tr> <td class="padding-right10px" style="width:25px;" valign="top"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></td><td class="font-size13px text-align-left">'+e.message+'</td></tr></table> </div>');
	}
}
//when update profile pic
function profile_pic(e){
	if(e.success){
		$('.alert').remove();
		if(e.status){
			$('#user-banner').before('<div class="font-size13px alert alert-success" role="alert"><a href="#" data-dismiss="alert" style="color:rgba(0,0,0,0.3);display:block;float:right;"><i class="fa fa-times" aria-hidden="true"></i></a><table cellpadding="0" cellspacing="0" style="padding:0px;margin:0px"> <tr> <td class="padding-right10px" style="width:25px;" valign="top"><i class="fa fa-check-circle" aria-hidden="true"></i></td><td class="font-size13px text-align-left">Your profile picture has been successfully uploaded. Your profile picture still subject for approval. Once approved, your profile picture will be then visible.</td></tr></table> </div>');
			$('#user-image figcaption').html('<span class="c-red">Pending for approval, click to change.</span>');
		}
		$('#user-pic').attr('src',e.image);	
	}else{
		$('#user-banner').before('<div class="font-size13px alert alert-danger" role="alert"><a href="#" data-dismiss="alert" style="color:rgba(0,0,0,0.3);display:block;float:right;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></a><table cellpadding="0" cellspacing="0" style="padding:0px;margin:0px"> <tr> <td class="padding-right10px" style="width:25px;" valign="top"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></td><td class="font-size13px text-align-left">'+e.message+'</td></tr></table> </div>');
	}
}
//when update profile info
function profile_update(e){

}
