<?php
class ListaMaterias{
    public $listadoHtml;

    public function __construct(){
        //$cursor = fopen(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/listaMaterias');
        $materias = file(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/listaMaterias');
        foreach ($materias as $materia) {
            $this->listadoHtml .= '<option>'.$materia.'</option>';
        }
    }
}
?>