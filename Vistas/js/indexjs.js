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

var main=function(){
	ultimosComentarios();

	setInterval(ultimosComentarios,500000); //50 segs.
	var fecha1 = new fecha(new Date());
	fecha1.actualizar();
}

function Comentario (aut,cad,fec,tem,secc,idTe,idCa,idCu,codCu,catCu,matCu,catOp,matOp,profs,cant){
	this.autor=aut;
	this.cadenaCruda=cad;
	this.fecha=fec;
	this.tema=tem;
	this.seccion=secc;
	this.idTema=idTe;
	this.idCatedra=idCa;
	this.idCurso=idCu;
	this.codCurso=codCu;
	this.catedraCurso=catCu;
	this.materiaCurso=matCu;
	this.catedraOp=catOp;
	this.materiaOp=matOp;
	this.profsOp=profs;
	this.cantComents=cant;
	this.pie=null;
	this.enlace=null;	
	this.crearBaseOpinion=function () {
		this.armarFecha();
		var enlace = document.createElement("a");
		enlace.innerHTML += '  <h5 class="esquinaIzq2">Autor: '+this.autor+'</h5>';
		enlace.innerHTML += '<h6 class="esquinaDer2">'+this.fecha+'</h6>';
		enlace.innerHTML += '<br/><br/>';
		enlace.innerHTML += '<div class="contenido2">'+this.cadenaCruda+'</div>';
		enlace.innerHTML += ' </br><small class="pie2">'+ this.pie + '</small>';
		enlace.innerHTML += '<img class="card-img-top separador" src="/Vistas/imagenes/item12.png" height="35" alt="Card image cap">';	
		enlace.className='enlace';
		enlace.href = this.enlace;
		return enlace;
	}
	this.armarFecha=function () {
		this.fecha=this.fecha.substring(8,10)+'/'+this.fecha.substring(5,7)+'/'+this.fecha.substring(0,4)+this.fecha.substring(10,19);
	}
	this.armarPieYLink=function () {
        var pag;
        ((this.cantComents%10)==0) ? pag = this.cantComents/10: pag = Math.trunc((this.cantComents/10))+1;
				
		if(this.idTema != null && this.idTema.length !=0){
			this.enlace ='/Seccion/irTema/'+this.seccion+'/'+this.idTema+'/'+pag;
			this.pie = 'Seccion: '+this.seccion+' Tema: '+this.tema;
		}else if(this.idCurso!=null && this.idCurso.length!=0){
			this.enlace ='/Seccion/Curso/'+this.idCurso+'/'+pag;
			this.pie = 'Seccion: '+this.seccion+' Curso:'+this.codCurso+'(cod.curso) Materia:'+this.materiaCurso;
			this.pie += ' Catedra: '+this.catedraCurso;
		}else if(this.idCatedra != null && this.idCatedra.length != 0){
			this.enlace = '/Seccion/irHiloOpinion/'+this.idCatedra+'/'+pag;
			this.pie = 'Seccion: '+this.seccion+' Materia: '+this.materiaOp+' Catedra: '+this.catedraCurso;
			this.pie += ' Profesores: '+this.profsOp;
		}
	}
	this.limpiarComent=function (anchor) {
		var vecImgs = this.cadenaCruda.match(/&lt;img /gi);
		if( vecImgs!=null ){
			/* hay imagenes en el comentario */
			var inicio=0;
			var clas=' class="img-fluid img-thumbnail mx-auto d-block" ';
			for(i=0;i<vecImgs.length;i++){
				var posRelImg = this.cadenaCruda.substring(inicio,this.cadenaCruda.length).search(/&lt;img /i);
				//Tema:alto y ancho adecuados
				var posRelAncho = this.cadenaCruda.substring(inicio,this.cadenaCruda.length).search(/width:/gi);
				var posRelAlto = this.cadenaCruda.substring(inicio,this.cadenaCruda.length).search(/height:/gi);
				//posicionReal=inicio+posRelAncho
				var j=0;
				var ancho='';
				while(/^([0-9])*$/.test(this.cadenaCruda[posRelAncho+inicio+6+j])){
					ancho += this.cadenaCruda[posRelAncho+inicio+6+j];
					j++;
				};
				j=0;
				var alto='';
				while(/^([0-9])*$/.test(this.cadenaCruda[posRelAlto+inicio+7+j])){
					alto += this.cadenaCruda[posRelAlto+inicio+7+j];
					j++;
				};											
				var anchoOrig = ancho;
				var altoOrig = alto; 
				//limite de ancho: no mas que el 70% del ancho de "panelDer"
				//limite de alto: no mas que el 80% del ancho de "panelDer"
				var limitAncho=Math.trunc((65*anchor)/100);
				var limitAlto =Math.trunc((60*anchor)/100);	
				var ratio = ancho/alto;
				if(ancho>limitAncho){//corregir ancho
					ancho=limitAncho;
					alto =Math.trunc(ancho/ratio); 					
				}
				if(alto>limitAlto){//corregir alto
					alto=limitAlto;
					ancho=Math.trunc(alto/ratio);
				}			
				this.cadenaCruda =this.cadenaCruda.substring(0,posRelAncho+inicio)+this.cadenaCruda.substring(posRelAncho+inicio,this.cadenaCruda.length).replace(anchoOrig,ancho);
				this.cadenaCruda =this.cadenaCruda.substring(0,posRelAlto+inicio)+this.cadenaCruda.substring(posRelAlto+inicio,this.cadenaCruda.length).replace(altoOrig,alto);
				
				var esFluid = this.cadenaCruda.substring(inicio,this.cadenaCruda.length).search(/class="/g);
				if(esFluid === -1){
					this.cadenaCruda = this.cadenaCruda.substring(0,posRelImg+7+inicio)+clas+this.cadenaCruda.substring(posRelImg+7+inicio,this.cadenaCruda.length);
				}
				(posRelAlto<posRelAncho)? inicio +=posRelAncho+56:inicio+=posRelAlto;
			}
		}
		this.cadenaCruda = this.cadenaCruda.replace(/&lt;/g,'<');
		this.cadenaCruda = this.cadenaCruda.replace(/&gt;/g,'>');
		this.cadenaCruda = this.cadenaCruda.replace(/&quot;/g,'"');
		this.cadenaCruda = this.cadenaCruda.replace(/&amp;/g,'&');
	}
}

function ultimosComentarios(){
	var peticion=new XMLHttpRequest();
	peticion.open('get','/UltimosComents',true);
	peticion.send(null);
	peticion.onreadystatechange= function (){
		if(peticion.readyState==4){
			if(peticion.status==200){
				var rta= JSON.parse(peticion.responseText);	
				var listado = document.getElementById('ultimosComentarios');
				listado.innerHTML='';
				var anchoTot = listado.clientWidth;
				for( var i=0; i<=rta.length-1; i++ ) {
					e = rta[i];
					var com=new Comentario (e.autor,e.contenido,e.fechaHora,e.tema,e.seccion,e.idTema,e.idCatedra,e.idCurso,e.codigoCurso,e.catedraCurso,e.materiaCurso,e.catedraOpinion,e.materiaOpinion,e.profesoresOpinion,e.cantComents);
				
com.armarPieYLink();
					com.limpiarComent(anchoTot);					
					listado.appendChild(com.crearBaseOpinion());					
				}
			}
		}
	}
}

window.onload=function(){
	main();
}