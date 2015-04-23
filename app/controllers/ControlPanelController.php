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
	public function excelIdsTa($actor,$ids)
	{
		$_actor = Actor::where('rf_id',$actor)->first();

		$_ids 	= explode(',', $ids);

		$audits = Audit::with(array('actor','pieces' => function($query) {
                $query->with('actor','topic','type')
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
	                                        'tipo_1' 	=> $p->type->name,
	                                        'estatus'   => $p->status 
	                                    );
	                array_push($aa, $p->id);
	                $i++;
	            } else {
	                if($cai==$p->actor_id) {
	                    if(!in_array($p->id, $aa)) {
	                        $a1["tema_" . ($i+1)] = array(
	                                        'actor'				=> $p->actor->name,
	                                        'tema_' . ($i+1)    => $p->topic->text,
	                                        'tipo_' . ($i+1)	=> $p->type->name,
	                                        'estatus'           => $p->status 
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
	                                            'tipo_1' 	=> $p->type->name,
	                                            'estatus'   => $p->status 
	                                        );
	                    array_push($aa, $p->id);
	                    $i++;
	                } else {
	                    if($cai==$p->actor_id) {
	                        if(!in_array($p->id, $aa)) {
	                            $a2["tema_" . ($i+1)] = array(
	                                            'actor'             => $p->actor->name,
	                                            'tema_' . ($i+1)    => $p->topic->text,
	                                            'tipo_' . ($i+1)	=> $p->type->name,
	                                            'estatus' 			=> $p->status 
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
	                                            'tipo_1' 	=> $p->type->name,
	                                            'estatus'   => $p->status 
	                                        );
	                    array_push($aa, $p->id);
	                    $i++;
	                } else {
	                    if($cai==$p->actor_id) {
	                        if(!in_array($p->id, $aa)) {
	                            $a3["tema_" . ($i+1)] = array(
	                                            'actor'             => $p->actor->name,
	                                            'tema_' . ($i+1)    => $p->topic->text,
	                                            'tipo_' . ($i+1)	=> $p->type->name,
	                                            'estatus'           => $p->status 
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
	                'Tipo 1',
	                '+',
	                '-',
	                '=',
	                'Tema 2',
	                'Tipo 2',
	                '+',
	                '-',
	                '=',
	                'Tema 3',
	                'Tipo 3',
	                '+',
	                '-',
	                '=',
	                'Autor 2',
	                'Tema 1',
	                'Tipo 1',
	                '+',
	                '-',
	                '=',
	                'Tema 2',
	                'Tipo 2',
	                '+',
	                '-',
	                '=',
	                'Tema 3',
	                'Tipo 3',
	                '+',
	                '-',
	                '=',
	                'Autor 3',
	                'Tema 1',
	                'Tipo 1',
	                '+',
	                '-',
	                '=',
	                'Tema 2',
	                'Tipo 2',
	                '+',
	                '-',
	                '=',
	                'Tema 3',
	                'Tipo 3',
	                '+',
	                '-',
	                '='
	            ));

				//  Fix format
				$sheet->cells('A2:A200', function($cells) {
	                $cells->setAlignment('left');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('B2:B200', function($cells) {
	                $cells->setAlignment('left');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('C2:C200', function($cells) {
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('D2:D200', function($cells) {
	                $cells->setAlignment('left');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('E2:E200', function($cells) {
	                $cells->setAlignment('left');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('F2:F200', function($cells) {
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('G2:G200', function($cells) {
	                $cells->setAlignment('left');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('H2:H200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('12:1200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('J2:J200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('L2:L200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('M2:M200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('N2:N200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('P2:P200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('Q2:Q200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('R2:R200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('S2:S200', function($cells) {
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('U2:U200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('V2:V200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('W2:W200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('Y2:Y200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('Z2:Z200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AA2:AA200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AC2:AC200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AD2:AD200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AE2:AE200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AF2:AF200', function($cells) {
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AH2:AH200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AI2:AI200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AJ2:AJ200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AL2:AL200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AM2:AM200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AN2:AN200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AO2:AO200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AP2:AP200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AQ2:AQ200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AR2:AR200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AS2:AS200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AT2:AT200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AT2:AT200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AT2:AT200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AU2:AU200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AV2:AV200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AW2:AW200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AX2:AX200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AY2:AY200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AZ2:AZ200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('BA2:BA200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });

	            /**
	             * Altura del la primer celda (Las demas heredan su tamaño)
	             */
	            for($i = 2; $i < 200; $i++) {
	            	$sheet->setHeight($i, 17);
	            }

	            /**
	             * Colores de algunas celdas
	             */
	            // Verde
	            $sheet->cells('I1:I200', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('N1:N200', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('S1:S200', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('S1:S200', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('AD1:AD200', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('AI1:AI200', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('AO1:AO200', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('AT1:AT200', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('AY1:AY200', function($cells) { $cells->setFontColor('#03d100'); });

	            // Rojo
	            $sheet->cells('J1:J200', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('O1:O200', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('T1:T200', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('Z1:Z200', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('AE1:AE200', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('AJ1:AJ200', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('AP1:AP200', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('AU1:AU200', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('AZ1:AZ200', function($cells) { $cells->setFontColor('#db0404'); });

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
		                (isset($d["actor_1"]["tema_1"]) ? $d["actor_1"]["tema_1"]["tipo_1"]:''),
		                (isset($d["actor_1"]["tema_1"]) && $d["actor_1"]["tema_1"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_1"]["tema_1"]) && $d["actor_1"]["tema_1"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_1"]["tema_1"]) && $d["actor_1"]["tema_1"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_1"]["tema_2"]) ? $d["actor_1"]["tema_2"]["tema_2"]:''),
		                (isset($d["actor_1"]["tema_2"]) ? $d["actor_1"]["tema_2"]["tipo_2"]:''),
		                (isset($d["actor_1"]["tema_2"]) && $d["actor_1"]["tema_2"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_1"]["tema_2"]) && $d["actor_1"]["tema_2"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_1"]["tema_2"]) && $d["actor_1"]["tema_2"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_1"]["tema_3"]) ? $d["actor_1"]["tema_3"]["tema_3"]:''),
		                (isset($d["actor_1"]["tema_3"]) ? $d["actor_1"]["tema_3"]["tipo_3"]:''),
		                (isset($d["actor_1"]["tema_3"]) && $d["actor_1"]["tema_3"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_1"]["tema_3"]) && $d["actor_1"]["tema_3"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_1"]["tema_3"]) && $d["actor_1"]["tema_3"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_2"]["actor"]) ? $d["actor_2"]["actor"]:''),
		                (isset($d["actor_2"]["tema_1"]) ? $d["actor_2"]["tema_1"]["tema_1"]:''),
		                (isset($d["actor_2"]["tema_1"]) ? $d["actor_2"]["tema_1"]["tipo_1"]:''),
		                (isset($d["actor_2"]["tema_1"]) && $d["actor_2"]["tema_1"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_2"]["tema_1"]) && $d["actor_2"]["tema_1"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_2"]["tema_1"]) && $d["actor_2"]["tema_1"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_2"]["tema_2"]) ? $d["actor_2"]["tema_2"]["tema_2"]:''),
		                (isset($d["actor_2"]["tema_2"]) ? $d["actor_2"]["tema_2"]["tipo_2"]:''),
		                (isset($d["actor_2"]["tema_2"]) && $d["actor_2"]["tema_2"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_2"]["tema_2"]) && $d["actor_2"]["tema_2"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_2"]["tema_2"]) && $d["actor_2"]["tema_2"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_2"]["tema_3"]) ? $d["actor_2"]["tema_3"]["tema_3"]:''),
		                (isset($d["actor_2"]["tema_3"]) ? $d["actor_2"]["tema_3"]["tipo_3"]:''),
		                (isset($d["actor_2"]["tema_3"]) && $d["actor_2"]["tema_3"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_2"]["tema_3"]) && $d["actor_2"]["tema_3"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_2"]["tema_3"]) && $d["actor_2"]["tema_3"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_3"]["actor"]) ? $d["actor_3"]["actor"]:''),
		                (isset($d["actor_3"]["tema_1"]) ? $d["actor_3"]["tema_1"]["tema_1"]:''),
		                (isset($d["actor_3"]["tema_1"]) ? $d["actor_3"]["tema_1"]["tipo_1"]:''),
		                (isset($d["actor_3"]["tema_1"]) && $d["actor_3"]["tema_1"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_3"]["tema_1"]) && $d["actor_3"]["tema_1"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_3"]["tema_1"]) && $d["actor_3"]["tema_1"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_3"]["tema_2"]) ? $d["actor_3"]["tema_2"]["tema_2"]:''),
		                (isset($d["actor_3"]["tema_2"]) ? $d["actor_3"]["tema_2"]["tipo_2"]:''),
		                (isset($d["actor_3"]["tema_2"]) && $d["actor_3"]["tema_2"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_3"]["tema_2"]) && $d["actor_3"]["tema_2"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_3"]["tema_2"]) && $d["actor_3"]["tema_2"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_3"]["tema_3"]) ? $d["actor_3"]["tema_3"]["tema_3"]:''),
		                (isset($d["actor_3"]["tema_3"]) ? $d["actor_3"]["tema_3"]["tipo_3"]:''),
		                (isset($d["actor_3"]["tema_3"]) && $d["actor_3"]["tema_3"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_3"]["tema_3"]) && $d["actor_3"]["tema_3"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_3"]["tema_3"]) && $d["actor_3"]["tema_3"]["estatus"]=='nn'?'1':'')

		            ));
					$i++;
	            }
				
	        });

	    })->export('xls');

		return "done";
	}

	// Metodo para exportar a excell
	public function excelFullTa($actor)
	{
		$_actor = Actor::where('rf_id',$actor)->first();

		$audits = Audit::with(array('actor','pieces' => function($query) {
                $query->with('actor','topic','type')
                      ->orderBy('actor_id', 'ASC')
                      ->orderBy('topic_id', 'ASC');
            }))
            ->where('character_id',$actor)
            //->where( DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')") , "=", Carbon::today()->toDateString() )
            //->whereIn('id',$_ids)
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
	                                        'tipo_1' 	=> $p->type->name,
	                                        'estatus'   => $p->status 
	                                    );
	                array_push($aa, $p->id);
	                $i++;
	            } else {
	                if($cai==$p->actor_id) {
	                    if(!in_array($p->id, $aa)) {
	                        $a1["tema_" . ($i+1)] = array(
	                                        'actor'				=> $p->actor->name,
	                                        'tema_' . ($i+1)    => $p->topic->text,
	                                        'tipo_' . ($i+1)	=> $p->type->name,
	                                        'estatus'           => $p->status 
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
	                                            'tipo_1' 	=> $p->type->name,
	                                            'estatus'   => $p->status 
	                                        );
	                    array_push($aa, $p->id);
	                    $i++;
	                } else {
	                    if($cai==$p->actor_id) {
	                        if(!in_array($p->id, $aa)) {
	                            $a2["tema_" . ($i+1)] = array(
	                                            'actor'             => $p->actor->name,
	                                            'tema_' . ($i+1)    => $p->topic->text,
	                                            'tipo_' . ($i+1)	=> $p->type->name,
	                                            'estatus' 			=> $p->status 
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
	                                            'tipo_1' 	=> $p->type->name,
	                                            'estatus'   => $p->status 
	                                        );
	                    array_push($aa, $p->id);
	                    $i++;
	                } else {
	                    if($cai==$p->actor_id) {
	                        if(!in_array($p->id, $aa)) {
	                            $a3["tema_" . ($i+1)] = array(
	                                            'actor'             => $p->actor->name,
	                                            'tema_' . ($i+1)    => $p->topic->text,
	                                            'tipo_' . ($i+1)	=> $p->type->name,
	                                            'estatus'           => $p->status 
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
	                'Tipo 1',
	                '+',
	                '-',
	                '=',
	                'Tema 2',
	                'Tipo 2',
	                '+',
	                '-',
	                '=',
	                'Tema 3',
	                'Tipo 3',
	                '+',
	                '-',
	                '=',
	                'Autor 2',
	                'Tema 1',
	                'Tipo 1',
	                '+',
	                '-',
	                '=',
	                'Tema 2',
	                'Tipo 2',
	                '+',
	                '-',
	                '=',
	                'Tema 3',
	                'Tipo 3',
	                '+',
	                '-',
	                '=',
	                'Autor 3',
	                'Tema 1',
	                'Tipo 1',
	                '+',
	                '-',
	                '=',
	                'Tema 2',
	                'Tipo 2',
	                '+',
	                '-',
	                '=',
	                'Tema 3',
	                'Tipo 3',
	                '+',
	                '-',
	                '='
	            ));

				//  Fix format
				$sheet->cells('A2:A200', function($cells) {
	                $cells->setAlignment('left');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('B2:B200', function($cells) {
	                $cells->setAlignment('left');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('C2:C200', function($cells) {
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('D2:D200', function($cells) {
	                $cells->setAlignment('left');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('E2:E200', function($cells) {
	                $cells->setAlignment('left');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('F2:F200', function($cells) {
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('G2:G200', function($cells) {
	                $cells->setAlignment('left');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('H2:H200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('12:1200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('J2:J200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('L2:L200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('M2:M200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('N2:N200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('P2:P200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('Q2:Q200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('R2:R200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('S2:S200', function($cells) {
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('U2:U200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('V2:V200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('W2:W200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('Y2:Y200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('Z2:Z200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AA2:AA200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AC2:AC200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AD2:AD200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AE2:AE200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AF2:AF200', function($cells) {
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AH2:AH200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AI2:AI200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AJ2:AJ200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AL2:AL200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AM2:AM200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AN2:AN200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AO2:AO200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AP2:AP200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AQ2:AQ200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AR2:AR200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AS2:AS200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AT2:AT200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AT2:AT200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AT2:AT200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AU2:AU200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AV2:AV200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AW2:AW200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AX2:AX200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AY2:AY200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('AZ2:AZ200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });
	            $sheet->cells('BA2:BA200', function($cells) {
	            	$cells->setFontWeight('bold');
	                $cells->setAlignment('center');
	                $cells->setValignment('middle');
	            });

	            /**
	             * Altura del la primer celda (Las demas heredan su tamaño)
	             */
	            for($i = 2; $i < 200; $i++) {
	            	$sheet->setHeight($i, 17);
	            }

	            /**
	             * Colores de algunas celdas
	             */
	            // Verde
	            $sheet->cells('I1:I200', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('N1:N200', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('S1:S200', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('S1:S200', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('AD1:AD200', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('AI1:AI200', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('AO1:AO200', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('AT1:AT200', function($cells) { $cells->setFontColor('#03d100'); });
	            $sheet->cells('AY1:AY200', function($cells) { $cells->setFontColor('#03d100'); });

	            // Rojo
	            $sheet->cells('J1:J200', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('O1:O200', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('T1:T200', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('Z1:Z200', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('AE1:AE200', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('AJ1:AJ200', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('AP1:AP200', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('AU1:AU200', function($cells) { $cells->setFontColor('#db0404'); });
	            $sheet->cells('AZ1:AZ200', function($cells) { $cells->setFontColor('#db0404'); });

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
		                (isset($d["actor_1"]["tema_1"]) ? $d["actor_1"]["tema_1"]["tipo_1"]:''),
		                (isset($d["actor_1"]["tema_1"]) && $d["actor_1"]["tema_1"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_1"]["tema_1"]) && $d["actor_1"]["tema_1"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_1"]["tema_1"]) && $d["actor_1"]["tema_1"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_1"]["tema_2"]) ? $d["actor_1"]["tema_2"]["tema_2"]:''),
		                (isset($d["actor_1"]["tema_2"]) ? $d["actor_1"]["tema_2"]["tipo_2"]:''),
		                (isset($d["actor_1"]["tema_2"]) && $d["actor_1"]["tema_2"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_1"]["tema_2"]) && $d["actor_1"]["tema_2"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_1"]["tema_2"]) && $d["actor_1"]["tema_2"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_1"]["tema_3"]) ? $d["actor_1"]["tema_3"]["tema_3"]:''),
		                (isset($d["actor_1"]["tema_3"]) ? $d["actor_1"]["tema_3"]["tipo_3"]:''),
		                (isset($d["actor_1"]["tema_3"]) && $d["actor_1"]["tema_3"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_1"]["tema_3"]) && $d["actor_1"]["tema_3"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_1"]["tema_3"]) && $d["actor_1"]["tema_3"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_2"]["actor"]) ? $d["actor_2"]["actor"]:''),
		                (isset($d["actor_2"]["tema_1"]) ? $d["actor_2"]["tema_1"]["tema_1"]:''),
		                (isset($d["actor_2"]["tema_1"]) ? $d["actor_2"]["tema_1"]["tipo_1"]:''),
		                (isset($d["actor_2"]["tema_1"]) && $d["actor_2"]["tema_1"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_2"]["tema_1"]) && $d["actor_2"]["tema_1"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_2"]["tema_1"]) && $d["actor_2"]["tema_1"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_2"]["tema_2"]) ? $d["actor_2"]["tema_2"]["tema_2"]:''),
		                (isset($d["actor_2"]["tema_2"]) ? $d["actor_2"]["tema_2"]["tipo_2"]:''),
		                (isset($d["actor_2"]["tema_2"]) && $d["actor_2"]["tema_2"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_2"]["tema_2"]) && $d["actor_2"]["tema_2"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_2"]["tema_2"]) && $d["actor_2"]["tema_2"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_2"]["tema_3"]) ? $d["actor_2"]["tema_3"]["tema_3"]:''),
		                (isset($d["actor_2"]["tema_3"]) ? $d["actor_2"]["tema_3"]["tipo_3"]:''),
		                (isset($d["actor_2"]["tema_3"]) && $d["actor_2"]["tema_3"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_2"]["tema_3"]) && $d["actor_2"]["tema_3"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_2"]["tema_3"]) && $d["actor_2"]["tema_3"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_3"]["actor"]) ? $d["actor_3"]["actor"]:''),
		                (isset($d["actor_3"]["tema_1"]) ? $d["actor_3"]["tema_1"]["tema_1"]:''),
		                (isset($d["actor_3"]["tema_1"]) ? $d["actor_3"]["tema_1"]["tipo_1"]:''),
		                (isset($d["actor_3"]["tema_1"]) && $d["actor_3"]["tema_1"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_3"]["tema_1"]) && $d["actor_3"]["tema_1"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_3"]["tema_1"]) && $d["actor_3"]["tema_1"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_3"]["tema_2"]) ? $d["actor_3"]["tema_2"]["tema_2"]:''),
		                (isset($d["actor_3"]["tema_2"]) ? $d["actor_3"]["tema_2"]["tipo_2"]:''),
		                (isset($d["actor_3"]["tema_2"]) && $d["actor_3"]["tema_2"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_3"]["tema_2"]) && $d["actor_3"]["tema_2"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_3"]["tema_2"]) && $d["actor_3"]["tema_2"]["estatus"]=='nn'?'1':''),

		                (isset($d["actor_3"]["tema_3"]) ? $d["actor_3"]["tema_3"]["tema_3"]:''),
		                (isset($d["actor_3"]["tema_3"]) ? $d["actor_3"]["tema_3"]["tipo_3"]:''),
		                (isset($d["actor_3"]["tema_3"]) && $d["actor_3"]["tema_3"]["estatus"]=='p'?'1':''),
		                (isset($d["actor_3"]["tema_3"]) && $d["actor_3"]["tema_3"]["estatus"]=='n'?'1':''),
		                (isset($d["actor_3"]["tema_3"]) && $d["actor_3"]["tema_3"]["estatus"]=='nn'?'1':'')

		            ));
					$i++;
	            }
				
	        });

	    })->export('xls');

		return "done";
	}

	//Generamos el excel del actor seleccionado
	public function excelFullTb($actor)
	{
		$_actor = Actor::where('rf_id',$actor)->first();

		$pieces = Piece::with('actor','topic','type','audits')
				  ->where( DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')") , "=", Carbon::today()->toDateString() )
				  ->where('actor_id',$_actor->id)
				  ->get();

		$data 	= array();

		foreach ($pieces as $p) {

			// Si no esta ligado a una nota lo omitimos
			if(count($p->audits)<1) continue;

			// Formamos la salida
			$md 					= array();
			$md['tipo'] 			= $p->type->name;
			$md['actor'] 			= ($p->actor->id==1?'CPA':'JGM');
			$md['calificacion']		= ($p->status=='p'?'Positivo':($p->status=='n'?'Negativo':'Neutral'));

			foreach ($p->audits as $a) {

				$_note  = NoticiasDia::with('periodico')->find($a->note_id);
				
				$md['fecha'] 		= $_note->Fecha;
				$md['periodico']	= $_note->periodico->Nombre;
				$md['titulo'] 		= $_note->Titulo;
				$md['pdf']			= ($_note->Categoria==80 || $_note->Categoria==98 ? $_note->Encabezado : "http://www.gaimpresos.com/Periodicos/".$_note->periodico->Nombre.'/'.$_note->Fecha.'/'.$_note->NumeroPagina);
			}

			$data[] 				= $md;

		}

		$file_name = 'Reporte Sonora ' . date('Y-m-d.H-i-s');

		Excel::create($file_name, function($excel) use($data) {

			$excel->sheet('Reporte', function($sheet) use($data) {

				// Creamos las celdas superiores
	            $sheet->row(1, array(
	                'Fecha',
	                'Medio',
	                'Tipo',
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
				    'C'	=> 17,
				    'D'	=> 9,
				    'E' => 40,
				    'F' => 40,
				    'G' => 14
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

	            // F ->
	            $sheet->cells('F1', function($cell) {
	            	$cell->setFontWeight('bold');
	            	$cell->setFontSize(13);
	            });

	            // G ->
	            $sheet->cells('G1', function($cell) {
	            	$cell->setFontWeight('bold');
	            	$cell->setFontSize(13);
	            });
	            $sheet->cells('G1:G2000', function($cell) {
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
		                $d["actor"],
		                $d["titulo"],
		                $d["pdf"],
		                $d["calificacion"]

		            ));
					$i++;
	            }
				
	        });

		})->export('xls');

		return "Done";
	}

	//Generamos el excel del actor seleccionado
	public function excelFullTb2()
	{

		$pieces = Piece::with('actor','topic','type','audits')
				  ->where( DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')") , "=", Carbon::today()->toDateString() )
				  ->get();

		$data 	= array();

		foreach ($pieces as $p) {

			// Si no esta ligado a una nota lo omitimos
			if(count($p->audits)<1) continue;

			// Formamos la salida
			$md 					= array();
			$md['tipo'] 			= $p->type->name;
			$md['actor'] 			= ($p->actor->id==1?'CPA':'JGM');
			$md['calificacion']		= ($p->status=='p'?'Positivo':($p->status=='n'?'Negativo':'Neutral'));

			foreach ($p->audits as $a) {

				$_note  = NoticiasDia::with('periodico')->find($a->note_id);
				
				$md['fecha'] 		= $_note->Fecha;
				$md['periodico']	= $_note->periodico->Nombre;
				$md['titulo'] 		= $_note->Titulo;
				$md['pdf']			= ($_note->Categoria==80 || $_note->Categoria==98 ? $_note->Encabezado : "http://www.gaimpresos.com/Periodicos/".$_note->periodico->Nombre.'/'.$_note->Fecha.'/'.$_note->NumeroPagina);
			}

			$data[] 				= $md;

		}

		$file_name = 'Reporte Sonora ' . date('Y-m-d.H-i-s');

		Excel::create($file_name, function($excel) use($data) {

			$excel->sheet('Reporte', function($sheet) use($data) {

				// Creamos las celdas superiores
	            $sheet->row(1, array(
	                'Fecha',
	                'Medio',
	                'Tipo',
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
				    'C'	=> 17,
				    'D'	=> 9,
				    'E' => 40,
				    'F' => 40,
				    'G' => 14
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

	            // F ->
	            $sheet->cells('F1', function($cell) {
	            	$cell->setFontWeight('bold');
	            	$cell->setFontSize(13);
	            });

	            // G ->
	            $sheet->cells('G1', function($cell) {
	            	$cell->setFontWeight('bold');
	            	$cell->setFontSize(13);
	            });
	            $sheet->cells('G1:G2000', function($cell) {
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
		                $d["actor"],
		                $d["titulo"],
		                $d["pdf"],
		                $d["calificacion"]

		            ));
					$i++;
	            }
				
	        });

		})->export('xls');

		return "Done";
	}
}
