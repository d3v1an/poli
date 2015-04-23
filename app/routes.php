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
    $pieces = Piece::with('actor','topic','type','audits')
                  ->where( DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')") , "=", Carbon::today()->toDateString() )
                  ->where('actor_id',1398)
                  ->get();

    return $pieces;
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