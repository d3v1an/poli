<?php

class Audit extends \Eloquent {
	protected $fillable = [];

    // Piesas relacionadas
    public function pieces()
    {
        return $this->belongsToMany("Piece");
    }

    // Actores relacionados
    public function actor() 
    { 
        return $this->belongsTo('Actor','character_id','rf_id'); 
    }

    // Usuarios relacionados
    public function user() 
    { 
        return $this->belongsTo('User'); 
    }

    // Electronic news relacionados con la auditoria
    public function enews()
    {
        return $this->belongsTo('ElectronicNews','note_id','id'); 
    }

    // Verificamos si la auditoria ya existe
    public function hasAudited($id)
    {
        return (Autod::where('note_id',$id)->first());
    }
}
