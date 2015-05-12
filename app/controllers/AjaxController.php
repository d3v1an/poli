<?php
class AjaxController extends \BaseController {


	// Obtenemos la informacion del personaje
	public function characterCounter($id)
	{
		$rest 		= cURL::get('http://' . Config::get('rest.ip') . '/siscap.la/public/api/v1/character/' . $id);
		$character 	= json_decode($rest);
		return Response::json($character,200);
	}

	// Obtenemos la informacion  de las notas del personaje
	public function characterData($id)
	{
		$rest 									= cURL::get('http://' . Config::get('rest.ip') . '/siscap.la/public/api/v1/data/' . $id);
		$data 									= json_decode($rest, true);

		if($data['status']==false) return Response::json($data,200);

		$newData 								= array();	
		$newData['status']						= true;
		$newData['message']						= $data['message'];
		$newData['data']						= array();
		$newData['data']['id'] 					= $data['data']['id'];
		$newData['data']['character'] 			= $data['data']['character'];
		$newData['data']['count'] 				= $data['data']['count'];

		$newData['data']['main']['count']		= $data['data']['main']['count'];
		$newData['data']['main']['data']		= array();
		if(count($data['data']['main']['data'])<1) $newData['data']['main']['data'] = array();
		foreach ($data['data']['main']['data'] as $n) {

			$ad 	= Audit::with('Pieces')->where('note_id',$n['idEditorial'])->first();
			$exists = !is_null($ad);

			if($exists) {
				$n['audited'] 	= true;
				$n['pieces'] 	= $ad->pieces;
			} else {
				$n['audited'] 	= false;
				$n['pieces'] 	= array();
			}

			array_push($newData['data']['main']['data'],$n);
		}

		$newData['data']['estados']['count']	= $data['data']['estados']['count'];
		$newData['data']['estados']['data']		= array();
		if(count($data['data']['estados']['data'])<1) $newData['data']['estados']['data'] = array();
		foreach ($data['data']['estados']['data'] as $n) {
			
			$ad 	= Audit::with('Pieces')->where('note_id',$n['idEditorial'])->first();
			$exists = !is_null($ad);

			if($exists) {
				$n['audited'] 	= true;
				$n['pieces'] 	= $ad->pieces;
			} else {
				$n['audited'] 	= false;
				$n['pieces'] 	= array();
			}

			array_push($newData['data']['estados']['data'],$n);
		}

		$newData['data']['revistas']['count']	= $data['data']['revistas']['count'];
		$newData['data']['revistas']['data']	= array();
		if(count($data['data']['revistas']['data'])<1) $newData['data']['revistas']['data'] = array();
		foreach ($data['data']['revistas']['data'] as $n) {
			
			$ad 	= Audit::with('Pieces')->where('note_id',$n['idEditorial'])->first();
			$exists = !is_null($ad);

			if($exists) {
				$n['audited'] 	= true;
				$n['pieces'] 	= $ad->pieces;
			} else {
				$n['audited'] 	= false;
				$n['pieces'] 	= array();
			}

			array_push($newData['data']['revistas']['data'],$n);
		}

		$newData['data']['portales']['count']	= $data['data']['portales']['count'];
		$newData['data']['portales']['data']	= array();
		if(count($data['data']['portales']['data'])<1) $newData['data']['portales']['data'] = array();
		foreach ($data['data']['portales']['data'] as $n) {
			
			$ad 	= Audit::with('Pieces')->where('note_id',$n['idEditorial'])->first();
			$exists = !is_null($ad);

			if($exists) {
				$n['audited'] 	= true;
				$n['pieces'] 	= $ad->pieces;
			} else {
				$n['audited'] 	= false;
				$n['pieces'] 	= array();
			}

			array_push($newData['data']['portales']['data'],$n);
		}
		// return dd($data);
		return Response::json($newData,200);
	}

