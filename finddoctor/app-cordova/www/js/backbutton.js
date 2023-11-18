/*Script backbutton Android*/

document.addEventListener("deviceready", onDeviceReady, false);

function onDeviceReady()
{ 
   document.addEventListener("backbutton", onBackKeyDown, false); 
} 
function onBackKeyDown() 
{ 
  if($('.dialog').hasClass('modal-in'))
  {
    app.dialog.close()
    return
  }
	if($('.login-screen').hasClass('modal-in'))
	{
    app.loginScreen.close()
    return
	}
	if($('.popup').hasClass('modal-in'))
	{
  	app.popup.close()
  	return
	}
  if($('[data-name="login"]').hasClass('page-current') || $('[data-name="home"]').hasClass('page-current'))
  {
    homeView.router.history = []
    app.methods.tryGoOut()
    return
  }
	app.router.back()
	window.history.pushState('forward', null, './index.html');
}