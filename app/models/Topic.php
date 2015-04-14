<?php

class Topic extends \Eloquent {
	protected $fillable = [];
	public $timestamps = false;

    // Un topico puede pertenecer a una pieza
    public function piece()
    {
        return $this->hasMany("Piece");
    }
}