	// Obtenemos la informacion  de las notas del personaje
	public function characterDataDate($id,$date)
	{
		$rest 									= cURL::get('http://' . Config::get('rest.ip') . '/siscap.la/public/api/v1/data/' . $id . ':' . $date);
		$data 									= json_decode($rest, true);

		//return Response::json(array($data,'mamalon'));

		if($data['status']==false) return Response::json($data,200);

		$newData 								= array();	
		$newData['status']						= true;
		$newData['message']						= $data['message'];
		$newData['data']						= array();
		$newData['data']['id'] 					= $data['data']['id'];
		$newData['data']['character'] 			= $data['data']['character'];
		$newData['data']['count'] 				= $data['data']['count'];

		$newData['data']['main']['count']		= $data['data']['main']['count'];
		$newData['data']['main']['data']		= array();
		if(count($data['data']['main']['data'])<1) $newData['data']['main']['data'] = array();
		foreach ($data['data']['main']['data'] as $n) {

			$ad 	= Audit::with('Pieces')->where('note_id',$n['idEditorial'])->first();
			$exists = !is_null($ad);

			if($exists) {
				$n['audited'] 	= true;
				$n['pieces'] 	= $ad->pieces;
			} else {
				$n['audited'] 	= false;
				$n['pieces'] 	= array();
			}

			array_push($newData['data']['main']['data'],$n);
		}

		$newData['data']['estados']['count']	= $data['data']['estados']['count'];
		$newData['data']['estados']['data']		= array();
		if(count($data['data']['estados']['data'])<1) $newData['data']['estados']['data'] = array();
		foreach ($data['data']['estados']['data'] as $n) {
			
			$ad 	= Audit::with('Pieces')->where('note_id',$n['idEditorial'])->first();
			$exists = !is_null($ad);

			if($exists) {
				$n['audited'] 	= true;
				$n['pieces'] 	= $ad->pieces;
			} else {
				$n['audited'] 	= false;
				$n['pieces'] 	= array();
			}

			array_push($newData['data']['estados']['data'],$n);
		}

		$newData['data']['revistas']['count']	= $data['data']['revistas']['count'];
		$newData['data']['revistas']['data']	= array();
		if(count($data['data']['revistas']['data'])<1) $newData['data']['revistas']['data'] = array();
		foreach ($data['data']['revistas']['data'] as $n) {
			
			$ad 	= Audit::with('Pieces')->where('note_id',$n['idEditorial'])->first();
			$exists = !is_null($ad);

			if($exists) {
				$n['audited'] 	= true;
				$n['pieces'] 	= $ad->pieces;
			} else {
				$n['audited'] 	= false;
				$n['pieces'] 	= array();
			}

			array_push($newData['data']['revistas']['data'],$n);
		}

		$newData['data']['portales']['count']	= $data['data']['portales']['count'];
		$newData['data']['portales']['data']	= array();
		if(count($data['data']['portales']['data'])<1) $newData['data']['portales']['data'] = array();
		foreach ($data['data']['portales']['data'] as $n) {
			
			$ad 	= Audit::with('Pieces')->where('note_id',$n['idEditorial'])->first();
			$exists = !is_null($ad);

			if($exists) {
				$n['audited'] 	= true;
				$n['pieces'] 	= $ad->pieces;
			} else {
				$n['audited'] 	= false;
				$n['pieces'] 	= array();
			}

			array_push($newData['data']['portales']['data'],$n);
		}
		// return dd($data);
		return Response::json($newData,200);
	}

	// Obtenemos los ids auditados
	public function cursIds($id) {
		$ids = Audit::where( DB::raw('DATE_FORMAT(created_at,\'%Y-%m-%d\')'), '=', DB::raw('CURDATE()') ) ->get();
		return Response::json($ids,200);
	}

	// Obtenemos los ids auditados por fecha
	public function cursIdsDate($id,$date) {
		$ids = Audit::where( DB::raw('DATE_FORMAT(created_at,\'%Y-%m-%d\')'), '=', $date ) ->get();
		return Response::json($ids,200);
	}

	// Recarga de cache
	public function reloadCache()
	{
		$rest 	= cURL::get('http://' . Config::get('rest.ip') . '/siscap.la/public/api/v1/cache/reload');
		$data 	= json_decode($rest);
		return Response::json($data,200);
	}

