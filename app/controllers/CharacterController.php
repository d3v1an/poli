<?php

class CharacterController extends \BaseController {

	public function character($id)
	{
		$params = array(
						'main_active' => 'characters',
						'active' => $id,
						'ranged' => false
					   );

		return View::make('cp.character')->with($params);
	}

	public function characterRanged($id,$date)
	{
		$params = array(
						'main_active' => 'characters',
						'active' => $id,
						'ranged' => true,
						'date' => $date
					   );

		return View::make('cp.character')->with($params);
	}

	// Funcion para remover una pieza de una auditoria
	public function removePiece()
	{
		$audit_id = Input::get('eaudit');
		$piece_id = Input::get('piece');

		try {
			
			$a = Audit::with('Pieces')->where('id',$audit_id)->first();

	        if(!$a) return Response::json(array('status' => false, 'message' => 'No se localizo la calificacion seleccionada a eliminar'));

	        if($a->pieces()->detach($piece_id)) return Response::json(array('status' => true, 'message' => 'Elemento removido de la lista de calificacion'));
	        else return Response::json(array('status' => false, 'message' => 'El elemento no puso ser eliminado, intentelo nuevamente'));

		} catch (Exception $e) {
			return Response::json(array('status' => false, 'message' => 'Ocurrio un problema al eliminar la calificacion seleccionada [' . $e->getMessage() . ']'));
		}

	}

	// Funcion para actualizar una pieza de una auditortia
	public function updatePiece()
	{

		$piece_id 	= Input::get('pid');
		$actor_id 	= Input::get('actor');
		$topic_id 	= Input::get('topic');
		$type_id 	= Input::get('type');
		$status 	= Input::get('status');

		try {
			
			$p = Piece::find($piece_id);

	        if(!$p) return Response::json(array('status' => false, 'message' => 'No se localizo la calificacion seleccionada a eliminar'));

	        $p->actor_id 	= $actor_id;
	        $p->topic_id 	= $topic_id;
	        $p->type_id 	= $type_id;
	        $p->status 		= $status;

	        if($p->save()) {
	        	$pp = Piece::with('actor','topic','type')->where('id',$piece_id)->first();
	        	return Response::json(array('status' => true, 'message' => 'Elemento actualizado', 'piece' => $pp));
	        }
	        else return Response::json(array('status' => false, 'message' => 'El elemento no puso ser actualizado, intentelo nuevamente'));

		} catch (Exception $e) {
			return Response::json(array('status' => false, 'message' => 'Ocurrio un problema al actualizar el elemento seleccionado [' . $e->getMessage() . ']'));
		}

	}

}