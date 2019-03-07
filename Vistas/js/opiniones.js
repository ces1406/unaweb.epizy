var main=function(){
	ponerFecha();
	if(document.getElementById('opinionesCargadas')!=null){
		ajustarComentarios();
		redimensionarImgsComentsOdinarios();		
	}
	if(document.getElementById('ultimosComentarios')!=null){
		redimensionarImgsUltimosComents();
	}	
	if(document.getElementById('idDeComent')!=null) borrado();
	if(document.getElementById('crearComentario')!=null) controlarTextArea();
	
	controlarUsuario();
}
function ajustarComentarios(){
	var anchor = document.getElementById('comentarioInicial').clientWidth;
	var coments = document.getElementsByClassName('comentario1');
	var contenedores = document.getElementsByClassName('contenedor1');
	for (let i = 0; i < coments.length; i++) {
		var element = coments[i];
		element.style.cssText = 'max-width: '+(85*anchor/100)+'px !important;'; //el 85% del anchor		
		element.style.cssText = 'min-width: '+(85*anchor/100)+'px !important;';
	}
	for (let i = 0; i < contenedores.length; i++) {
		var element = contenedores[i];
		element.style.cssText = 'min-width: '+(85*anchor/100)+'px !important;'; //el 85% del anchor	
		element.style.cssText = 'max-width: '+(85*anchor/100)+'px !important;';	
	}
}
function borrado(){
	var vecFormus = document.getElementsByClassName('formuBorrar');
	for (let i = 0; i < vecFormus.length; i++) {
		vecFormus[i].addEventListener('submit',ponerBorrado,false);		
	}
}
function ponerBorrado(event){
	event.preventDefault();
	var idComentario =this.getElementsByTagName('input')[0].attributes['value'].value;
	var idCatedra=document.getElementById('idDeTema').attributes['value'].value; //es el idCatedra aunque en el form lo nombre como idDeTema
	var pag=document.getElementById('idDePag').attributes['value'].value;

	var conte ='<div class="badge badge-primary text-wrap esquinaDer2" style="background-color: rgba(21, 24, 29, 0.9);font-size:1.7ex !important">';
		conte += '<form class="form-inline" id="" action="/Administrar/EliminarComentCatedra/'+idComentario+'/'+idCatedra+'/'+pag+'" method="POST" enctype="multipart/form-data">';
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
function limitaTextoMateriaYCatedra(){
	if(this.value.length>59){
		this.value=null;
	}else{
		return true;
	}
}
function limitaTextoProfe(){
	if(this.value.length>99){
		this.value=null;
	}else{
		return true;
	}
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
	var limitAncho=Math.trunc((6*anchor)/10);
	var limitAlto =Math.trunc((7*anchor)/10);
	if(ancho>limitAncho){//corregir ancho
		ancho=limitAncho;
		alto =Math.trunc(ancho/ratio); 					
	}
	if(alto>limitAlto){//corregir alto
		alto=limitAlto;
		ancho=Math.trunc(alto/ratio);
	}
	cadena.style.width = ancho+'px';
	cadena.style.height= alto+'px';
}
function redimensionarImgsComentsOdinarios() {	
	var anchor = document.getElementById('opinionesCargadas').clientWidth;
	var comentarios = document.getElementsByClassName('elComentario');
	for (let i = 0; i < comentarios.length; i++) {
		const element0 = comentarios[i];
		var imgs = element0.getElementsByTagName('img');
		for (let n = 0; n < imgs.length; n++) {
			var element1 = imgs[n];	
			cambiarDimension(anchor,element1);		
		}		
	}
}
function redimensionarImgsUltimosComents(){
	var mensajes=document.getElementById('ultimosComentarios');
	var anchor = mensajes.clientWidth;
	if(mensajes!=null){
		var imagenes=mensajes.getElementsByTagName('img');
		for(var i=0;i<imagenes.length;i++){
			var contenido=imagenes[i];
			cambiarDimension(anchor,contenido);
					
		}
	}
}
function controlarUsuario(){
	var formu2=document.getElementById('unFormulario2');
	if(formu2!=null){		
		formu2.addEventListener('submit',chequearCampos2,false);
		var materia2=document.getElementById('materia2');
		var catedra2=document.getElementById('catedra2');
		var profesor2=document.getElementById('profesor2');
		materia2.addEventListener('keypress',limitaTextoMateriaYCatedra,false);
		catedra2.addEventListener('keypress',limitaTextoMateriaYCatedra,false);
		profesor2.addEventListener('keypress',limitaTextoProfe,false);
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
function chequearCampos2(event){
	var materia2=document.getElementById('materia2');
	var catedra2=document.getElementById('catedra2');
	//var profesor2=document.getElementById('profesor2');
	var cancelar=false;
	
	if(materia2.value.length<4||materia2.value==null){
		var small2=document.getElementById('avisoMateria2');
		small2.textContent='OJO, debes indicar una materia';
		small2.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
		materia2.value=null;
		cancelar=true
	}
	if(catedra2.value.length<3||catedra2.value==null){
		var small3=document.getElementById('avisoCatedra2');
		small3.textContent='OJO, debes indicar una catedra';
		small3.setAttribute("style","color:#FB9209 !important;font-weight:bold !important;");
		/*materia.value=null;*/
		catedra2.value=null;
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

window.onload=function(){
	if(document.getElementById('area1')!=null){
		CKEDITOR.replace('area1');
	}
	main();
}