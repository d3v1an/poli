<?php

class Roles extends \Eloquent {

	/**
     * Set timestamps off
    */
    public $timestamps = false;

	protected $fillable = [];

	//relación uno a uno entre users y roles
    public function user()
    {
        return $this->hasMany("User");
    }
}