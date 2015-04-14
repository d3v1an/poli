<?php

class ElectronicController extends \BaseController {

	// Funcion de render de vista de captura
	public function capture()
	{
		$programs 		= Program::with('Source','Comunicator')->get();
		$comunicator 	= Comunicator::all();
		$enews 			= ElectronicNews::with(array('Program',
						'Comunicator' => function($query){
							$query->orderBy('name','ASC');
						},
						'Audit' => function($query) {
			                $query->with(array('Pieces' => function($qry){
			                    $qry->with('actor','topic','type');
			                }));
			            }))
						->where( DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')") , "=", Carbon::today()->toDateString() )
			            ->get();

		// $enews 			= ElectronicNews::with(array('Program','Comunicator'))
		// 				->where( DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')") , "=", Carbon::today()->toDateString() )
		// 				->get();

		$params 	= array(
						'main_active' => 'electronic',
						'programs' => $programs,
						'comunicator' => $comunicator,
						'enews' => $enews
					   );

		return View::make('cp.electronic')->with($params);
	}

	// Funcion para agregar una nota de medios electronicos
	public function addNote()
	{

		$_file  	= Input::file('note-file');
		$date 		= Input::get('note-fecha');
		$hour 		= Input::get('note-hora');
		$title 		= Input::get('note-title');
		$header 	= Input::get('note-header');
		$note 		= Input::get('noteText');
		$prog 		= Input::get('note-program');
		$source 	= Input::get('_note_source');
		$mtype 		= Input::get('note-media-type');
		$autor 		= Input::get('note-autor');
		$character 	= Input::get('to_actor');
		$meta 		= explode('|',Input::get('meta'));
		$acts 		= array();
		$done 		= true;

		$destinationPath 	= public_path() .'/uploads/';

		try {

			DB::beginTransaction();

			for ($i=0; $i < count($meta); $i++) { 
				
				$spl_pz 			= explode(':',$meta[$i]);

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

				$acts[] = $piece->id;
			}

			if($done) {

				// Nombre de archivo sin extension
				$file_name_woe 		= pathinfo($_file->getClientOriginalName(), PATHINFO_FILENAME);
				$file_extension 	= $_file->getClientOriginalExtension();
				$file_mime 			= $_file->getMimeType();
				$file_name_remark 	= preg_replace('/-/','_',$date) . '_' . fix_string(preg_replace('/ /','_',$source)) . '_' . fix_string(preg_replace('/ /','_',$title));

				// Carga del archivo al servidor
				$uploadSuccess = $_file->move($destinationPath, $_file->getClientOriginalName());

				// SI la carga fue exitoza realizamos la conversion
				if($uploadSuccess) {

					// Objeto shell
					$command		= L4shell::get();

					$_file_name 	= $_file->getClientOriginalName();

					if (strpos($file_mime,'video') !== false) {
					    
					    // Es video
						if($file_extension!='mp4') {

							// Video de entrada
							$finput 	= $destinationPath . $_file->getClientOriginalName();
							// Video de salida
							$foutput 	= $destinationPath . $file_name_woe . '.mp4';

							$result 	= $command
										  ->setCommand("avconv -i \"{$finput}\" \"{$foutput}\"")
										  ->execute();

							// Eliminamos el archivo origin al
							File::delete($finput);

							$__old = $destinationPath . $file_name_woe . '.mp4';
							$__new = $destinationPath . $file_name_remark . '.mp4';

							// Renombramos el archivo
							File::move($__old, $__new);

							// Definimos el nuevo nombre del archivo
							$_file_name = $file_name_remark . '.mp4';

						} else {

							$__old = $destinationPath . $_file->getClientOriginalName();
							$__new = $destinationPath . $file_name_remark . '.mp4';

							// Renombramos el archivo
							File::move($__old, $__new);

							// Definimos el nuevo nombre del archivo
							$_file_name = $file_name_remark . '.mp4';

						}

					} else {
						// Es audio
						if($file_extension!='mp3') {

							// Audio de entrada
							$finput 	= $destinationPath . $_file->getClientOriginalName();
							// Audio de salida
							$foutput 	= $destinationPath . $file_name_woe . '.mp3';

							$result 	= $command
										  ->setCommand("avconv -i \"{$finput}\" \"{$foutput}\"")
										  ->execute();

							// Eliminamos el archivo origin al
							File::delete($finput);

							$__old = $destinationPath . $file_name_woe . '.mp3';
							$__new = $destinationPath . $file_name_remark . '.mp3';

							// Renombramos el archivo
							File::move($__old, $__new);

							// Definimos el nuevo nombre del archivo
							$_file_name = $file_name_remark . '.mp3';
						
						}  else {

							$__old = $destinationPath . $_file->getClientOriginalName();
							$__new = $destinationPath . $file_name_remark . '.mp3';

							// Renombramos el archivo
							File::move($__old, $__new);

							// Definimos el nuevo nombre del archivo
							$_file_name = $file_name_remark . '.mp3';

						}
					}

					$en 				= new ElectronicNews();
					$en->program_id 	= $prog;
					$en->comunicator_id = $autor;
					$en->actor_id 		= $character;
					$en->date 			= $date;
					$en->hour 			= $hour;
					$en->title 			= $title;
					$en->header 		= $header;
					$en->note 			= $note;
					$en->type 			= $mtype;
					$en->file 			= $_file_name;

					if($en->save()) {

						$audit 					= new Audit();
					    $audit->note_id 		= $en->id;
					    $audit->audited 		= true;
					    $audit->user_id 		= Auth::user()->id;
					    $audit->character_id 	= 0;
					    $audit->type 			= 'e';

					    if(!$audit->save()) {
					    	DB::rollback();
					    	return Redirect::to('cp/electronic')->with('error_message', 'Ocurrio un problema al guardar la nota');
					    }

					    $audit->pieces()->sync($acts);

			    		DB::commit();

			    		return Redirect::to('cp/electronic')->with('success_message', 'Nota guardada exitosamente');
					} else {
						DB::rollback();
						return Redirect::to('cp/electronic')->with('error_message', 'Ocurrio un problema al guardar la nota');
					}

				} else {
					DB::rollback();
					return Redirect::to('cp/electronic')->with('error_message', 'Ocurrio un problema al cargar el archivo al servidor');
				}

			} else {
				DB::rollback();
				return Redirect::to('cp/electronic')->with('error_message', 'Ocurrio un problema al guardar la nota');
			}

			
		} catch (Exception $e) {
			return Redirect::to('cp/electronic')->with('error_message', 'Ocurrio un problema al guardar la nota [' . $e->getMessage() . ']');
		}
	}

	// Funcion para descarga de medios
	public function mediaDownload($file)
	{
		$path_file = public_path() . '/uploads/' . $file;
		if(File::exists($path_file)) return Response::download($path_file);
		else return Redirect::to('cp/electronic')->with('error_message', 'Ocurrio un problema al descargar el archivo');
	}

	// Funcion para remover una pieza de una auditoria
	public function removePiece()
	{
		$audit_id = Input::get('eaudit');
		$piece_id = Input::get('piece');

		try {
			
			$en = ElectronicNews::with(array('Audit' => function($query) {
                    $query->with('Pieces');
                }))
                ->where('id',$audit_id)
                ->first();

	        if(!$en) return Response::json(array('status' => false, 'message' => 'No se localizo la calificacion seleccionada a eliminar'));

	        if($en->audit->pieces()->detach($piece_id)) return Response::json(array('status' => true, 'message' => 'Elemento removido de la lista de calificacion'));
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