<?php

class Essentials
{
    private static function alterEach(&$item1){
        /* noInject each array item to each depth*/ 
        if(gettype($item1) === 'array'){$item1 = json_decode(self::noInject(json_encode($item1)),true);}
        else {$item1 = self::noInject($item1);}
    }

    public static function noInject($usertext,$arg1 = null)
    {
        /* If nothing is given - return nothing */ 
        if(!isset($usertext)){return null;}

        /* Setbool isJson for further processing*/
        if(!isset($arg1)){$isjson = self::isJson($usertext);}
        
        if(isset($isjson) && $isjson === true && !isset($arg1)){
            $jdtext = json_decode($usertext,true);

            if(gettype($jdtext) === "array"){
                array_walk($jdtext, array(__CLASS__,'alterEach'));
                $jdtext =  json_encode($jdtext);
                $jdtext = str_replace('&amp;','&',$jdtext);
            }else {
                $jdtext = self::noInject($jdtext);
            }
            return $jdtext;
        }elseif(!is_numeric($usertext)) {
            $usertext = str_replace('\\','&bsol;',$usertext);
            $usertext = str_replace("'",'&#39;',$usertext);
            $usertext = str_replace('`','&#96;',$usertext);
            $usertext = str_replace('/','&#47;',$usertext);
            $usertext = str_replace('/', '&#47;', $usertext);

            $usertext = self::entities($usertext);
            $usertext = htmlentities($usertext,ENT_HTML401);

            //New lines
            $usertext = preg_replace('/\r\n/', '<br>', trim($usertext));

            $usertext = str_replace(' ','&nbsp;',$usertext);
            $usertext = str_replace('&amp;amp;','&',$usertext);
            $usertext = str_replace('&amp;','&',$usertext);
            $usertext = str_replace('&amp;','&',$usertext);

            return $usertext;
        }
        return $usertext;
    }

    public static function realWhitespace($str){
        $str = str_replace('&nbsp;',' ',$str);
        while(strpos($str,'&amp;')){$str = str_replace('&amp;','&',$str);}
        return $str;
    }

    private static function entities( $string ) {
        $stringBuilder = "";
        $offset = 0;

        if ( empty( $string ) ) {return "";}

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
        if(!is_numeric($string))
        {
            @json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        }

        return false;
    }

    public static function shortenString($str = "",$length)
    {
        return strlen($str) > $length ? substr($str,0,$length)."..." : $str;
    }

    public static function timep(){
        return date("Y-m-d").'T'.date("H:i:s").'+02:00';
    }

    public static function validateAge($birthday, $age = 18)
    {
        // $birthday can be UNIX_TIMESTAMP or just a string-date.
        if(is_string($birthday)) {
            $birthday = strtotime($birthday);
        }

        // check
        // 31536000 is the number of seconds in a 365 days year.
        if(time() - $birthday < $age * 31536000)  {
            return false;
        }

        return true;
    }

    public static function validateLength($string,$min,$max)
    {
        return (strlen($string) >= $min && strlen($string) <= $max) ? true : false;
    }

    public static function currentUrl()
    {
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        return $actual_link;
    }

    public static function realBreak($usertext = "",$nlorbr = false){
        $usertext = self::noInject($usertext);
        if($nlorbr) {
            $usertext = str_replace('<br>', '&#10;', $usertext);
            $usertext = str_replace('&lt;br&gt;', '&#10;', $usertext);
            $usertext = str_replace('&bsol;r&bsol;n', '&#10;', $usertext);
            $usertext = str_replace('&bsol;n', '&#10;', $usertext);
            $usertext = str_replace('&bsol;r', '&#10;', $usertext);
        }else{
            $usertext = str_replace('&lt;br&gt;', '<br>', $usertext);
            $usertext = str_replace('&bsol;r&bsol;n', '<br>', $usertext);
            $usertext = str_replace('&bsol;n', '<br>', $usertext);
            $usertext = str_replace('&bsol;r', '<br>', $usertext);
        }
        return $usertext;
    }
    
    public static function progress_bar($done, $total, $info = "", $width = 50)
    {
        $perc = round(($done * 100) / $total);
        $bar = round(($width * $perc) / 100);
        return sprintf("%s%%[%s>%s]%s\r", $perc, str_repeat("=", $bar), str_repeat(" ", $width - $bar), $info);
    }
    
    public static function command_exist($cmd) {
        return !empty(shell_exec("which $cmd 2>/dev/null"));
    }
    
    public static function uid($length = 32,$call = 0) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function pingDomain($domain)
    {
        try {
            //code...
            $starttime = microtime(true);
            $file      = fsockopen($domain, 80, $errno, $errstr, 10);
            $stoptime  = microtime(true);
            $status    = 0;
    
            if (!$file) $status = -1;  // Site is down
            else {
                fclose($file);
                $status = ($stoptime - $starttime) * 1000;
                $status = floor($status);
            }
        } catch (\Throwable $th) {
            return false;
        }
        return $status;
    }
}

?>
