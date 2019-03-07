var main=function(){
	var fecha1 = new fecha(new Date());
	fecha1.actualizar();
	controlarUsuario();
}
var fecha= function(date){
	this.dia=date.getDate().toString();
	this.mes=(date.getMonth()+1).toString();
	this.anio=date.getFullYear().toString();
	this.actualizar = function(){
		var fecha = document.getElementById("fecha");
		var txt = this.dia+'-'+this.mes+'-'+this.anio;
		fecha.appendChild(document.createTextNode(txt));
	}
}
function controlarUsuario(){
	var user=document.getElementById('apodo');
	var pass=document.getElementById('password')
	var formu=document.getElementById('inicioSesion');
	user.addEventListener('keypress',limitaTextoApodo,false);
	pass.addEventListener('keypress',limitaTextoPass,false);
	formu.addEventListener('submit',chequearCampos,false);
}
function chequearCampos(event){
	var user=document.getElementById('apodo');
	var pass=document.getElementById('password');
	
	if(user.value.length==0 || user.value==null){
		var small1=document.getElementById('avisoApodo');
		small1.textContent='NO INDICASTE TU APODO';
		small1.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
		event.preventDefault();		
	}else if(pass.value.length<5 || pass.value==null || pass.value.length>8){
		var small2=document.getElementById('avisoPass');
		small2.textContent='LA CONTRASEÑA DEBE TENER ENTRE 5 Y 8 CARACTERES';
		small2.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
		event.preventDefault();
		pass.value=null;
	}else {
		return true;
	}
}
function limitaTextoApodo(){
	if(this.value.length>24){
		this.value=null;
	}else{
		return true;
	}
}
function limitaTextoPass(){
	if(this.value.length>7){
		this.value=null;
	}else{
		return true;
	}
}
window.onload=main();