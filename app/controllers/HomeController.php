<?php

class HomeController extends BaseController {

	// Dashboard
	public function dashboard()
	{
		// Informacion del actor
		$actor 				= Actor::find(1);

		// Informacion de todos los actores
		$actors 			= Actor::where('status',1)->get();

		// Calificaciones de impresos
		$statics_printed 	=

		// Parametros a pasar a la vista
		$params 			= array(
								'actor' 	=> $actor,
								'actors' 	=> $actors
							  );

		// Renderizado de la vista
		return View::make('cp.dashboard')->with($params);
	}

}
