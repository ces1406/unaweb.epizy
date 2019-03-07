var main=function(){
	ponerFecha();
	borrado()
	redimensionarImgsComentInicial();
	ajustarComentarios();
	redimensionarImgsComentsOdinarios();
	if(document.getElementById('crearComentario')!=null) controlarTextArea();
}
function borrado(){
	var vecFormus = document.getElementsByClassName('formuBorrar');
	for (let i = 0; i < vecFormus.length; i++) {
		vecFormus[i].addEventListener('submit',ponerBorrado,false);
		
	}
}
function ponerBorrado(event){
		event.preventDefault();
		var idCom=this.getElementsByTagName('input')[0].attributes['value'].value;
		var idTem=document.getElementById('idDeTema').attributes['value'].value;
		var pag=document.getElementById('idDePag').attributes['value'].value;
		var idSec=document.getElementById('idDeSec').attributes['value'].value;

		var conte ='<div class="badge badge-primary text-wrap esquinaDer2" style="background-color: rgba(21, 24, 29, 0.9);font-size:1.7ex !important">';
		conte += '<form class="form-inline" id="" action="/Administrar/EliminarComentario/'+idSec+'/'+idCom+'/'+idTem+'/'+pag+'" method="POST" enctype="multipart/form-data">';
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
function ponerFecha(){
	var var2=document.getElementById("fecha");
	var ahora= new Date();
	var dia=ahora.getDate().toString();
	var mes=ahora.getMonth()+1;
	var anio=ahora.getFullYear().toString();
	var fecha=dia+'-'+mes.toString()+'-'+anio;
	var2.appendChild(document.createTextNode(fecha));
}
function cambiarDimension(anchor,cadena){
	var j=0;
	var ancho='';
	while(/^([0-9])*$/.test(cadena.style.width[j])){
		ancho += cadena.style.width[j];
		j++;
	};
	j=0;
	var alto='';
	while(/^([0-9])*$/.test(cadena.style.height[j])){
		alto += cadena.style.height[j];
		j++;
	};
	var ratio = (ancho / alto);
	var limitAncho=Math.trunc((8*anchor)/10);
	var limitAlto =Math.trunc((8*anchor)/10);
	if(ancho>limitAncho){			//corregir ancho
		ancho=limitAncho;
		alto =Math.trunc(ancho/ratio); 					
	}
	if(alto>limitAlto){				//corregir alto
		alto=limitAlto;
		ancho=Math.trunc(alto/ratio);
	}
	cadena.style.width = ancho+'px';
	cadena.style.height= alto+'px';
}
function redimensionarImgsComentsOdinarios() {	
	var comentarios = document.getElementsByClassName('contenedor1');
	var anchor = document.getElementById('comentarioInicial').clientWidth;
	for (let i = 0; i < comentarios.length; i++) {
		const element0 = comentarios[i];
		var imgs = element0.getElementsByTagName('img');
		for (let n = 0; n < imgs.length; n++) {
			var element1 = imgs[n];	
			cambiarDimension(anchor,element1);		
		}		
	}
}
function redimensionarImgsComentInicial(){
	var comentarioInicial = document.getElementById('comentarioInicial');
	var anchor = comentarioInicial.clientWidth;
	var imgs = comentarioInicial.getElementsByTagName('img');
	for (let i = 0; i < imgs.length; i++) {
		var element = imgs[i];		
		cambiarDimension(anchor,element);
	}
}	
function ajustarComentarios(){
	var anchor = document.getElementById('comentarioInicial').clientWidth;
	var coments = document.getElementsByClassName('comentario1');
	var contenedores = document.getElementsByClassName('contenedor1');
	for (let i = 0; i < coments.length; i++) {
		var element = coments[i];
		element.style.cssText = 'max-width: '+(85*anchor/100)+'px !important;'; //el 85% del anchor		
	}
	for (let i = 0; i < contenedores.length; i++) {
		var element = contenedores[i];
		element.style.cssText = 'min-width: '+(85*anchor/100)+'px !important;'; //el 85% del anchor		
	}
}
function controlarTextArea(){
	var formComent=document.getElementById('crearComentario');
	formComent.addEventListener('submit',chequearTextArea,false);
}
function chequearTextArea(event){
	var aviso=document.getElementById('aviso');
	if (/^\s*$/.test(CKEDITOR.instances.area1.document.getBody().getText())) {
	  	aviso.textContent='debes escribir algo';
	  	event.preventDefault();
	  	return false;
	} else {
	  	return true;
	}
}
window.onload=function(){
	if(document.getElementById('area1')!=null){
		CKEDITOR.replace('area1');
	}
	main();
}