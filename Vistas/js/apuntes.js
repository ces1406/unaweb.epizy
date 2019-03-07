var main=function(){
	ponerFecha();
	controlarUsuario();
	if(document.getElementById('idDeApunte')!=null) borrado();
};
function borrado(){
	var vecFormus = document.getElementsByClassName('formuBorrar');
	for (let i = 0; i < vecFormus.length; i++) {
		vecFormus[i].addEventListener('submit',ponerBorrado,false);		
	}
}
function ponerBorrado(event){
	event.preventDefault();
	var idApunte =this.getElementsByTagName('input')[0].attributes['value'].value;

	var conte ='<div class="badge badge-primary text-wrap esquinaDer2" style="background-color: rgba(21, 24, 29, 0.9);font-size:1.7ex !important">';
		conte += '<form class="form-inline" id="" action="/Administrar/EliminarApunte/'+idApunte+'" method="POST" enctype="multipart/form-data">';
		conte += '	<h3>El comentario se eliminara permanentemente, esta seguro de borrarlo? &nbsp; </h3> ';
		conte +='	<div class="custom-control custom-radio custom-control-inline"><input type="radio" id="si" name="confirmado" class="custom-control-input" value="si"><label class="custom-control-label" for="si">Si</label></div>';
		conte +='	<div class="custom-control custom-radio custom-control-inline"><input type="radio" id="no" name="confirmado" class="custom-control-input" value="no"><label class="custom-control-label" for="no">No</label></div>';
		conte +='	<button type="submit" id="BorrarCurso" value="Borrar" class="btn btn-sm enlace" style="font-size: 1.6ex;">OK</button>';
		conte +='	<div class="form-group mx-sm-3 mb-2 py-0 my-0" id="" >';
		conte +='		<input type="password" class="form-control py-0 my-0" name="unaPassword1" placeholder="password de Admin" style="font-size: 1.6ex;"required>';
		conte +='	</div></form> </div>';		
	this.outerHTML =conte;
	return false;
}
function limitaTextoTituloYAutor(){
	if(this.value.length>99){
		this.value=null;
	}else{
		return true;
	}
}
function limitaDirUrl(){
	if(this.value.length>144){
		this.value=null;
	}else{
		return true;
	}
}
function controlarUsuario(){
	var formu2=document.getElementById('unFormulario2');

	if(formu2!=null){
		formu2.addEventListener('submit',chequearCampos2,false);
		var titulo2=document.getElementById('titulo2');
		var autor2=document.getElementById('autor2');
		var url=document.getElementById('ubicacionUrl');		
		autor2.addEventListener('keypress',limitaTextoTituloYAutor,false);
		titulo2.addEventListener('keypress',limitaTextoTituloYAutor,false);
		url.addEventListener('keypress',limitaDirUrl,false);
	}	
}
function chequearCampos2(event){
	var titulo=document.getElementById('titulo2');
	var autor=document.getElementById('autor2');
	var url=document.getElementById('ubicacionUrl');
	var cancelar=false;

	if(titulo.value.length<2||titulo.value==null){
		var small2=document.getElementById('avisoTitulo');
		small2.textContent='OJO, debes indicar un titulo';
		small2.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
		titulo.value=null;
		cancelar=true
	}
	if(autor.value.length<2||autor.value==null){
		var small2=document.getElementById('avisoAutor');
		small2.textContent='OJO, debes indicar un autor';
		small2.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
		autor.value=null;
		cancelar=true
	}
	if(cancelar){
		event.preventDefault();
		return false;
	}
	return true;
}
function ponerFecha(){
	var var2=document.getElementById("fecha");
	var ahora= new Date();
	var dia=ahora.getDate().toString();
	var mes=ahora.getMonth()+1;
	var anio=ahora.getFullYear().toString();
	var fecha=dia+'-'+mes.toString()+'-'+anio;
	var2.appendChild(document.createTextNode(fecha));
}

window.onload=main();