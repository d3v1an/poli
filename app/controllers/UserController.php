<?php

class UserController extends \BaseController {

	// Administracion de usuarios
	public function users()
	{
		$params = array(
						'active'=>'users'
					   );

		return View::make('cp.users')->with($params);
	}

	// Agregado de usuario nuevo
	public function addUser()
	{
		try {

			$user = User::where('username',Input::get('username'))->first();

			if($user) return Response::json(array('status'=>false,'message'=>'El usuario ya existe en la base de datos'),200);

			$user 					= new User();
			$user->role_id 			= Input::get('role');
			$user->username 		= Input::get('username');
			$user->first_name 		= Input::get('first_name');
			$user->last_name 		= Input::get('last_name');
			$user->password 		= Hash::make(Input::get('password'));

			if($user->save()) return Response::json(array('status'=>true,'message'=>'Usuario agregado.'),200);
			else return Response::json(array('status'=>false,'message'=>'Error al registrar usuario.'),200);

		} catch (Exception $e) {
			return Response::json(array('status'=>false,'message'=>'Error al registrar usuario - ' . $e->getMessage()),200);
		}
	}

	// Listado de usuarios
	public function loadUsers()
	{

		// DataTable params
		$pagina	= Input::get('sEcho');
		$start  = Input::get('iDisplayStart');
		$limit	= Input::get('iDisplayLength');
		$uid 	= (int) Crypt::decrypt(Input::get('uid'));

		//Rows config
		$dic 	= array();

		$dic[0] = "id";
		$dic[1] = "username";
		$dic[2] = "full_name";
		$dic[3] = "role_id";

		// Order
		$order 	= $dic[Input::get('iSortCol_0')]; 
		$by 	= strtoupper(Input::get('sSortDir_0'));

		// Search critelial
		$condition = Input::has('sSearch') && Input::get('sSearch') !="" ? Input::get('sSearch') : null;

		// Total items
		$total 	= 0;
		if(is_null($condition)) $total = User::whereNotIn('id',[$uid])->count();
		else {
			$total = User::with('role')->where('status','=',1)
					->where(function($query) use ($condition) {
						$query->where('first_name','LIKE',"%{$condition}%")
							  ->orWhere('last_name','LIKE',"%{$condition}%")
							  ->orWhere('username','LIKE',"%{$condition}%");
					})
					->whereNotIn('id',[$uid])
					->count();
		}

		// Full result
		$dbUsers = array();

		if(!is_null($condition)) {

			if($limit > -1) {
				$dbUsers = User::with('role')->where('status','=',1)
									->where(function($query) use ($condition) {
										$query->where('first_name','LIKE',"%{$condition}%")
											  ->orWhere('last_name','LIKE',"%{$condition}%")
											  ->orWhere('username','LIKE',"%{$condition}%");
									})
									->whereNotIn('id',[$uid])
									->take($limit)
									->skip($start)
									->orderBy($order,$by)
									->get();
			} else {
				$dbUsers = User::with('role')->where('status','=',1)
									->where(function($query) use ($condition) {
										$query->where('first_name','LIKE',"%{$condition}%")
											  ->orWhere('last_name','LIKE',"%{$condition}%")
											  ->orWhere('username','LIKE',"%{$condition}%");
									})
									->whereNotIn('id',[$uid])
									->orderBy($order,$by)
									->get();
			}

		} else {

			if($limit > -1) {
				$dbUsers = User::with('role')->where('status','=',1)
									->whereNotIn('id',[$uid])
									->take($limit)
									->skip($start)
									->orderBy($order,$by)
									->get();
			} else {
				$dbUsers = User::with('role')->where('status','=',1)
									->whereNotIn('id',[$uid])
									->orderBy($order,$by)
									->get();
			}

		}

		// Users to display
		$users = array();

		foreach ($dbUsers as $_user) {
			
			$user 				= array();
			$user['id']			= $_user->id;
			$user['username']	= $_user->username;
			$user['full_name']	= $_user->first_name . ' ' . $_user->last_name;
			$user['role']		= $_user->role_id;
			$user['role_name']	= $_user->role->name;

			$users[] 			= $user;
		}

		/*
		 * Output
		 */
		$output = array(
			"sEcho" => $pagina,
			"iTotalRecords" => count($users),
			"iTotalDisplayRecords" => $total,
			"aaData" => $users
		);

		return Response::json($output, 200);
	}

	// Funcion para eliminar usuario
	public function delUser()
	{
		
		$id = Input::get('id');
		
		try {

			$user = User::find($id);

			if(!$user) return Response::json(array('status'=>false,'message'=>'El usuario no existe en el sistema'),200);

			$user->status = false;

			if($user->save()) {
				return Response::json(array('status'=>true,'message'=>'Usuario eliminado del sistema'),200);				
			} else {
				return Response::json(array('status'=>false,'message'=>'El usuario no pudo ser eliminado'),200);
			}

		} catch (Exception $e) {
			return Response::json(array('status'=>false,'message'=>'Error al eliminar al usuario seleccionado - [' . $e->getMessage() . ']'),200);	
		}
	}

	// Edicion de usuario
	public function editUser()
	{
		try {

			$user = User::find(Input::get('id'));

			if(!$user) return Response::json(array('status'=>false,'message'=>'El usuario no existe en la base de datos'),200);

			$user->role_id 			= Input::get('role');
			$user->username 		= Input::get('username');
			$user->first_name 		= Input::get('first_name');
			$user->last_name 		= Input::get('last_name');
			if(Input::get('password')!='') $user->password = Hash::make(Input::get('password'));

			if($user->save()) return Response::json(array('status'=>true,'message'=>'Usuario modificado.'),200);
			else return Response::json(array('status'=>false,'message'=>'Error al modificar usuario.'),200);

		} catch (Exception $e) {
			return Response::json(array('status'=>false,'message'=>'Error al modificar usuario - ' . $e->getMessage()),200);
		}
	}

	// Funcion para obtener informacion del usuario
	public function infoUser()
	{
		$id = Input::get('id');

		try {
			
			$user = User::with('role')->find($id);

			if(!$user) return Response::json(array('status'=>false,'message'=>'No existe el usuario en el sistema'),200);

			return Response::json(array('status'=>true,'message'=>'Usuario encontrado','user'=>$user),200);

		} catch (Exception $e) {
			return Response::json(array('status'=>false,'message'=>'Error al buscar al usuario en el sistema [' . $e->getMessage() . ']'),200);
		}
	}

	// Actualizacion de informacion personal
	public function updateOwnUser()
	{
		try {
			
			$user = User::find(Input::get('id'));

			if(!$user) return Response::json(array('status'=>false,'message'=>'No se encontro al usuario en el sistema'),200);

			$user->first_name 		= Input::get('first_name');
			$user->last_name 		= Input::get('last_name');
			if(Input::get('password')!='') $user->password = Hash::make(Input::get('password'));

			if($user->save()) return Response::json(array('status'=>true,'message'=>'Informacion de usuario actualizada'),200);
			else return Response::json(array('status'=>false,'message'=>'La informacion del usuario no pudo ser actualizada, intentelo mas tarde'),200);


		} catch (Exception $e) {
			return Response::json(array('status'=>false,'message'=>'Error al guardar la informacion del usuario [' . $e->getMessage() . ']'),200);	
		}
	}

}
