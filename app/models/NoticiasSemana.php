<?php

class NoticiasSemana extends \Eloquent {
	protected $fillable 	= [];
	protected $connection 	= 'mysqlMon';
	protected $table 		= 'noticiasSemana';
	protected $primaryKey 	= 'idEditorial';

	// Actores relacionados
    public function periodico() 
    { 
        return $this->belongsTo('Periodico','Periodico','idPeriodico'); 
    }
}