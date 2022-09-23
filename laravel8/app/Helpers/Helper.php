<?php
namespace App\Helpers;

class Helper
{
	public static function getDate()
    {
        date_default_timezone_set("Asia/Hong_Kong");
        $cdates = date("Y-m-d");
        
        return $cdates;
    }

    public function compareTwoDates($date1, $date2)
    {
		$dateTimestamp1 = strtotime($date1);
		$dateTimestamp2 = strtotime($date2);
		  
		// Compare the timestamp date 
		if ($dateTimestamp1 == $dateTimestamp2)
		    return "equal";
		else if ($dateTimestamp1 < $dateTimestamp2)
		    return "later";
		else
			return "ealier";
    }
}