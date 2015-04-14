<?php

class Comunicator extends \Eloquent {
	protected $fillable = [];

	// Un comunicador pertenece a un programa
	public function program()
    {
        return $this->hasMany('Program');
    }
}