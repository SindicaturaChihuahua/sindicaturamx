<?php
define('DMVERSION', 'v1.1.4');

    function getEtapas(){
        $etapas = array("Solventado", "No Solventado", "Turnadas al Ayuntamiento", "Recomendaciones Emitidas", "Denunciada al Ayuntamiento y al Órgano Interno de Control", "Denunciada al Ayuntamiento, al Órgano Interno de Control y a la Auditoría Superior del Estado", "Denunciada al OIC");
        return $etapas;
    }
    function getIconosEtapas(){
        $iconosEtapas = array(
            "Solventado" => array(
                "icono" => '<i class="fas fa-check-circle"></i>',
                "color" => 'green',
				"descripcion" => 'Son aquellas donde todos los aspectos señalados durante nuestra revisión fueron bien justificados por la dependencia.',
            ),
            "No Solventado" => array(
                "icono" => '<i class="fas fa-exclamation-triangle"></i>',
                "color" => 'yellow',
				"descripcion" => 'Es cuando una dependencia no ha emitido respuesta que justifique las observaciones realizadas en nuestra revisión.',
            ),
			"Recomendaciones Emitidas" => array(
                "icono" => '<i class="fas fa-share"></i>',
                "color" => 'orange',
				"descripcion" => 'Son las revisiones en las que no hay irregularidades u observaciones, pero se emiten recomendaciones para mejorar el funcionamiento de la dependencia.',
            ),
            "Turnadas al Ayuntamiento" => array(
                "icono" => '<i class="fas fa-retweet"></i>',
                "color" => 'blue',
				"descripcion" => 'Estas revisiones contienen observaciones que no fueron solventadas por las dependencias y son turnadas al Ayuntamiento para que las analice y determine una posible sanción.',
            ),
            "Denunciada al Ayuntamiento y al Órgano Interno de Control" => array(
                "icono" => '<i class="fas fa-retweet"></i>',
                "color" => 'blue',
				"descripcion" => '',
            ),
            "Denunciada al Ayuntamiento, al Órgano Interno de Control y a la Auditoría Superior del Estado" => array(
                "icono" => '<i class="fas fa-retweet"></i>',
                "color" => 'blue',
				"descripcion" => '',
            ),
            "Denunciada al OIC" => array(
                "icono" => '<i class="fas fa-retweet"></i>',
                "color" => 'blue',
				"descripcion" => '',
            ),

        );
        return $iconosEtapas;
    }
    function get3de3(){
        $tresdetres = array(
            0 => array(
                "nombre" => "Declaración Patrimonial",
                "icono" => '<i class="fas fa-home"></i>',
                "url" => ''
            ),
            1 => array(
                "nombre" => "Declaración de Intereses",
                "icono" => '<i class="fas fa-comment"></i>',
                "url" => ''
            ),
            2 => array(
                "nombre" => "Declaración Fiscal",
                "icono" => '<i class="fas fa-shopping-bag"></i>',
                "url" => ''
            ),
        );
        return $tresdetres;
    }
    function getPartidos(){
        $pp = array(
            "pan" => array(
                "nombre" => 'Partido Acción Nacional',
                "abbr" => "PAN",
                "icono" => URLASSETS.'partidos/pan.jpg'
            ),
            "pri" => array(
                "nombre" => 'Partido Revolucionario Institucional',
                "abbr" => "PRI",
                "icono" => URLASSETS.'partidos/pri.jpg'
            ),
            "morena" => array(
                "nombre" => 'Morena',
                "abbr" => "Morena",
                "icono" => URLASSETS.'partidos/morena.jpg'
            ),
            "pes" => array(
                "nombre" => 'Partido Encuentro Social',
                "abbr" => "PES",
                "icono" => URLASSETS.'partidos/pes.jpg'
            ),
            "pt" => array(
                "nombre" => 'Partido del Trabajo',
                "abbr" => "PT",
                "icono" => URLASSETS.'partidos/pt.jpg'
            ),
            "pvem" => array(
                "nombre" => 'Partido Verde Ecologista de México',
                "abbr" => "PVEM",
                "icono" => URLASSETS.'partidos/pvem.jpg'
            ),
            "independiente" => array(
                "nombre" => 'Independiente',
                "abbr" => "Independiente",
                "icono" => URLASSETS.'partidos/independiente.png'
            ),
        );
        return $pp;
    }
    $partidos_politicos = array(
        "pan" => array(
            "nombre" => 'Partido Acción Nacional',
            "abbr" => "PAN",
            "icono" => URLASSETS.'partidos/pan.jpg'
        ),
        "pri" => array(
            "nombre" => 'Partido Revolucionario Institucional',
            "abbr" => "PRI",
            "icono" => URLASSETS.'partidos/pri.jpg'
        ),
        "morena" => array(
            "nombre" => 'Morena',
            "abbr" => "Morena",
            "icono" => URLASSETS.'partidos/morena.jpg'
        ),
        "pes" => array(
            "nombre" => 'Encuentro Social',
            "abbr" => "PES",
            "icono" => URLASSETS.'partidos/pes.jpg'
        ),
        "pt" => array(
            "nombre" => 'Partido del Trabajo',
            "abbr" => "PT",
            "icono" => URLASSETS.'partidos/pt.jpg'
        ),
        "pvem" => array(
            "nombre" => 'Partido Verde Ecologista de México',
            "abbr" => "PVEM",
            "icono" => URLASSETS.'partidos/pvem.jpg'
        ),
        "independiente" => array(
            "nombre" => 'Independiente',
            "abbr" => "Independiente",
            "icono" => URLASSETS.'partidos/independiente.png'
        ),
    );

	function getEtapasConvocatorias(){
        $etapas = array("Abierta", "Sin Empezar", "Terminada");
        return $etapas;
    }
    function getIconosEtapasConvocatorias(){
        $iconosEtapas = array(
            "Abierta" => array(
                "icono" => '<i class="fas fa-dot-circle"></i>',
                "color" => 'blue',
				"descripcion" => '',
            ),
            "Sin Empezar" => array(
                "icono" => '<i class="fas fa-minus-circle"></i>',
                "color" => 'orange',
				"descripcion" => '',
            ),
			"Terminada" => array(
                "icono" => '<i class="fas fa-check-circle"></i>',
                "color" => 'green',
				"descripcion" => '',
            ),

        );
        return $iconosEtapas;
    }

    function getYears(){
        $years = array_combine(range(date("Y")+5, 2015), range(date("Y")+5, 2015));
        return $years;
    }
    function getColores(){
        return array(
            '#8CA42D',
            '#683E29',
            '#917799',
            '#F7D34C',
            '#6D9CB4',
            '#335289',
            '#ED8172',
        );
    }

    function getCategoriasObras(){
        return array(
            'recoleccion-basura' => 'Recolección de basura',
            'mantenimiento-parques-espacios-publicos' => 'Mantenimiento de parques y espacios públicos',
            'desarrollo-urbano-aprobado' => 'Desarrollo urbano aprobado',
            'mantenimiento-alumbrado-publico' => 'Mantenimiento de alumbrado público',
            'obras-publicas-municipio' => 'Obras públicas del municipio'
        );
    }
?>
