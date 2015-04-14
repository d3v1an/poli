<?php

class Piece extends \Eloquent {
	protected $fillable = [];

	// Una pieza pertenece a una auditoria
    public function audits()
    {
        return $this->belongsToMany("Audit");
    }

    // Una pieza contiene un actor
    public function actor() 
    { 
        return $this->belongsTo('Actor'); 
    }
    
    // Una pieza contiene un actor
    public function topic() 
    { 
        return $this->belongsTo('Topic'); 
    }

    // Una pieza contiene un tipo
    public function type() 
    { 
        return $this->belongsTo('Type'); 
    }
}