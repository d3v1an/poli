<?php

class ElectronicNews extends \Eloquent {
	protected $fillable = [];

	public function program() 
    { 
        return $this->belongsTo('Program'); 
    }

    public function comunicator() 
    { 
        return $this->belongsTo('Comunicator'); 
    }

    public function actor() 
    { 
        return $this->belongsTo('Actor'); 
    }

    //relación muchos a muchos entre usuarios y cursos
    public function pieces()
    {
        return $this->belongsToMany("Piece");
    }

    // Audits
    public function audit()
    {
    	return $this->belongsTo('Audit', 'id', 'note_id');
    }
}