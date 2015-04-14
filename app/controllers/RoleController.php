<?php

class RoleController extends \BaseController {

	// Listado de usuarios
	public function loadRoles()
	{

		// DataTable params
		$pagina	= Input::get('sEcho');
		$start  = Input::get('iDisplayStart');
		$limit	= Input::get('iDisplayLength');

		//Rows config
		$dic 	= array();

		$dic[0] = "id";
		$dic[1] = "name";

		// Order
		$order 	= $dic[Input::get('iSortCol_0')]; 
		$by 	= strtoupper(Input::get('sSortDir_0'));

		// Search critelial
		$condition = Input::has('sSearch') && Input::get('sSearch') !="" ? Input::get('sSearch') : null;

		// Total items
		$total 	= 0;
		if(is_null($condition)) $total = Roles::All()->count();
		else $total = Roles::where('name','LIKE',"%{$condition}%")->count();

		// Full result
		$dbRoles = array();

		if(!is_null($condition)) {

			if($limit > -1) {
				$dbRoles = Roles::where('name','LIKE',"%{$condition}%")
									->take($limit)
									->skip($start)
									->orderBy($order,$by)
									->get();
			} else {
				$dbRoles = Roles::where('name','LIKE',"%{$condition}%")
									->orderBy($order,$by)
									->get();
			}

		} else {

			if($limit > -1) {
				$dbRoles = Roles::where('id','>',0)
									->take($limit)
									->skip($start)
									->orderBy($order,$by)
									->get();
			} else {
				$dbRoles = Roles::where('id','>',0)
									->orderBy($order,$by)
									->get();
			}

		}

		// Users to display
		$roles = array();

		foreach ($dbRoles as $_role) {
			
			$role 				= array();
			$role['id']			= $_role->id;
			$role['name']		= $_role->name;

			$roles[] 			= $role;
		}

		// Ultimo query ejecutado
		$last_query = DB::getQueryLog();

		/*
		 * Output
		 */
		$output = array(
			"sEcho" => $pagina,
			"iTotalRecords" => count($roles),
			"iTotalDisplayRecords" => $total,
			"aaData" => $roles,
			"query" => end($last_query)
		);

		return Response::json($output, 200);
	}

	// Agregado de usuario nuevo
	public function addRoles()
	{
		try {

			$role = Roles::where('name',Input::get('name'))->first();

			if($role) return Response::json(array('status'=>false,'message'=>'El rol ya existe en la base de datos'),200);

			$role 					= new Roles();
			$role->name 			= Input::get('name');

			if($role->save()) return Response::json(array('status'=>true,'message'=>'Rol agregado.'),200);
			else return Response::json(array('status'=>false,'message'=>'Error al registrar rol.'),200);

		} catch (Exception $e) {
			return Response::json(array('status'=>false,'message'=>'Error al registrar rol - ' . $e->getMessage()),200);
		}
	}

	// Edicion de rol
	public function editRoles()
	{
		try {

			$role = Roles::find(Input::get('id'));

			if(!$role) return Response::json(array('status'=>false,'message'=>'El rol no se encuentra registrado'),200);

			$role->name 			= Input::get('name');

			if($role->save()) return Response::json(array('status'=>true,'message'=>'Rol modificado.'),200);
			else return Response::json(array('status'=>false,'message'=>'Error al modificar rol.'),200);

		} catch (Exception $e) {
			return Response::json(array('status'=>false,'message'=>'Error al modificar rol - ' . $e->getMessage()),200);
		}
	}

	// Funcion para obtener informacion del usuario
	public function infoRoles()
	{
		$id = Input::get('id');

		try {
			
			$role = Roles::find($id);

			if(!$role) return Response::json(array('status'=>false,'message'=>'No existe el rol en el sistema'),200);

			return Response::json(array('status'=>true,'message'=>'Rol encontrado','role'=>$role),200);

		} catch (Exception $e) {
			return Response::json(array('status'=>false,'message'=>'Error al buscar el rol en el sistema [' . $e->getMessage() . ']'),200);
		}
	}

	// Funcion para eliminar usuario
	public function delRoles()
	{
		
		try {

			$role = Roles::find(Input::get('id'));

			if(!$role) return Response::json(array('status'=>false,'message'=>'El rol no existe en el sistema'),200);

			if($role->delete()) {
				return Response::json(array('status'=>true,'message'=>'Rol eliminado'),200);				
			} else {
				return Response::json(array('status'=>false,'message'=>'El rol no pudo ser eliminado'),200);
			}

		} catch (Exception $e) {
			return Response::json(array('status'=>false,'message'=>'Error al eliminar al rol seleccionado - [' . $e->getMessage() . ']'),200);	
		}
	}

	// Obtenemos todos los reles disponibles
	public function getRoles()
	{
		try {

			$roles = Roles::all();

			if(!$roles) return Response::json(array('status'=>false,'message'=>'No hay roles disponibles en el sistema'),200);

			return Response::json(array('status'=>true,'message'=>'Roles encontrados','roles'=>$roles),200);
			
		} catch (Exception $e) {
			return Response::json(array('status'=>false,'message'=>'Error al buscar los roles en el sistema [' . $e->getMessage() . ']'),200);
		}
	}

}