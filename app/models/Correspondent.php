<?php

class Correspondent extends \Eloquent {
	protected $fillable = [];

	// Un corresponsal contiene un comunicador
    public function comunicator()
    { 
        return $this->belongsTo('Comunicator'); 
    }

    // Un corresponsal pertenece a un programa
    public function program()
    {
        return $this->belongsToMany("Program");
    }
}