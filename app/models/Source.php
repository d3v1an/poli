<?php

// Canal de tv/radio?
class Source extends \Eloquent {
	protected $fillable = [];

	// Un canal contiene un programa (Program)
    public function program()
    {
        return $this->hasMany('Program');
    }

    // Un canal puede contener muchos programas
    // public function programs()
    // {
    //     return $this->belongsToMany("Program");
    // }
}