<?php

class NoticiasMensual extends \Eloquent {
	protected $fillable = [];
	protected $connection 	= 'mysqlMon';
	protected $table 		= 'noticiasMensual';
	protected $primaryKey 	= 'idEditorial';

	// Actores relacionados
    public function periodico() 
    { 
        return $this->belongsTo('Periodico','Periodico','idPeriodico'); 
    }
}