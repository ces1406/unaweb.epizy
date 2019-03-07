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

function limitaTxt1(){
	if(this.value.length>59){
		this.value=null;
	}else{
		return true;
	}
};

function limitaTxt2(){
	if(this.value.length>39){
		this.value=null;
	}else{
		return true;
	}
};

function controlarUsuario(){
	var mail=document.getElementById('mail');
	var pass0=document.getElementById('password0');
	var pass1=document.getElementById('password1');
	var pass2=document.getElementById('password2');
	var formu1=document.getElementById('unFormulario');
	
	if(pass0!=null){/*esta cambiando la password*/
		pass0.addEventListener('keypress',limitaTxt1,false);
		pass2.addEventListener('keypress',limitaTxt1,false);
		formu1.addEventListener('submit',chequearCampos1,false);
	}else if(mail!=null){/*esta cambiando el mail*/
		mail.addEventListener('keypress',limitaTxt2,false);
		pass1.addEventListener('keypress',limitaTxt1,false);
		formu1.addEventListener('submit',chequearCampos2,false);
	}else if (pass1!=null){ /*se esta cambiando la imagen*/
		pass1.addEventListener('keypress',limitaTxt1,false);
		formu1.addEventListener('submit',chequearCampos3,false);
	}else{
		/*no hacer nada, todavia no se eligio ningun submenu*/
	}
};

function chequearCampos1(event){
	var pass0=document.getElementById('password0');
	var pass1=document.getElementById('password1');
	var pass2=document.getElementById('password2');
	var cancelar=false;
	var small1=document.getElementById('avisoPass1');
	var small0=document.getElementById('avisoPass0');
	var small2=document.getElementById('avisoPass2');
	small1.textContent='';
	small2.textContent='';
	small0.textContent='';
	if (pass1.value.length<6 || pass1.value.length>8||pass1==null){
		small1.textContent='LA CONTRASEÑA DEBEN TENER ENTRE 5 Y 8 CARACTERES';
		small1.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
		pass1.value=null;
		pass2.value=null;
		pass0.value=null;
		cancelar=true;
	}else if (pass0.value.length<6 || pass0.value.length>8||pass0==null){
		small0.textContent='LA CONTRASEÑA DEBEN TENER ENTRE 5 Y 8 CARACTERES';
		small0.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
		pass1.value=null;
		pass2.value=null;
		pass0.value=null
		cancelar=true;
	}else if (pass2.value.length<6 || pass2.value.length>8||pass2==null){
		small2.textContent='LA CONTRASEÑA DEBEN TENER ENTRE 5 Y 8 CARACTERES';
		small2.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
		pass1.value=null;
		pass2.value=null;
		pass0.value=null;
		cancelar=true;
	}else{
		if(!(pass1.value==pass2.value)){
			small2.textContent='LAS DOS CONTRASEÑAS DEBEN COINCIDIR';
			small2.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
			small1.textContent='LAS DOS CONTRASEÑAS DEBEN COINCIDIR';
			small1.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
			pass1.value=null;
			pass2.value=null;
			pass0.value=null;
			cancelar=true;
		}
	}
	if(cancelar){
		event.preventDefault();
		return false;
	}
	return true;
};
function chequearCampos2(event){
	var mail=document.getElementById('mail');
	var pass1=document.getElementById('password1');
	var expReg= new RegExp('^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/');
	var cancelar=false;
	
	if(pass1.value.length<6 || pass1.value==null || pass1.value.length>8){
		var small=document.getElementById('avisoPass1');
		small.textContent='LA CONTRASEÑA DEBEN TENER ENTRE 5 Y 8 CARACTERES';
		small.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
		pass1.value=null;
		cancelar=true;
	}else if(mail.value.length<4 || mail.value==null || mail.value.length>50){
		var small1=document.getElementById('avisoMail');
		small1.textContent='DIRECCION DE MAIL MAL INGRESADA';
		small1.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
		pass1.value=null;
		cancelar=true;
	}else {
		if(expReg.test(mail.value)){
			var small4=document.getElementById('avisoMail');
			small4.textContent='LA DIRECCION DE MAIL TIENE UN FORMATO INCORRECTO';
			small4.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
			mail.value=null;
			pass1.value=null;
			cancelar=true;
		}
	}
	if(cancelar){
		event.preventDefault();
		return false;
	}
	return true;
};
function chequearCampos3(event){
	var img=document.getElementById('unArchImg');
	var pass1=document.getElementById('password1');
	var cancelar=false;
	
	if(pass1.value.length<6 || pass1.value==null || pass1.value.length>8){
		var small=document.getElementById('avisoPass1');
		small.textContent='LA CONTRASEÑA DEBEN TENER ENTRE 5 Y 8 CARACTERES';
		small.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
		pass1.value=null;
		cancelar=true;
	}else if(img.value==null || img.value==''){
		var small1=document.getElementById('avisoImg');
		small1.textContent='DEBES ELEGIR PRIMERO UN ARCHIVO';
		small1.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
		pass1.value=null;
		cancelar=true;
	}
	if(cancelar){
		event.preventDefault();
		return false;
	}
	return true;
};
window.onload=main();