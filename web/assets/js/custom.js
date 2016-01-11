window.onload = function () {
	lang();
    if (fa2 = document.getElementById("fa2")) fa2.addEventListener("click", fa2message);
}

function lang() {
	var base = window.siteSettings.base;
	var defaultLanguages = ["en", "EN", "ru", "RU"];
	var path = window.location.pathname.replace(base, "");
	var route = path.split('/');
	var en = document.getElementById('lang_en');
	var ru = document.getElementById('lang_ru');

	if (defaultLanguages.indexOf(route[1]) != -1) {
		window.siteSettings.lang = route[1];
		path = path.replace(route[1]+'/', "");
		if (route[1] == 'en') document.getElementById('lang_en').className = 'active';
		if (route[1] == 'ru') document.getElementById('lang_ru').className = 'active';
	} else 	{
		document.getElementById('lang_en').className = 'active';
	}
	en.lastChild.href = base+'/en'+path;
	ru.lastChild.href = base+'/ru'+path;
}

function validate(regform){
	var mail = regform.inputEmail.value;
	var pass1 = regform.inputPassword.value;
	var pass2 = regform.confirmPassword.value;
	var img = regform.inputImage.value;	
	var regexEmail = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
	var regexImages = /\.(gif|jpe?g|png|bmp)$/i;
	var errors = '';
	messages  = function() {
		var messages_en = {"wrongmail":"Wrong e-mail address.",
							"passnofit":"Passwords do not match.",
							"wrongimg":"Wrong Image.",
							"sizeimg":"Image size too big"
		}
		var messages_ru = {"wrongmail":"Неправильный e-mail адрес.",
							"passnofit":"Пароли не совпадают.",
							"wrongimg":"Неправильное расширение изображения.",
							"sizeimg":"Размер изображения слишком большой."
		}
		return (window.siteSettings.lang == 'ru' ? messages_ru : messages_en);
	}

	resetError(regform.inputEmail.parentNode);
	resetError(regform.confirmPassword.parentNode);
	resetError(regform.inputImage.parentNode);
	if (!regexEmail.test(mail))	{
		showError(regform.inputEmail.parentNode, messages().wrongmail);
		errors += 'em';
	} 
	if (pass1!=pass2)	{
		showError(regform.confirmPassword.parentNode, messages().passnofit);
		errors += 'ps';
	}
	if (!regexImages.test(img))	{
		showError(regform.inputImage.parentNode, messages().wrongimg);
		errors += 'im';
	} 
	if (regform.inputImage.files[0].size > 262144)	{
		showError(regform.inputImage.parentNode, messages().sizeimg);
		errors += 'im';
	} 

	if (errors=="") return true; else return false;
}

function showError(container, errorMessage) {
	container.className = 'error';
	var msgElem = document.createElement('span');
	msgElem.className = "error-message";
	msgElem.innerHTML = errorMessage;
	container.appendChild(msgElem);
}

function resetError(container) {
	container.className = '';
	if (container.lastChild.className == "error-message") {
		container.removeChild(container.lastChild);
	}
}

function fa2message(){
    document.getElementById("fa2-message").className="alert alert-success";

}
//var el1 = getCookie("cookiename");
function getCookie(name) {
  var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}