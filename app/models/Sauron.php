<?php

class Sauron extends \Eloquent {
	protected $fillable = [];

	// Informacion de geolozalizacion
    public function geodata() 
    { 
        return $this->belongsTo('Geodata'); 
    }

    // Informacion de dispositivo
    public function devicedata() 
    { 
        return $this->belongsTo('Devicedata'); 
    }
}