<?php

class Yedutil
{

    public static function userAgent() {
        $u_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }

        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }

        return array(
            'userAgent' => $u_agent,
            'name' => $bname,
            'version' => $version,
            'platform' => $platform,
            'pattern' => $pattern
        );
    }

    public static function curlGet($url) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
        ));
// Send the request & save response to $resp
        $resp = curl_exec($curl);
// Close request to clear up some resources
        curl_close($curl);
        return $resp;
    }

    public static function curlPost($url, $fields) {

        $fields_string = '';
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }

        rtrim($fields_string, '&');

//open connection
        $ch = curl_init();

//set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//execute post
        $result = curl_exec($ch);

//close connection
        curl_close($ch);

        return $result;
    }

    public static function dateDiff($from_date,$to_date = '',$type='days'){
        if(empty($to_date))
            $to_date = date('d-m-Y');

        $stamp_from = strtotime(self::formatDate($from_date,'d-m-Y'));
        $stamp_to = strtotime(self::formatDate($to_date,'d-m-Y'));

        $datediff = $stamp_from - $stamp_to;
        $days = floor($datediff/(60*60*24));
        $months = floor($datediff/(60*60*24*30));
        $hours = floor($datediff/(60*60*24));
        $mins = floor($datediff/(60*60));
        return isset($$type) ? $$type : $days;
    }


    public static function isPastDateByDay($date,$days){
        return time() > (strtotime($date) + ($days * 24 * 60 * 60));
    }

    public static function filter($str, $preg = true) {
        return $preg ? preg_replace("/[^A-Za-z0-9-]/", ' ', addslashes(strip_tags(htmlspecialchars($str)))) : addslashes(strip_tags(htmlspecialchars($str)));
    }

    public static function formatPhoneNumber($number) {
        $rx = "/
    (1)?\D*
    (\d{3})?\D*
    (\d{3})\D*
    (\d{4})
    (?:\D+|$)
    (\d*)
/x";
        preg_match($rx, $number, $matches);
        if(!isset($matches[0])) return false;

        $country = $matches[1];
        $area = $matches[2];
        $three = $matches[3];
        $four = $matches[4];
        $ext = $matches[5];

        $out = "$three$four";
        if(!empty($area)) $out = "$area$out";
        if(!empty($country)) $out = "+$country$out";
        if(!empty($ext)) $out .= "x$ext";

// check that no digits were truncated
// if (preg_replace('/\D/', '', $s) != preg_replace('/\D/', '', $out)) return false;
        return '+234'.$out;
    }

    public static function generateRandomString($length = 6, $validCharacters = '') {
        if(empty($validCharacters))
            $validCharacters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        $validCharLength = strlen($validCharacters);

        $result = "";
        for($i = 0; $i < $length; $i++) {
            $index = mt_rand(0, $validCharLength - 1);
            $result .= $validCharacters[$index];
        }

        return $result;
    }

    public static function money($amount,$decimal = 0,$symbol = '₦'){
        return $symbol.' '.number_format($amount,$decimal);
    }

    public static function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public static function getBrowser() {
        if(!isset($_SERVER['HTTP_USER_AGENT']))
            return;

        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }

        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }

        return array(
            'userAgent' => $u_agent,
            'name' => $bname,
            'version' => $version,
            'platform' => $platform,
            'pattern' => $pattern,
            'ip' => Y::ip(),
            'current_location' => YedUtil::locationFromIp(Y::ip()),
        );
    }

    static function contains($needle, $haystack)
    {
        return strpos($haystack, $needle) !== false;
    }

    static function spaceCap($string){
        return preg_replace('/(?<!\ )[A-Z]/', ' $0', $string);
    }

    static function array_sort($array, $on, $order=SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

    static function isPastDate($date){
        $stamp = strtotime(str_ireplace('/','-',$date));
        if(time() > $stamp)
            return true;

        return false;
    }

    static function compareDate($date,$date2=''){

        if(empty($date2)){
            $stamp_date2 = strtotime(date('d-m-Y'));
        }else{
            $reformat = str_ireplace('/','-',$date2);
            $stamp_date2 = strtotime($reformat);

        }

        $reformat_2 = str_ireplace('/','-',$date);
        //echo $reformat_2;die;
        $stamp_date = strtotime($reformat_2);
        if($stamp_date > $stamp_date2)
            return 'G';

        if($stamp_date < $stamp_date2)
            return 'L';

        if($stamp_date == $stamp_date2)
            return 'E';

        return false;
    }

    static function setTimezone($default = 'UTC') {
        $timezone = "";

        // On many systems (Mac, for instance) "/etc/localtime" is a symlink
        // to the file with the timezone info
        if (is_link("/etc/localtime")) {

            // If it is, that file's name is actually the "Olsen" format timezone
            $filename = readlink("/etc/localtime");

            $pos = strpos($filename, "zoneinfo");
            if ($pos) {
                // When it is, it's in the "/usr/share/zoneinfo/" folder
                $timezone = substr($filename, $pos + strlen("zoneinfo/"));
            } else {
                // If not, bail
                $timezone = $default;
            }
        }
        else {
            // On other systems, like Ubuntu, there's file with the Olsen time
            // right inside it.
            $timezone = file_get_contents("/etc/timezone");
            if (!strlen($timezone)) {
                $timezone = $default;
            }
        }
        date_default_timezone_set($timezone);
    }

    static function nicetime($date)
    {
        if(empty($date)) {
            return "No date provided";
        }

        $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths         = array("60","60","24","7","4.35","12","10");

        $now             = time();
        $unix_date         = strtotime($date);

        // check validity of date
        if(empty($unix_date)) {
            return "Bad date";
        }

        // is it future date or past date
        if($now > $unix_date) {
            $difference     = $now - $unix_date;
            $tense         = "ago";

        } else {
            $difference     = $unix_date - $now;
            $tense         = "from now";
        }

        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if($difference != 1) {
            $periods[$j].= "s";
        }

        return "$difference $periods[$j] {$tense}";
    }

    static function findTime($timestamp, $format) {
        $difference = $timestamp - time();
        if($difference < 0)
            return false;
        else{

            $min_only = intval(floor($difference / 60));
            $hour_only = intval(floor($difference / 3600));

            $days = intval(floor($difference / 86400));
            $difference = $difference % 86400;
            $hours = intval(floor($difference / 3600));
            $difference = $difference % 3600;
            $minutes = intval(floor($difference / 60));
            if($minutes == 60){
                $hours = $hours+1;
                $minutes = 0;
            }

            if($days == 0){
                $format = str_replace('Days', '?', $format);
                $format = str_replace('Ds', '?', $format);
                $format = str_replace('%d', '', $format);
            }
            if($hours == 0){
                $format = str_replace('Hours', '?', $format);
                $format = str_replace('Hs', '?', $format);
                $format = str_replace('%h', '', $format);
            }
            if($minutes == 0){
                $format = str_replace('Minutes', '?', $format);
                $format = str_replace('Mins', '?', $format);
                $format = str_replace('Ms', '?', $format);
                $format = str_replace('%m', '', $format);
            }

            $format = str_replace('?,', '', $format);
            $format = str_replace('?:', '', $format);
            $format = str_replace('?', '', $format);

            $timeLeft = str_replace('%d', number_format($days), $format);
            $timeLeft = str_replace('%ho', number_format($hour_only), $timeLeft);
            $timeLeft = str_replace('%mo', number_format($min_only), $timeLeft);
            $timeLeft = str_replace('%h', number_format($hours), $timeLeft);
            $timeLeft = str_replace('%m', number_format($minutes), $timeLeft);

            if($days == 1){
                $timeLeft = str_replace('Days', 'Day', $timeLeft);
                $timeLeft = str_replace('Ds', 'D', $timeLeft);
            }
            if($hours == 1 || $hour_only == 1){
                $timeLeft = str_replace('Hours', 'Hour', $timeLeft);
                $timeLeft = str_replace('Hs', 'H', $timeLeft);
            }
            if($minutes == 1 || $min_only == 1){
                $timeLeft = str_replace('Minutes', 'Minute', $timeLeft);
                $timeLeft = str_replace('Mins', 'Min', $timeLeft);
                $timeLeft = str_replace('Ms', 'M', $timeLeft);
            }

            return $timeLeft;
        }
    }

    static function rel_time($from, $to = null)
    {
        $to = (($to === null) ? (time()) : ($to));
        $to = ((is_int($to)) ? ($to) : (strtotime($to)));
        $from = ((is_int($from)) ? ($from) : (strtotime($from)));
        $output = '';
        $units = array
        (
            "year"   => 29030400, // seconds in a year   (12 months)
            "month"  => 2419200,  // seconds in a month  (4 weeks)
            "week"   => 604800,   // seconds in a week   (7 days)
            "day"    => 86400,    // seconds in a day    (24 hours)
            "hour"   => 3600,     // seconds in an hour  (60 minutes)
            "minute" => 60,       // seconds in a minute (60 seconds)
            "second" => 1         // 1 second
        );

        $diff = abs($from - $to);
        $suffix = (($from > $to) ? ("from now") : ("ago"));

        foreach($units as $unit => $mult)
            if($diff >= $mult)
            {
                $and = (($mult != 1) ? ("") : ("and "));
                $output .= ", ".$and.intval($diff / $mult)." ".$unit.((intval($diff / $mult) == 1) ? ("") : ("s"));
                $diff -= intval($diff / $mult) * $mult;
            }
        $output .= " ".$suffix;
        $output = substr($output, strlen(", "));

        return $output;
    }

    static function time_difference($endtime){
        $days= (date("j",$endtime)-1);
        $months =(date("n",$endtime)-1);
        $years =(date("Y",$endtime)-1970);
        $hours =date("G",$endtime);
        $mins =date("i",$endtime);
        $secs =date("s",$endtime);
        $diff="'day': ".$days.",'month': ".$months.",'year': ".$years.",'hour': ".$hours.",'min': ".$mins.",'sec': ".$secs;
        return $diff;
    }

    static function distanceOfTimeInWords($fromTime, $toTime = 0, $showLessThanAMinute = false) {
        $distanceInSeconds = round(abs($toTime - $fromTime));
        $distanceInMinutes = round($distanceInSeconds / 60);

        if ( $distanceInMinutes <= 1 ) {
            if ( !$showLessThanAMinute ) {
                return ($distanceInMinutes == 0) ? 'less than a minute' : '1 minute';
            } else {
                if ( $distanceInSeconds < 5 ) {
                    return 'less than 5 seconds';
                }
                if ( $distanceInSeconds < 10 ) {
                    return 'less than 10 seconds';
                }
                if ( $distanceInSeconds < 20 ) {
                    return 'less than 20 seconds';
                }
                if ( $distanceInSeconds < 40 ) {
                    return 'about half a minute';
                }
                if ( $distanceInSeconds < 60 ) {
                    return 'less than a minute';
                }

                return '1 minute';
            }
        }
        if ( $distanceInMinutes < 45 ) {
            return $distanceInMinutes . ' minutes';
        }
        if ( $distanceInMinutes < 90 ) {
            return 'about 1 hour';
        }
        if ( $distanceInMinutes < 1440 ) {
            return 'about ' . round(floatval($distanceInMinutes) / 60.0) . ' hours';
        }
        if ( $distanceInMinutes < 2880 ) {
            return '1 day';
        }
        if ( $distanceInMinutes < 43200 ) {
            return 'about ' . round(floatval($distanceInMinutes) / 1440) . ' days';
        }
        if ( $distanceInMinutes < 86400 ) {
            return 'about 1 month';
        }
        if ( $distanceInMinutes < 525600 ) {
            return round(floatval($distanceInMinutes) / 43200) . ' months';
        }
        if ( $distanceInMinutes < 1051199 ) {
            return 'about 1 year';
        }

        return 'over ' . round(floatval($distanceInMinutes) / 525600) . ' years';
    }

    static function forceDownload($dir) {
        if (file_exists($dir)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($dir));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($dir));
            ob_clean();
            flush();
            readfile($dir);
//exit;
        } else {
            die("File not found.");
//$this->render('error_download');
        }
    }

    static function formatPercent($num) {
        return number_format(($num * 100), 2) . '%';
    }

    public static function isInUrl($text){
        $url = $_SERVER['REQUEST_URI'];
        return stristr($url, $text);
    }

    public static function isMyDomain(){
        $ref = $_SERVER['HTTP_REFERER'];
        return stristr($ref,$_SERVER['HTTP_HOST']);
    }

    public static function validId($id){
        if(is_numeric($id) && $id > 0)
            return $id;

        return 0;
    }

    public static function pagination($current,$total,$max = 5){
        $count = $current;

        if($current < $max){
            $count = 1;
        }
        $gets = '';
        foreach($_GET as $key=>$val){
            if($key != 'pg')
                $gets .= $key.'='.$val.'&';
        }
        echo '<ul class="pagination"><li><a href="?'.$gets.'pg=1">&laquo;</a></li>';

        $totalPage = round($total/$max);
        for($i = $count; $i <= $total; $i+=$max){
            if($count < $current + $max){
                $active = $count == $current ? 'active' : '';
                echo '<li class="'.$active.'"><a href="?'.$gets.'pg='.$count.'">'.$count.'</a></li>';
                $count++;
            }
        }
        $count--;
        echo  '<li><a href="?'.$gets.'pg='.$count.'">&raquo;</a></li>
            </ul>';
    }

    public static function postToArray(){
        $data = array();
        if(!empty($_POST)){
            foreach($_POST as $key=>$val){
                //if($key != 'stage'){
                $data[$key] = $val;
                //}
            }
        }
        return $data;
    }

    /*
     * return list of hours for drop down list
     */
    public static function getHours(){
        $hours = array(''=>'N/A');
        for($i = 1; $i < 25; $i++){
            if($i < 10){
                $hours[$i] = '0'.$i;
            }else{
                $hours[$i] = $i;
            }
        }
        return $hours;
    }

    static function parseDateTime($string, $timezone=null) {
        $string = str_ireplace('/','-',$string);
        try{
            $date = new DateTime(
                $string,
                $timezone ? $timezone : new DateTimeZone('UTC')
            // Used only when the string is ambiguous.
            // Ignored if string has a timezone offset in it.
            );
            if ($timezone) {
                // If our timezone was ignored above, force it.
                $date->setTimezone($timezone);
            }
        }catch (Exception $e){
            $date = new DateTime(
                date('Y-m-d'),
                $timezone ? $timezone : new DateTimeZone('UTC')
            // Used only when the string is ambiguous.
            // Ignored if string has a timezone offset in it.
            );
            if ($timezone) {
                // If our timezone was ignored above, force it.
                $date->setTimezone($timezone);
            }
        }

        return $date;

    }

    public static function formatDate($date,$format){
        $datetime = self::parseDateTime($date);
        return $datetime->format($format);
    }

    static function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    static  function currentDate($format = 'Y-m-d H:i:s'){
        return date($format);
    }

    static function cors() {

        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
            header('Content-Type: application/json; charset=UTF-8');
            header( 'Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept');
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            exit(0);
        }

        //echo 'you have CORS<br/>';
    }

    static function getInnerSubstring($string, $boundstring, $trimit=false) {
        $res = false;
        $bstart = strpos($string, $boundstring);
        if ($bstart >= 0) {
            $bend = strrpos($string, $boundstring);
            if ($bend >= 0 && $bend > $bstart)
                $res = substr($string, $bstart+strlen($boundstring), $bend-$bstart-strlen($boundstring));
        }
        return $trimit ? trim($res) : $res;
    }

    static function getInbetweenStrings($start, $end, $str){
        $matches = array();
        $regex = "/$start([a-zA-Z0-9_]*)$end/";
        preg_match_all($regex, $str, $matches);
        return $matches;
    }

    static function GetBetween($var1="",$var2="",$pool){
        $temp1 = strpos($pool,$var1)+strlen($var1);
        $result = substr($pool,$temp1,strlen($pool));
        $dd=strpos($result,$var2);
        if($dd == 0){
            $dd = strlen($result);
        }

        return substr($result,0,$dd);
    }

    public static function debug($data){
        echo '<code><pre>';
        var_dump($data);
        echo '</pre></code>';die;
    }

    public static function timeOutRedirect($url,$seconds = 30){
      echo CHtml::script("
      var count = 0;
      var t = setInterval(function(){
        count++;
        if(count >= ".$seconds."){
            document.location.href= '".$url."';
            clearInterval(t);
        }
      },1000);
      ");
    }

    public static function forceRedirect($url){
        echo CHtml::script("document.location.href= '".$url."'");
        exit;
    }

    public static function shutdown(){

        $error = error_get_last();
        if ($error['type'] === E_ERROR) {
            Y::exception('wooo');
        }

    }

    public static function array_unshift_assoc(&$arr, $key, $val){
        $arr = array_reverse($arr, true);
        $arr[$key] = $val;
        return  array_reverse($arr, true);
    }

    public static function domain($url = false){
        if($url)
            return 'http://'.$_SERVER['HTTP_HOST'];

        return $_SERVER['HTTP_HOST'];
    }

    public static function locationFromIp($ip){
        $content = @file_get_contents("http://www.geoplugin.net/php.gp?ip=$ip");
        if($content !== false){
            $geo = unserialize($content);
            return $geo["geoplugin_city"]. ', '.$geo["geoplugin_countryName"];
        }

        return '';
    }


    static function checkPass($pass){
        $rex = '^(?=.*\d+)(?=.*[a-zA-Z])[0-9a-zA-Z!@#$%]{6,10}$^';
        $rx =  '^.*(?=.{7,15})(?=.*\d)(?=.*[a-zA-Z]).*$^';
        $rx1 =  '/[0-9]/';
        $rx1 =  '/[a-z]/';
        $rx1 =  '/[A-Z]/';
        $rx4 =  '/[!@#$%]/';
        $exps = array(
            array('length'=>'7','msg'=>'password must be greater than 6 characters!'),
            array('rex'=>'/[0-9]/','msg'=>'password must contain at least one number (0-9)!'),
            array('rex'=>'/[a-z]/','msg'=>'password must contain at least one lowercase letter (a-z)!'),
            array('rex'=>'/[A-Z]/','msg'=>'password must contain at least one uppercase letter (A-Z)!'),
            array('rex'=>'/[!@#$%]/','msg'=>'password must contain at least one Special Charracter (!@#$%)!'),
        );
        foreach($exps as $exp){
            if(array_key_exists('rex',$exp)){
                if(!preg_match($exp['rex'], $pass)){
                    return  $exp['msg'];
                }

            }elseif(array_key_exists('length',$exp)){
                if(strlen($pass) < $exp['length']){
                    return 	 $exp['msg'];
                }
            }
        }
        return '';
    }

    public static function watchScript(){
        try{
            $t1 = time(); // start to mesure time.
            while (true) { // put your long-time loop condition here
                $time_spent = time() - $t1;
                if($time_spent >= (60)) {
                    throw new Exception('Time Limit reached');
                }
                // do work here
            }
        } catch(Exception $e) {
           YedUtil::debug($e);
        }
    }


    static function compare($str1,$str2){
        $arg = explode(',',$str1);
        foreach($arg as $val){
            if(strpos('#'.$str2,$val))
                return true;
        }

        return false;
    }


    static function birthdate($date){
        $stamp = strtotime($date);
        $total = 0;
        $day = date('d',$stamp);
        $total = $day - date('d');
        $month = date('m',$stamp);
        if($month > date('m')){
            $dif = ((($month - date('m')) - 1) * 30) + $day;
            $total += $dif;
        }
        if($total > 0){
            $comment = $total.' Day(s) to Birthday';
        }else{
            $comment = 'Birthday celebrated '.($total * -1).' Day(s) Ago';
        }

        return $comment;

    }

}