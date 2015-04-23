<?php

class Periodico extends \Eloquent {
	protected $fillable = [];
	protected $connection 	= 'mysqlMon';
	protected $table 		= 'periodicos';
	protected $primaryKey 	= 'idPeriodico';
}