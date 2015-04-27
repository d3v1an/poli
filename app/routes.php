<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// Pruebas
Route::get('/test', function()
{
    
    //return $created->diff($now)->days;

    $pieces = Piece::with('actor','topic','type','audits')
                  //->where( DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')") , "=", Carbon::today()->toDateString() )
                  ->whereBetween( DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')") , array('2015-04-20','2015-04-21') )
                  ->get();
                  
    $data   = array();

    foreach ($pieces as $p) {

        // Si no esta ligado a una nota lo omitimos
        if(count($p->audits)<1) continue;

        // Formamos la salida
        $md                     = array();
        $md['tipo']             = $p->type->name;
        $md['actor']            = ($p->actor->id==1?'CPA':'JGM');
        $md['calificacion']     = ($p->status=='p'?'Positivo':($p->status=='n'?'Negativo':'Neutral'));

        $mamo = null;

        foreach ($p->audits as $a) {

            $_note  = null;

            // $created    = new Carbon(date('Y-m-d',strtotime($_note->Fecha)));
            $created    = new Carbon($a->created_at);
            $now        = Carbon::now();

            // if(!$_note) {
            //     $mamo = $created->diff($now)->days;
            //     break;
            // }

            if($created->diff($now)->days < 1) {
                $_note  = NoticiasDia::with('periodico')->find($a->note_id);
            } else if($created->diff($now)->days >= 1 && $created->diff($now)->days < 7) {
                $_note  = NoticiasSemana::with('periodico')->find($a->note_id);
            } else if ($created->diff($now)->days >= 7) {
                $_note  = NoticiasMensual::with('periodico')->find($a->note_id);
            }

            if(!isset($_note->Fecha)) {
                $mamo = $_note;
                break;
            }
            
            $md['fecha']        = $_note->Fecha;
            $md['autor']        = ucwords(strtolower($_note->Autor));
            $md['periodico']    = $_note->periodico->Nombre;
            $md['titulo']       = $_note->Titulo;
            $md['pdf']          = ($_note->Categoria==80 || $_note->Categoria==98 ? $_note->Encabezado : "http://www.gaimpresos.com/Periodicos/".$_note->periodico->Nombre.'/'.$_note->Fecha.'/'.$_note->NumeroPagina);
        }

        $data[]                 = $md;

        if(!is_null($mamo)) {
            $data = $mamo;
            break;
        }

    }

    return $data;

    $file_name = 'Reporte Sonora ' . date('Y-m-d.H-i-s');

    Excel::create($file_name, function($excel) use($data) {

        $excel->sheet('Reporte', function($sheet) use($data) {

            // Creamos las celdas superiores
            $sheet->row(1, array(
                'Fecha',
                'Medio',
                'Tipo',
                'Autor',
                'Actor',
                'Titulo',
                'PDF',
                'Calificacíon'
            ));

            // Auto filtrado
            $sheet->setAutoFilter();

            // Definimos el ancho para las celdas
            $sheet->setWidth(array(
                'A' => 12,
                'B' => 30,
                'C' => 17,
                'D' => 22,
                'E' => 9,
                'F' => 40,
                'G' => 40,
                'H' => 14
            ));

            // Definimos la altura de las celdas d elos filtros
            $sheet->setHeight(1,20);

            // Freeze first row
            $sheet->freezeFirstRow();

            // A ->
            $sheet->cells('A1', function($cell) {
                $cell->setFontWeight('bold');
                $cell->setFontSize(13);
            });
            $sheet->cells('A1:A2000', function($cell) {
                $cell->setAlignment('center');
                $cell->setValignment('middle');
            });

            // B ->
            $sheet->cells('B1', function($cell) {
                $cell->setFontWeight('bold');
                $cell->setFontSize(13);
            });

            // C ->
            $sheet->cells('C1', function($cell) {
                $cell->setFontWeight('bold');
                $cell->setFontSize(13);
            });

            // D ->
            $sheet->cells('D1', function($cell) {
                $cell->setFontWeight('bold');
                $cell->setFontSize(13);
            });
            $sheet->cells('D1:D2000', function($cell) {
                $cell->setAlignment('center');
                $cell->setValignment('middle');
            });

            // E ->
            $sheet->cells('E1', function($cell) {
                $cell->setFontWeight('bold');
                $cell->setFontSize(13);
            });
            $sheet->cells('E1:E2000', function($cell) {
                $cell->setAlignment('center');
                $cell->setValignment('middle');
            });

            // E ->
            $sheet->cells('F1', function($cell) {
                $cell->setFontWeight('bold');
                $cell->setFontSize(13);
            });

            // F ->
            $sheet->cells('G1', function($cell) {
                $cell->setFontWeight('bold');
                $cell->setFontSize(13);
            });

            // G ->
            $sheet->cells('H1', function($cell) {
                $cell->setFontWeight('bold');
                $cell->setFontSize(13);
            });
            $sheet->cells('H1:H2000', function($cell) {
                $cell->setAlignment('center');
                $cell->setValignment('middle');
            });

            /**
             * Altura del la primer celda (Las demas heredan su tamaño)
             */
            for($i = 2; $i < 2000; $i++) {
                $sheet->setHeight($i, 17);
            }

            $i=2;
            foreach ($data as $d) {
                $sheet->row($i, array(

                    $d["fecha"],
                    $d["periodico"],
                    $d["tipo"],
                    $d["autor"],
                    $d["actor"],
                    $d["titulo"],
                    $d["pdf"],
                    $d["calificacion"]

                ));
                $i++;
            }
            
        });

    })->export('xls');
});

// Entrada Inicial
Route::get('/', function()
{
    return Redirect::to('cp');
});

// Test d enoticias api?
Route::get('/notitest/{id}', 'AjaxController@getNotice')->where('id', '[0-9]+');

// Formulario de inicio de sesion
Route::get('adm/login', 'AdminAuthController@login');
// Inicio de session
Route::post('adm/login', 'AdminAuthController@authLogin');

// Panel de control de usuarios / modelos / auditores / administradores
Route::group(['prefix' => 'cp','before' => 'auth.cp'], function ()
{
    // Dashboard
    Route::get('/', 'ControlPanelController@reportPrinted');

    // Peronajes

        // Usuarios - administracion
        Route::get('/character/{id}', 'CharacterController@character');//->where('id', '[0-9]+');

    // Administracion de usuarios

        // Usuarios - administracion
        Route::get('/users', 'UserController@users');

    	// Usuarios - agregado de usuarios
        Route::post('/users', 'UserController@addUser');

        // Usuarios - Perfil de usuarios
        Route::get('/users/{id}', 'UserController@profileUser')->where('id', '[0-9]+');

        // Usuarios / carga - carga de usuarios para tabla de administracion
        Route::get('/users/load', 'UserController@loadUsers');

        // Usuarios / del - elimina un usuario de la base de datos
        Route::post('/users/del', 'UserController@delUser');

        // Usuarios / edit - edicion de informacion de usuarios
        Route::post('/users/edit', 'UserController@editUser');

        // Usuarios / info - obtenemos informacion del usuario
        Route::post('/users/info', 'UserController@infoUser');

        // Usuario - actualiza la informacion de el usuario activo
        Route::post('/user', 'UserController@updateOwnUser');

    

    // Session y roles de usuarios

        // Cierre de session
        Route::get('/logout', 'AdminAuthController@logout');

        // Roles / carga - carga de roles para tabla de administracion
        Route::get('/roles/load', 'RoleController@loadRoles');

        // Roles / carga - carga de roles para tabla de administracion
        Route::post('/roles', 'RoleController@addRoles');

        // Roles / edit - carga de roles para tabla de administracion
        Route::post('/roles/edit', 'RoleController@editRoles');

        // Roles / info - obtenemos informacion del rol
        Route::post('/roles/info', 'RoleController@infoRoles');

        // Roles / del - elimina un rol de la base de datos
        Route::post('/roles/del', 'RoleController@delRoles');

        // Roles / get - obtenemos los roles disponibles
        Route::get('/roles/get', 'RoleController@getRoles');

    // Reportea

        // Reporte de analisis
        //Route::get('/report', 'ControlPanelController@report');

        // Impresos

            // Reporte de impresos
            Route::get('/report/printed', 'ControlPanelController@reportPrinted');

            // Reporte de analisis
            Route::get('/report/printed/{aid}:{data_init}:{data_end}', 'ControlPanelController@reportPrintedRange');

            // Reporte de analisis argumentado
            Route::get('/report/printed/{actor}', 'ControlPanelController@reportPrintedArgs')->where('actor', '[0-9]+');

        // Electronicos

            // Reporte de electronicos
            Route::get('/report/electronics', 'ControlPanelController@reportElectronic');

            // Reporte de analisis
            Route::get('/report/electronics/{aid}:{data_init}:{data_end}', 'ControlPanelController@reportElectronicRange');

            // Reporte de analisis argumentado
            Route::get('/report/electronics/{actor}', 'ControlPanelController@reportElectronicArgs')->where('actor', '[0-9]+');

    // Excell

        // exportar a excel tipo "a" id's
        Route::get('/excel/export/ta/{actor}:{ids}', 'ControlPanelController@excelIdsTa');

        // exportar a excel tipo "a" completo
        Route::get('/excel/export/ta/{actor}', 'ControlPanelController@excelFullTa');

        // exportar a excel
        Route::get('/excel/export/tb/{actor}', 'ControlPanelController@excelFullTb');

        // exportar a excel
        Route::get('/excel/export/tb-full', 'ControlPanelController@excelFullTb2');

    // Electronicos

        // Captura de electronicos
        Route::get('/electronic', 'ElectronicController@capture');

        // Download de electronicos
        Route::get('/electronic/download/{file}', 'ElectronicController@mediaDownload');

        // Captura de electronicos
        Route::post('/electronic/add_note', 'ElectronicController@addNote');

        // Remocion de una pieza de auditoria
        Route::post('/electronic/rpiece', 'ElectronicController@removePiece');

        // Actualizacion de una pieza de auditoria
        Route::post('/electronic/upiece', 'ElectronicController@updatePiece');

    // Impresos

        // Remocion de una pieza de auditoria
        Route::post('/printed/rpiece', 'CharacterController@removePiece');

        // Actualizacion de una pieza de auditoria
        Route::post('/printed/upiece', 'CharacterController@updatePiece');

    // Contenido miscelaneo

        // Cierre de session
        Route::get('/misc/catalogs', 'MiscController@catalogs');

        // Agregar fuente
        Route::post('/misc/catalogs/source', 'MiscController@addSource');
        // Agregar programa
        Route::post('/misc/catalogs/program', 'MiscController@addProgram');
        // Agregar comunicador (Locutor principal)
        Route::post('/misc/catalogs/comunicator', 'MiscController@addComunicator');

        // Obtener las fuentes
        Route::get('/misc/catalogs/sources', 'MiscController@getSources');
        // Obtener los programas
        Route::get('/misc/catalogs/programs', 'MiscController@getPrograms');
        // obtener comunicadores
        Route::get('/misc/catalogs/comunicators', 'MiscController@getComunicators');
});

// Peticiones miscelaneas
Route::group(['prefix' => 'ajax'], function () {

    // Character - contadores de notas por personaje
    Route::get('charcounter/{id}', 'AjaxController@characterCounter');

    // Character - contadores de notas por personaje
    Route::get('data/{id}', 'AjaxController@characterData');

    // Current ids - Obtenemos los id de lso registros ya auditados
    Route::get('cur_ids/{id}', 'AjaxController@cursIds');

    // Character - contadores de notas por personaje
    Route::get('reloadcache', 'AjaxController@reloadCache');

    // Programs - programas
    Route::get('programs', 'AjaxController@programs');

    // Comunicators - comunicadores
    Route::get('comunicators', 'AjaxController@comunicators');

    // Actors - actores
    Route::get('actors', 'AjaxController@actors');

    // Temes - topics
    Route::get('themes', 'AjaxController@themes');

    // types - tipo
    Route::get('types', 'AjaxController@types');

    // Agregamos un nuevo actor
    Route::post('add_actor', 'AjaxController@addActor');

    // Agregamos un nuevo topico
    Route::post('add_tema', 'AjaxController@addTopic');

    // Agregamos un nuevo tipo
    Route::post('add_type', 'AjaxController@addType');

    // Agregamos un nuevo analisis
    Route::post('add_audit', 'AjaxController@addAudit');

    // Verifica si una nota existe en la base de datos local
    Route::get('note_check', 'AjaxController@noteCheck');

});