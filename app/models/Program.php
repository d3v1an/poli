<?php

class Program extends \Eloquent {
	protected $fillable = [];

	// Un programa pertenece a un canal (Source)
    public function source()
    { 
        return $this->belongsTo('Source'); 
    }

    // Una programa contiene un comunicador
    public function comunicator()
    { 
        return $this->belongsTo('Comunicator'); 
    }

    // Un programa contiene muchos corresponsales
    public function correspondents()
    {
        return $this->belongsToMany("Correspondent");
    }

    public function enews()
    {
        return $this->hasMany("ElectronicNews");
    }

	// Verificamos si el id ya existe
	// if($p->hasCorrespondent(1)) return "Existe";
    // else return "No existe";
	public function hasCorrespondent($id)
	{
	    return $this->correspondents->contains($id);
	}
}