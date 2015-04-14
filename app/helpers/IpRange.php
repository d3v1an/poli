<?php
class IpRange {

	// Verificacion de rango de ip
	public static function check($ip = null) {

		$iprange = Config::get('iprange.ips_range');

		foreach ($iprange as $ipr) 
        {
            if (strpos($ipr, '*')) 
            {
                $range = [ 
                    str_replace('*', '0', $ipr),
                    str_replace('*', '255', $ipr)
                ];

                if(self::ip_exists_in_range($range, $ip)) return true;
            } 
            else if(strpos($ipr, '-'))
            {
                $range = explode('-', str_replace(' ', '', $ipr));

                if(self::ip_exists_in_range($range, $ip)) return true;
            }
            else 
            {
                if (ip2long($ipr) === ip2long($ip)) return true;
            }
        }

		return false;
	}

	// Funcion para verificar el rango de ip's
	private static function ip_exists_in_range(array $range, $ip)
    {
        if (ip2long($ip) >= ip2long($range[0]) && ip2long($ip) <= ip2long($range[1])) 
        {
            return true;
        }
        return false;
    }
}