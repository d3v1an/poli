<?php

class Actor extends \Eloquent {
	protected $fillable = [];
	public $timestamps = false;

	// Un Actor puede pertenecer a una pieza
    public function piece()
    {
        return $this->hasMany("Piece");
    }

    // Relacion entre un actor y auditorias de impresos
    public function audit()
    {
        return $this->hasMany('Audit','character_id','rf_id');
    }

    // Relacion entre un actor y auditoria de electronicos
    // public function eaudit()
    // {
    //     return $this->hasMany('Audit','character_id','rf_id');
    // }

    //relación uno a uno entre users y roles
    public function enews()
    {
        return $this->hasMany('ElectronicNews','actor_id','id');
    }
}