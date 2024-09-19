<?php

// globally define roles name
define('DOCTOR', 'doctor');
define('Compounder', 'compounder');
define('ADMIN', 'admin');
define('ADVERTISER', 'advertiser');
define('PHARMACEUTICAL', 'pharmaceutical');
define('COMPOUNDER', 'compounder');
define('USER', 'user');
define('HOSPITAL', 'hospital');
define('weekdays', ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
define('BD_DIVISIONS', ['Barishal', 'Chattogram', 'Dhaka', 'Khulna', 'Rajshahi', 'Rangpur', 'Sylhet', 'Mymensingh']);

function getRoutesName($routes, $onlyApp = null)
{
    $appRoutes = [];
    foreach ($routes as $route) {
        if (str_starts_with($route->getName(), 'app.')) {
            $permission = explode('.', substr($route->getName(), 4));
            if (array_key_exists(1, $permission)) {
                $appRoutes[$permission[0]][] = substr(implode('.', $permission), strlen($permission[0]) + 1);
            } else {
                $appRoutes['app'][] = $permission[0];
            }
        }
    }
    if ($onlyApp) {
        return $appRoutes['app'];
    } else {
        unset($appRoutes['app']);

        return $appRoutes;
    }
}

function convert24to12($time24)
{
    list($hours, $minutes) = explode(':', $time24);

    $hours24 = $hours;
    $period = $hours24 >= 12 ? 'PM' : 'AM';
    $hours12 = $hours24 % 12 ? $hours24 % 12 : 12;
    $time12 = $hours12 . ':' . $minutes . ' ' . $period;
    return $time12;
}

function valid_phone($phone) {
    $phone = str_replace('-','', $phone);
    $phone = str_replace(' ','', $phone);
    $numericMobileNumber = preg_replace('/[^0-9]/', '', $phone);
    if (preg_match('/^(?:\+?88)?(.+)$/', $numericMobileNumber, $matches)) {
        $modifiedNumber = '+88' . $matches[1];
        $number = $matches[1];
        if (strlen($number) != 11) {
            return false;
        } else {
            return $modifiedNumber;
        }
    } else {
        return false;
    }
}

function sendMultipleMessage($smsData) {
    $url = "https://api.greenweb.com.bd/api.php?json";
    $token = env('SMS_API_TOKEN');
    if ($token && count($smsData)) {
        try {
            $data = [
                "token" => $token,
                "smsdata" => json_encode($smsData, JSON_UNESCAPED_UNICODE)
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_ENCODING, '');
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);

              //response
            //   echo $response;
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    } else {
        dd('error');
    }
}

function saveLog($message, $file=null) {
    $path = 'logs/';
    $path .= $file ? $file : 'common.log';
    $log_path = storage_path($path);
    if ($message) {
        try {
            error_log("[" . date('Y-m-d H:i:s') . '] ' . $message . "\n", 3, $log_path);
        } catch (\Throwable $th) {}
    }
}

function sendMessage($phone, $content)
{
    try {
        $to = $phone;
        $token = env('SMS_API_TOKEN');
        $message = $content;
        if ($token) {

            $url = "http://api.greenweb.com.bd/api.php?json";

            $data = array(
                'to' => "$to",
                'message' => "$message",
                'token' => "$token"
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_ENCODING, '');
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $smsresult = curl_exec($ch);
        }
        try {
            saveLog($phone, 'smsresult.log');
            saveLog($message, 'smsresult.log');
        } catch (\Throwable $th) {}
    } catch (\Throwable $th) {
        try {
            saveLog($th->getMessage(), 'smserror.log');
        } catch (\Throwable $th) {}
    }
}

