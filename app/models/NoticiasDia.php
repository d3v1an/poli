<?php

class NoticiasDia extends \Eloquent {
	protected $fillable 	= [];
	protected $connection 	= 'mysqlMon';
	protected $table 		= 'noticiasDia';
	protected $primaryKey 	= 'idEditorial';

	// Actores relacionados
    public function periodico() 
    { 
        return $this->belongsTo('Periodico','Periodico','idPeriodico'); 
    }
}