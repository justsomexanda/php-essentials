<?php

class Essentials
{
    private static function alterEach(&$item1, $key){ /* noInject each array item */ */ $item1 = self::noInject($item1,'A');}

    public static function noInject($usertext,$arg1 = null)
    {
        if(!isset($arg1))
        {
            $isjson = self::isJson($usertext);
        }
        
        if(isset($isjson) && $isjson === true && !isset($arg1)){
            $jdtext = json_decode($usertext,true);

            array_walk($jdtext, array(__CLASS__,'alterEach'));
        }else {
            $usertext = self::entities($usertext);
            $usertext = str_replace('\\','&bsol;',$usertext);
        }
        if(!isset($arg1))
        return $usertext;
    }

    private static function entities( $string ) {
        $stringBuilder = "";
        $offset = 0;

        if ( empty( $string ) ) {
            return "";
        }

        while ( $offset >= 0 ) {
            $decValue = self::ordutf8( $string, $offset );
            $char = self::unichr($decValue);

            $htmlEntited = htmlentities( $char );
            if( $char != $htmlEntited ){
                $stringBuilder .= $htmlEntited;
            } elseif( $decValue >= 128 ){
                $stringBuilder .= "&#" . $decValue . ";";
            } else {
                $stringBuilder .= $char;
            }
        }

        return $stringBuilder;
    }

    // source - http://php.net/manual/en/function.ord.php#109812
    private static function ordutf8($string, &$offset) {
        $code = ord(substr($string, $offset,1));
        if ($code >= 128) {        //otherwise 0xxxxxxx
            if ($code < 224) $bytesnumber = 2;                //110xxxxx
            else if ($code < 240) $bytesnumber = 3;        //1110xxxx
            else if ($code < 248) $bytesnumber = 4;    //11110xxx
            $codetemp = $code - 192 - ($bytesnumber > 2 ? 32 : 0) - ($bytesnumber > 3 ? 16 : 0);
            for ($i = 2; $i <= $bytesnumber; $i++) {
                $offset ++;
                $code2 = ord(substr($string, $offset, 1)) - 128;        //10xxxxxx
                $codetemp = $codetemp*64 + $code2;
            }
            $code = $codetemp;
        }
        $offset += 1;
        if ($offset >= strlen($string)) $offset = -1;
        return $code;
    }

    // source - http://php.net/manual/en/function.chr.php#88611
    private static function unichr($u) {
        return mb_convert_encoding('&#' . intval($u) . ';', 'UTF-8', 'HTML-ENTITIES');
    }

    public static function getUserIpAddr(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public static function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}

?>
