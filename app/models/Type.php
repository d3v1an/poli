<?php

class Type extends \Eloquent {
	protected $fillable = [];
	public $timestamps = false;

	// Un tipo puede pertenecer a una pieza
    public function piece()
    {
        return $this->hasMany("Piece");
    }
}