	// Obtenemos la informacion del personaje
	public function getNotice($id)
	{
		$rest 		= cURL::get('http://' . Config::get('rest.ip') . '/siscap.la/public/api/v1/notice/' . $id);
		$character 	= json_decode($rest);
		return Response::json($character,200);
	}	

	// Obtenemos un arreglo con los actores en base de datos
	public function programs()
	{
		try {
			
			$actors = Program::with('Source','Comunicator')->orderBy('name', 'ASC')->get();

			if(!$actors) return Response::json(array('status' => false, 'message' => 'No hay programas en la base de datos'),200);

			return Response::json(array('status' => true, 'message' => 'Programas localizados', 'actors' => $actors),200);

		} catch (Exception $e) {
			return Response::json(array('status' => false, 'message' => 'Ocurrio un problema al obtener el listado de programas', 'exceotion' => $e->getMessage()),200);
		}
	}

	// Obtenemos un arreglo con los actores en base de datos
	public function comunicators()
	{
		try {
			
			$actors = Comunicator::orderBy('name', 'ASC')->get();

			if(!$actors) return Response::json(array('status' => false, 'message' => 'No hay comunicadores en la base de datos'),200);

			return Response::json(array('status' => true, 'message' => 'Comunicadores localizados', 'actors' => $actors),200);

		} catch (Exception $e) {
			return Response::json(array('status' => false, 'message' => 'Ocurrio un problema al obtener el listado de comunicadores', 'exceotion' => $e->getMessage()),200);
		}
	}


	// Obtenemos un arreglo con los actores en base de datos
	public function actors()
	{
		try {
			
			$actors = Actor::orderBy('name', 'ASC')->get();

			if(!$actors) return Response::json(array('status' => false, 'message' => 'No hay actores en la base de datos'),200);

			return Response::json(array('status' => true, 'message' => 'Actores localizados', 'actors' => $actors),200);

		} catch (Exception $e) {
			return Response::json(array('status' => false, 'message' => 'ocurrio un problema al obtener el listado de actores', 'exceotion' => $e->getMessage()),200);
		}
	}

	// Obtenemos un arreglo con los topicos en base de datos
	public function themes()
	{
		try {
			
			$topics = Topic::orderBy('text', 'ASC')->get();

			if(!$topics) return Response::json(array('status' => false, 'message' => 'No hay temas en la base de datos'),200);

			return Response::json(array('status' => true, 'message' => 'Temas localizados', 'actors' => $topics),200);

		} catch (Exception $e) {
			return Response::json(array('status' => false, 'message' => 'ocurrio un problema al obtener el listado de temas', 'exceotion' => $e->getMessage()),200);
		}
	}

	// Obtenemos un arreglo con los tipos en base de datos
	public function types()
	{
		try {
			
			$types = Type::orderBy('name', 'ASC')->get();

			if(!$types) return Response::json(array('status' => false, 'message' => 'No hay tipos en la base de datos'),200);

			return Response::json(array('status' => true, 'message' => 'Tipos localizados', 'types' => $types),200);

		} catch (Exception $e) {
			return Response::json(array('status' => false, 'message' => 'ocurrio un problema al obtener el listado de tipos', 'exceotion' => $e->getMessage()),200);
		}
	}

	// Funcion para agregar un nuevo actor
	public function addActor()
	{
		try {
		
			$actor = new Actor();
			$actor->name = Input::get('name');

			if($actor->save())	{
				return Response::json(array('status' => true, 'message' => 'Actor agregado'),200);
			} else {
				return Response::json(array('status' => false, 'message' => 'El actor no pudo ser agregado'),200);
			}

		} catch (Exception $e) {
			return Response::json(array('status' => false, 'message' => 'El actor no pudo ser agregado', 'exception' => $e->getMessage()),200);
		}
	}

