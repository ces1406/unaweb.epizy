var main=function(){
	ponerFecha();
	controlarUsuario();
};
function ponerFecha(){
	var var2=document.getElementById("fecha");
	var ahora= new Date();
	var dia=ahora.getDate().toString();
	var mes=ahora.getMonth()+1;
	var anio=ahora.getFullYear().toString();
	var fecha=dia+'-'+mes.toString()+'-'+anio;
	var2.appendChild(document.createTextNode(fecha));
};
function limitaTextoApodo(){
	if(this.value.length>26){
		this.value=null;
	}else{
		return true;
	}
};
function limitaTextoPass(){
	if(this.value.length>7){
		this.value=null;
	}else{
		return true;
	}
};
function limitaTextoDirMail(){
	if(this.value.length>140){
		this.value=null;
	}else{
		return true;
	}
}
function controlarUsuario(){
	var user1=document.getElementById('apodo');
	var pas1=document.getElementById('password1');
	var pas2=document.getElementById('password2');
	var mail1=document.getElementById('mail');
	var formu=document.getElementById('unFormulario');
	if(user1!=null){
		user1.addEventListener('keypress',limitaTextoApodo,false);
		user1.addEventListener('focusout',apodoRepetido,true);
		user1.addEventListener('focusin',limpiarAviso,false);
	}
	if(pas1!=null){
		pas1.addEventListener('keypress',limitaTextoPass,false);
	}
	if(pas2!=null){
		pas2.addEventListener('keypress',limitaTextoPass,false);
	}
	if(mail1!=null){
		mail1.addEventListener('keypress',limitaTextoDirMail,false);
		mail1.addEventListener('focusout',mailRepetido);
		mail1.addEventListener('focusin',limpiarAviso);
	}
	if(formu!=null){
		formu.addEventListener('submit',chequearCampos,false);
	}
}
function chequearCampos(event){
	var user=document.getElementById('apodo');
	var mail=document.getElementById('mail');
	var pass1=document.getElementById('password1');
	var pass2=document.getElementById('password2');
	var cancelar=false;
	var expReg= new RegExp('^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/'); 
	if(user!=null){		
		if(user.value.length==0 || user.value==null){
			var small1=document.getElementById('avisoApodo');
			small1.textContent='NO INDICASTE TU APODO';
			small1.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
			cancelar=true;
		}		
	}
	if(pass1!=null && pass2!=null){
		if(!(pass1.value==pass2.value)){
			var small2=document.getElementById('avisoPass2');
			small2.textContent='LAS DOS CONTRASEÑAS DEBEN COINCIDIR';
			small2.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
			pass1.value=null;
			pass2.value=null;
			cancelar=true;
		}
	}
	if(pass1.value.length<6 || pass1.value==null || pass1.value.length>8){
		var small3=document.getElementById('avisoPass1');
		small3.textContent='LA CONTRASEÑA DEBEN TENER ENTRE 5 Y 8 CARACTERES';
		small3.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
		pass1.value=null;
		pass2.value=null;
		cancelar=true;
	}
	if(mail!=null){
		if(expReg.test(mail.value)){
			var small4=document.getElementById('avisoMail');
			small4.textContent='LA DIRECCION DE MAIL TIENE UN FORMATO INCORRECTO';
			small4.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
			mail.value=null;
			cancelar=true;
		}
	}
	if(cancelar){		
		event.preventDefault();
		return false;
	}	
	return true;
}
function mailRepetido(){
	var mail = this.value;
	var mailok;
	var peticion = new XMLHttpRequest();
	peticion.open('get','/UltimosComents/cheqMail/'+mail,true);
	peticion.send(null);
	peticion.onreadystatechange = function(){
		if(peticion.readyState == 4){
			if(peticion.status == 200){
				var rta = JSON.parse(peticion.responseText);
				mailok = rta.mail;	
				if(mailok==1){
					var small4=document.getElementById('avisoMail');
					small4.textContent='ESTA DIRECCION DE MAIL ESTA SIENDO USADA';
					small4.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
				}else{
				}			
			}
		}
	}
	return mailok;
}
function limpiarAviso(){
	if(this.id=='apodo'){
		var small1=document.getElementById('avisoApodo');
		small1.innerHTML="";
	}else{
		var small4=document.getElementById('avisoMail');
		small4.innerHTML="";
	}
	
}
function apodoRepetido(){
	var apodo = this.value;
	var apodook;
	var peticion = new XMLHttpRequest();
	peticion.open('get','/UltimosComents/cheqApodo/'+apodo,true);
	peticion.send(null);

	peticion.onreadystatechange = function(){
		if(peticion.readyState == 4){
			if(peticion.status == 200){
				var rta = JSON.parse(peticion.responseText);
				apodook= rta.apodo;	
				if(apodook==1){
					var small1=document.getElementById('avisoApodo');
					small1.textContent='ESTE APODO YA ESTA SIENDO USADO';
					small1.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
				}else{
				}
			}
		}
	}	
}
window.onload=main();