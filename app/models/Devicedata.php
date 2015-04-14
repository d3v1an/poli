<?php

class Devicedata extends \Eloquent {
	protected $fillable = [];

	// Relacion con el log sauron
    public function sauron()
    {
        return $this->hasMany("Sauron");
    }
}