	// Funcion para agregar un nuevo tema
	public function addTopic()
	{
		try {
		
			$topic 			= new Topic();
			$topic->text 	= Input::get('text');

			if($topic->save())	{
				return Response::json(array('status' => true, 'message' => 'Tema agregado'),200);
			} else {
				return Response::json(array('status' => false, 'message' => 'El tema no pudo ser agregado'),200);
			}

		} catch (Exception $e) {
			return Response::json(array('status' => false, 'message' => 'El tema no pudo ser agregado', 'exception' => $e->getMessage()),200);
		}
	}

	// Funcion para agregar un nuevo tipo
	public function addType()
	{
		try {
		
			$type 		= new Type();
			$type->name = Input::get('text');

			if($type->save())	{
				return Response::json(array('status' => true, 'message' => 'Tipo agregado'),200);
			} else {
				return Response::json(array('status' => false, 'message' => 'El tipo no pudo ser agregado'),200);
			}

		} catch (Exception $e) {
			return Response::json(array('status' => false, 'message' => 'El tipo no pudo ser agregado', 'exception' => $e->getMessage()),200);
		}
	}

	// Funcoion para agregar un analisis
	public function addAudit()
	{
		try {

			DB::beginTransaction();

			$ctimestamps = Input::get('date');

			if (!Auth::check()) return Redirect::json(array('status' => false, 'message' => 'Usuario no autorizado'),200);

			$note_id 				= Input::get('note_id');
			$part_meta 				= explode('|',Input::get('meta'));

			$acts 					= array();

			$done 					= true;

			for ($i=0; $i < count($part_meta); $i++) { 
				
				$spl_pz 			= explode(':',$part_meta[$i]);

				$piece 				= new Piece();
			    $piece->actor_id 	= $spl_pz[0];
			    $piece->topic_id 	= $spl_pz[1];
			    $piece->type_id 	= $spl_pz[2];
			    $piece->status 		= $spl_pz[3];

			    if(!$piece->save()) {
			    	DB::rollback();
			    	$done = false;
			    	break;
			    }

			    if(Input::get('ranged')==true) DB::update( DB::raw("UPDATE pieces SET created_at='{$ctimestamps} 00:00:00', updated_at='{$ctimestamps} 00:00:00' WHERE id={$piece->id}") );

				$acts[] = $piece->id;
			}

			if($done) {
				$audit 					= new Audit();
			    $audit->note_id 		= $note_id;
			    $audit->audited 		= true;
			    $audit->user_id 		= Auth::user()->id;
			    $audit->character_id 	= Input::get('chracter');

			    if(!$audit->save()) {
			    	DB::rollback();
			    	return Response::json(array('status' => false, 'message' => 'Ocurrio un problema al guardar revision'),200);
			    }

			    if(Input::get('ranged')==true) DB::update( DB::raw("UPDATE audits SET created_at='{$ctimestamps}', updated_at='{$ctimestamps}' WHERE id={$audit->id}") );
			    
			    $audit->pieces()->sync($acts);

			    DB::commit();

			    $au 	= Audit::find(1)->first();
			    $pieces = $au->pieces()->with('Actor','Topic','Type')->get();
				
				return Response::json(array('status' => true, 'message' => 'Auditorial realizada','pieces' => $pieces),200);

			} else {
				DB::rollback();
				return Response::json(array('status' => false, 'message' => 'Ocurrio un problema al guardar revision'),200);
			}

		} catch (Exception $e) {
			DB::rollback();
			return Response::json(array('status' => false, 'message' => 'Ocurrio un problema al actualizar la auditoria', 'exception' => $e->getMessage()),200);
		}
		
	}

	// Funcion para verificar si una nota existe en la base de datos local
	public function noteCheck()
	{
		try {
			
			$audit = Audit::with(array('Pieces' => function($query){
				$query->with('Actor','Topic','Type');
			}))->where('note_id',Input::get('id'))->first();

			if(!$audit) return Response::json(array('status' => false, 'message' => 'La nota no existe en la base de datos'),200);

			return Response::json(array('status' => true, 'message' => 'Nota localizada en la base de datos local', 'note' => $audit),200); 

		} catch (Exception $e) {
			return Response::json(array('status' => true, 'message' => 'OCurrio un error al tratar de localizar la nota en la base de datos local', 'exception' => $e->getMessage()),200);
		}
	}
}