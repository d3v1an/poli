<?php

class MiscController extends \BaseController {

	// Opciones de catalogos temporales
	public function catalogs()
	{

		$params = array(
						'main_active' => 'catalogs'
					   );

		return View::make('cp.catalogs')->with($params);
	}

	// Funcion para agregar una fuente (Canal)
	public function addSource()
	{
		$_source = Input::get('data');

		try {

			$src = Source::where('name','LIKE',"%{$_source}%")->first();

			if($src) return Response::json(array('status' => false, 'message' => 'La fuenta ya existe en la base de datos'));

			$src = new Source();
			$src->name = $_source;

			if($src->save()) {
				$ssrc = Source::all();
				return Response::json(array('status' => true, 'message' => 'Fuente agregada con exito', 'sources' => $ssrc));
			}
			else return Response::json(array('status' => false, 'message' => 'La fuente no puso ser agregada'));

		} catch (Exception $e) {
			return Response::json(array('status' => false, 'message' => 'Ocurrio un problema al agregar la fuente [' . $e->getMessage() . ']'));			
		}
	}

	// Funcion para obtener las fuentes disponibles
	public function getSources()
	{
		try {
			$src = Source::all();
			return Response::json(array('status' => true, 'message' => 'Fuente localizadas', 'sources' => $src));
		} catch (Exception $e) {
			return Response::json(array('status' => false, 'message' => 'Ocurrio un problema al obtener las fuentes [' . $e->getMessage() . ']'));			
		}
	}	

	// Funcion para agregar un comunicador
	public function addProgram()
	{
		$_source 		= Input::get('fuente');
		$_program 		= Input::get('data');
		$_comunicator	= Input::get('comunicador');

		try {

			//$prog = Program::where('name','LIKE',"%{$_source}%")->first();

			//if($prog) return Response::json(array('status' => false, 'message' => 'El programa ya existe en la base de datos'));

			$prog 					= new Program();
			$prog->name 			= $_program;
			$prog->source_id 		= $_source;
			$prog->comunicator_id 	= $_comunicator; 

			if($prog->save()) {
				$aprog = Program::with('Source','Comunicator')->get();
				return Response::json(array('status' => true, 'message' => 'Programa agregado con exito', 'sources' => $aprog));
			}
			else return Response::json(array('status' => false, 'message' => 'El programa no puso ser agregado'));

		} catch (Exception $e) {
			return Response::json(array('status' => false, 'message' => 'Ocurrio un problema al agregar el programa [' . $e->getMessage() . ']'));			
		}
	}

	// Funcion para obtener los programas disponibles
	public function getPrograms()
	{
		try {
			$src = Program::with('Source','Comunicator')->get();
			return Response::json(array('status' => true, 'message' => 'Programas localizados', 'sources' => $src));
		} catch (Exception $e) {
			return Response::json(array('status' => false, 'message' => 'Ocurrio un problema al obtener los programas [' . $e->getMessage() . ']'));			
		}
	}	

	// Funcion para agregar un comunicador
	public function addComunicator()
	{
		$_source = Input::get('data');

		try {

			$src = Comunicator::where('name','LIKE',"%{$_source}%")->first();

			if($src) return Response::json(array('status' => false, 'message' => 'El comunicador ya existe en la base de datos'));

			$src = new Comunicator();
			$src->name = $_source;

			if($src->save()) {
				$src = Comunicator::all();
				return Response::json(array('status' => true, 'message' => 'Comunicador agregado con exito', 'sources' => $src));
			}
			else return Response::json(array('status' => false, 'message' => 'El comunicador no puso ser agregado'));

		} catch (Exception $e) {
			return Response::json(array('status' => false, 'message' => 'Ocurrio un problema al agregar el comunicador [' . $e->getMessage() . ']'));			
		}
	}

	// Funcion para obtener los comunicadores
	public function getComunicators()
	{
		try {
			$src = Comunicator::all();
			return Response::json(array('status' => true, 'message' => 'Comunicadores localizadss', 'sources' => $src));
		} catch (Exception $e) {
			return Response::json(array('status' => false, 'message' => 'Ocurrio un problema al obtener las fuentes [' . $e->getMessage() . ']'));			
		}
	}	
}
?>