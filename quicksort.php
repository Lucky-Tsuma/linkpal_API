<?php

class QuickSort 
{

    public static function sort( $index , $my_array)
    {
        $loe = $gt = array();
        if(count($my_array) < 2)
        {
            return $my_array;
        }
        $pivot_key = key($my_array);
        $pivot = array_shift($my_array);
        foreach($my_array as $val)
        {
            if($val[$index] <= $pivot[$index])
            {
                $loe[] = $val;
            }elseif ($val[$index] > $pivot[$index])
            {
                $gt[] = $val;
            }
        }
        return array_merge(self::sort($index,$loe),array($pivot_key=>$pivot),self::sort($index,$gt));
    }

     public static function rsort( $index , $my_array)
    {
        return array_reverse(self::sort($index, $my_array));
    }
    
}

