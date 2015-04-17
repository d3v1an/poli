<?php

class ControlPanelController extends BaseController {

	// Pagina principal
	public function dashboard()
	{
		return View::make('cp.dashboard');
	}


	// Reporte por defecto de impresos
	public function reportPrinted()
	{
		$actor = Actor::where('rf_id',1398)->first();

		$audit = Audit::with(array('actor','user','pieces' => function($query) {
				$query->with('topic','type')
					  ->orderBy('actor_id', 'ASC');
			}))
            ->where('character_id',1398)
            ->where('type','i')
            ->where( DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')") , "=", Carbon::today()->toDateString() )
            ->get();

        $a_count = count($audit);

        if($a_count > 0) {
        	for ($i=0; $i < $a_count; $i++) {

        		$rest 					= cURL::get('http://' . Config::get('rest.ip') . '/siscap.la/public/api/v1/notice/' . $audit[$i]->note_id);
				$notice 				= json_decode($rest);

				$audit[$i]['notice']	= $notice->notice[0];

				$p_count = $audit[$i]->pieces->count();
				$tmp_act = array();

				foreach ($audit[$i]->pieces as $p) {
                    if(!in_array( $p->actor->name, $tmp_act, true)){
                        array_push($tmp_act,  $p->actor->name);
                    }
                }

				$audit[$i]['actors'] = $tmp_act;
        	}
        }

		$actors = Actor::with(array(
                            'audit' => function($query) {
                                $query->where( DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')") , "=", Carbon::today()->toDateString() ); 
                            }))
                            ->where('status',1)
                            ->get();
		$params = array(
					'actor' 	=> $actor,
					'actors' 	=> $actors,
					'audits' 	=> $audit,
					'aid' 		=> 1398
				);

		return View::make('cp.report')->with($params);
	}

	// Obtenemos la informacion segun rango de fechas de impresos
	public function reportPrintedRange($aid,$data_in,$data_end)
	{

		$actor = Actor::where('rf_id',$aid)->first();

		$audit = Audit::with(array('actor','user','pieces' => function($query) {
				$query->with('topic','type')
					  ->orderBy('actor_id', 'ASC');
			}))
            ->where('character_id',$aid)
            ->where('type','i')
            ->whereBetween( DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')") , array($data_in,$data_end) )
            ->get();

        $a_count = count($audit);

        if($a_count > 0) {
        	for ($i=0; $i < $a_count; $i++) {

        		$rest 					= cURL::get('http://' . Config::get('rest.ip') . '/siscap.la/public/api/v1/notice_range/' . $audit[$i]->note_id);
				$notice 				= json_decode($rest);

				$audit[$i]['notice']	= $notice->notice[0];

				$p_count = $audit[$i]->pieces->count();
				$tmp_act = array();

				foreach ($audit[$i]->pieces as $p) {
                    if(!in_array( $p->actor->name, $tmp_act, true)){
                        array_push($tmp_act,  $p->actor->name);
                    }
                }

				$audit[$i]['actors'] = $tmp_act;
        	}
        }

		$actors = Actor::with(array(
                            'audit' => function($query) use($data_in,$data_end) {
                                $query->whereBetween( DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')") , array($data_in,$data_end) );
                            }))
                            ->where('status',1)
                            ->get();
		$params = array(
					'actor' 	=> $actor,
					'actors' 	=> $actors,
					'audits' 	=> $audit,
					'aid' 		=> $aid
				);

		return View::make('cp.report')->with($params);

	}

	// Obtenemos la informacion por actor de impresos
	public function reportPrintedArgs($actor)
	{
		$_actor = Actor::where('rf_id',$actor)->first();

		$audit = Audit::with(array('actor','user','pieces' => function($query) {
				$query->with('topic')
					  ->orderBy('actor_id', 'ASC');
			}))
            ->where('character_id',$actor)
            ->where('type','i')
            ->where( DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')") , "=", Carbon::today()->toDateString() )
            ->get();
        
        $a_count = count($audit);

        if($a_count > 0) {
        	for ($i=0; $i < $a_count; $i++) {

        		$rest 					= cURL::get('http://' . Config::get('rest.ip') . '/siscap.la/public/api/v1/notice/' . $audit[$i]->note_id);
				$notice 				= json_decode($rest);
				$audit[$i]['notice']	= $notice->notice[0];

				$p_count = $audit[$i]->pieces->count();
				$tmp_act = array();

				foreach ($audit[$i]->pieces as $p) {
                    if(!in_array( $p->actor->name, $tmp_act, true)){
                        array_push($tmp_act,  $p->actor->name);
                    }
                }

				$audit[$i]['actors'] = $tmp_act;
        	}
        }

		$actors = Actor::with(array(
                            'audit' => function($query) {
                                $query->where( DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')") , "=", Carbon::today()->toDateString() ); 
                            }))
                            ->where('status',1)
                            ->get();
		$params = array(
					'actor' 	=> $_actor,
					'actors'	=> $actors,
					'audits' 	=> $audit,
					'aid' 		=> $actor
				);

		return View::make('cp.report')->with($params);
	}

	// Metodo para exportar a excell
	public function excel($actor,$ids)
	{
		$_actor = Actor::where('rf_id',$actor)->first();

		$_ids 	= explode(',', $ids);

		$audits = Audit::with(array('actor','pieces' => function($query) {
                $query->with('actor','topic')
                      ->orderBy('actor_id', 'ASC')
                      ->orderBy('topic_id', 'ASC');
            }))
            ->where('character_id',$actor)
            //->where( DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')") , "=", Carbon::today()->toDateString() )
            ->whereIn('id',$_ids)
            ->get();

	    $a_count = count($audits);

	    if($a_count > 0) {
	        for ($i=0; $i < $a_count; $i++) {

	            $rest                   = cURL::get('http://' . Config::get('rest.ip') . '/siscap.la/public/api/v1/notice_range/' . $audits[$i]->note_id);
	            $notice                 = json_decode($rest);
	            $audits[$i]['notice']   = $notice->notice[0];
	        }
	    }

	    $data = array();

	    foreach ($audits as $audit) {
        
	        // Configuracion de actores (3 actores con 3 temas)
	        $a1     = array();
	        $a2     = array();
	        $a3     = array();

	        // Is's de acotres auditados
	        $aa     = array();

	        // Actor 1
	        $cai    = 0;

	        // Bucle actor 1 y sus temas //if(!in_array($cai, $aa)) array_push($aa, $cai);
	        $i=0;
	        foreach ($audit->pieces as $p) {
	            
	            if($cai==0) {
	                $cai = $p->actor_id;
	                $a1["actor"]  = $p->actor->name;
	                $a1["tema_1"] = array(
	                                        'actor'     => $p->actor->name,
	                                        'tema_1'    => $p->topic->text,
	                                        'estatus'   => $p->status 
	                                    );
	                array_push($aa, $p->id);
	                $i++;
	            } else {
	                if($cai==$p->actor_id) {
	                    if(!in_array($p->id, $aa)) {
	                        $a1["tema_" . ($i+1)] = array(
	                                        'actor'                     => $p->actor->name,
	                                        'tema_' . ($i+1)    => $p->topic->text,
	                                        'estatus'                   => $p->status 
	                                    );
	                        array_push($aa, $p->id);
	                        $i++;
	                    }
	                }
	            }
	        }

	        // Actor 2
	        $cai    = 0;

	        // Bucle actor 1 y sus temas //if(!in_array($cai, $aa)) array_push($aa, $cai);
	        $i=0;
	        foreach ($audit->pieces as $p) {

	            if(!in_array($p->id, $aa)) {

	                if($cai==0) {
	                    $cai = $p->actor_id;
	                    $a2["actor"]  = $p->actor->name;
	                    $a2["tema_1"] = array(
	                                            'actor'     => $p->actor->name,
	                                            'tema_1'    => $p->topic->text,
	                                            'estatus'   => $p->status 
	                                        );
	                    array_push($aa, $p->id);
	                    $i++;
	                } else {
	                    if($cai==$p->actor_id) {
	                        if(!in_array($p->id, $aa)) {
	                            $a2["tema_" . ($i+1)] = array(
	                                            'actor'                     => $p->actor->name,
	                                            'tema_' . ($i+1)    => $p->topic->text,
	                                            'estatus'                   => $p->status 
	                                        );
	                            array_push($aa, $p->id);
	                            $i++;
	                        }
	                    }
	                }

	            }
	        }

	        // Actor 3
	        $cai    = 0;

	        // Bucle actor 1 y sus temas //if(!in_array($cai, $aa)) array_push($aa, $cai);
	        $i=0;
	        foreach ($audit->pieces as $p) {

	            if(!in_array($p->id, $aa)) {
	            
	                if($cai==0) {
	                    $cai = $p->actor_id;
	                    $a3["actor"]  = $p->actor->name;
	                    $a3["tema_1"] = array(
	                                            'actor'     => $p->actor->name,
	                                            'tema_1'    => $p->topic->text,
	                                            'estatus'   => $p->status 
	                                        );
	                    array_push($aa, $p->id);
	                    $i++;
	                } else {
	                    if($cai==$p->actor_id) {
	                        if(!in_array($p->id, $aa)) {
	                            $a3["tema_" . ($i+1)] = array(
	                                            'actor'                     => $p->actor->name,
	                                            'tema_' . ($i+1)    => $p->topic->text,
	                                            'estatus'                   => $p->status 
	                                        );
	                            array_push($aa, $p->id);
	                            $i++;
	                        }
	                    }
	                }
	            }
	        }

	        // Resultado final de columna
	        $r = array(
	                'fecha'     => $audit->notice->Fecha,
	                'diario'    => $audit->notice->Periodico,
	                'pagina'    => $audit->notice->PaginaPeriodico,
	                'seccion'   => $audit->notice->seccion,
	                'titulo'    => $audit->notice->Titulo,
	                'actor_1'   => $a1,
	                'actor_2'   => $a2,
	                'actor_3'   => $a3
	            );

	        $data[] = $r;
	    }

	    $file_name = $_actor->name . '-' . date('YmdHis');

	    Excel::create($file_name, function($excel) use($data) {

	        $excel->sheet('Prensa', function($sheet) use($data) {

	            //$sheet->fromArray($data);
	            // Creamos las celdas superiores
	            $sheet->row(1, array(
	                'Fecha',
	                'Diario',
	                'Pag.',
	                'Sección',
	                'Titulo',
	                'Autor 1',
	                'Tema 1',
	                '+',
	                '-',
	                '=',
	                'Tema 2',
	                '+',
	                '-',
	                '=',
	                'Tema 3',
	                '+',
	                '-',
	                '=',
	                'Autor 2',
	                'Tema 1',
	                '+',
	                '-',
	                '=',
	                'Tema 2',
	                '+',
	                '-',
	                '=',
	                'Tema 3',
	                '+',
	                '-',
	                '=',
	                'Autor 3',
	                'Tema 1',
	                '+',
	                '-',
	                '=',
	                'Tema 2',
	                '+',
	                '-',
	                '=',
	                'Tema 3',
	                '+',
	                '-',
	                '='
	            ));

				//  Fix format
				$sheet->cells('A2:A100', function($cells) {
	                $cells->setAlignment('left');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('B2:B100', function($cells) {
	                $cells->setAlignment('left');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('D2:D100', function($cells) {
	                $cells->setAlignment('left');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('E2:E100', function($cells) {
	                $cells->setAlignment('left');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('G2:G100', function($cells) {
	                $cells->setAlignment('left');
	                $cells->setValignment('middle');
	            });

	            /**
	             * Configuracion de fuentes y aliniamiento general de todas las celdas superiores
	             */
	            $sheet->cells('A1:AR1', function($cells) {
	                $cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });

	            $sheet->cells('C2:C100', function($cells) {
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('F2:F100', function($cells) {
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('H2:H100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('12:1100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('J2:J100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('L2:L100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('M2:M100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('N2:N100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('P2:P100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('Q2:Q100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('R2:R100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('S2:S100', function($cells) {
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('U2:U100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('V2:V100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('W2:W100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('Y2:Y100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('Z2:Z100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AA2:AA100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AC2:AC100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AD2:AD100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AE2:AE100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AF2:AF100', function($cells) {
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AH2:AH100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AI2:AI100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AJ2:AJ100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AL2:AL100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AM2:AM100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AN2:AN100', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });

	            /**
	             * Altura del la primer celda (Las demas heredan su tamaño)
	             */
	            for($i = 2; $i < 100; $i++) {
	            	$sheet->setHeight($i, 17);
	            }

	            /**
	             * Colores de algunas celdas
	             */
	            // Verde
	            $sheet->cells('H1:H100', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('L1:L100', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('P1:P100', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('U1:U100', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('Y1:Y100', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('AC1:AC100', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('AH1:AH100', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('AL1:AL100', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('AP1:AP100', function($cells) { $cells->setFontColor('#03d100'); });

	            // Rojo
	            $sheet->cells('I1:I100', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('M1:M100', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('Q1:Q100', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('V1:V100', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('Z1:Z100', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('AD1:AD100', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('AI1:AI100', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('AM1:AM100', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('AQ1:AQ100', function($cells) { $cells->setFontColor('#db0404'); });

	            $i=2;
	            foreach ($data as $d) {
	            	$sheet->row($i, array(

		                $d["fecha"],
		                $d["diario"],
		                $d["pagina"],
		                $d["seccion"],
		                $d["titulo"],

		                (isset($d["actor_1"]["actor"]) ? $d["actor_1"]["actor"]:''),
		                (isset($d["actor_1"]["tema_1"]) ? $d["actor_1"]["tema_1"]["tema_1"]:''),
		                (isset($d["actor_1"]["tema_1"]) && $d["actor_1"]["tema_1"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_1"]["tema_1"]) && $d["actor_1"]["tema_1"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_1"]["tema_1"]) && $d["actor_1"]["tema_1"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_1"]["tema_2"]) ? $d["actor_1"]["tema_2"]["tema_2"]:''),
		                (isset($d["actor_1"]["tema_2"]) && $d["actor_1"]["tema_2"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_1"]["tema_2"]) && $d["actor_1"]["tema_2"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_1"]["tema_2"]) && $d["actor_1"]["tema_2"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_1"]["tema_3"]) ? $d["actor_1"]["tema_3"]["tema_3"]:''),
		                (isset($d["actor_1"]["tema_3"]) && $d["actor_1"]["tema_3"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_1"]["tema_3"]) && $d["actor_1"]["tema_3"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_1"]["tema_3"]) && $d["actor_1"]["tema_3"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_2"]["actor"]) ? $d["actor_2"]["actor"]:''),
		                (isset($d["actor_2"]["tema_1"]) ? $d["actor_2"]["tema_1"]["tema_1"]:''),
		                (isset($d["actor_2"]["tema_1"]) && $d["actor_2"]["tema_1"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_2"]["tema_1"]) && $d["actor_2"]["tema_1"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_2"]["tema_1"]) && $d["actor_2"]["tema_1"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_2"]["tema_2"]) ? $d["actor_2"]["tema_2"]["tema_2"]:''),
		                (isset($d["actor_2"]["tema_2"]) && $d["actor_2"]["tema_2"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_2"]["tema_2"]) && $d["actor_2"]["tema_2"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_2"]["tema_2"]) && $d["actor_2"]["tema_2"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_2"]["tema_3"]) ? $d["actor_2"]["tema_3"]["tema_3"]:''),
		                (isset($d["actor_2"]["tema_3"]) && $d["actor_2"]["tema_3"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_2"]["tema_3"]) && $d["actor_2"]["tema_3"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_2"]["tema_3"]) && $d["actor_2"]["tema_3"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_3"]["actor"]) ? $d["actor_3"]["actor"]:''),
		                (isset($d["actor_3"]["tema_1"]) ? $d["actor_3"]["tema_1"]["tema_1"]:''),
		                (isset($d["actor_3"]["tema_1"]) && $d["actor_3"]["tema_1"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_3"]["tema_1"]) && $d["actor_3"]["tema_1"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_3"]["tema_1"]) && $d["actor_3"]["tema_1"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_3"]["tema_2"]) ? $d["actor_3"]["tema_2"]["tema_2"]:''),
		                (isset($d["actor_3"]["tema_2"]) && $d["actor_3"]["tema_2"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_3"]["tema_2"]) && $d["actor_3"]["tema_2"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_3"]["tema_2"]) && $d["actor_3"]["tema_2"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_3"]["tema_3"]) ? $d["actor_3"]["tema_3"]["tema_3"]:''),
		                (isset($d["actor_3"]["tema_3"]) && $d["actor_3"]["tema_3"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_3"]["tema_3"]) && $d["actor_3"]["tema_3"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_3"]["tema_3"]) && $d["actor_3"]["tema_3"]["estatus"]=='nn'?'1':'')

		            ));
					$i++;
	            }

	            //$sheet->cells('H2:H10', function($cells) { $cells->setFontColor('#03d100'); });

	        });

	    })->export('xls');

		return "done";
	}
}
