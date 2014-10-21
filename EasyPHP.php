<?php
/**
 * This class is intended to make coding in PHP more cleaer by renaming functions. It will also speed up coding instead of writing lines of code you could 
 * simply use functions which have been used to make easier.
 *
 * @author Script47
 * @copyright (c) 2014, Script47
 * @version 1.0
 * @example index.php - Some main examples.
 */
class EasyPHP {
    private $loggerSendTo = "";

    /**
     * Validate a variable, string, int, float or an email.
     * @param $input - The variable which you would like to validate.
     * @param - String, int, float or email.
     * @return - FALSE if the check fails otherwise it will return the original input variable.
     */
    public static function validate($input, $type) {
        switch($type) {
            case "string":
                return is_string($input) ? $input : FALSE;
                break;
            case "int":
                return is_integer($input) ? $input : FALSE;
                break;
            case "float":
                return is_float($input) ? $input : FALSE;
                break;
            case "email":
                return filter_var($input, FILTER_VALIDATE_EMAIL) ? $input : FALSE;
                break;
        }
    }
    
    /**
     * Clean a variable, string, int float or an email.
     * @param $input - The variable you'd like to sanitize.
     * @param $type - String, int, float or email.
     * @return - Returns the cleaned variable.
     */
    public static function sanitize($input, $type) {
        switch($type) {
            case "string":
                return htmlspecialchars(trim((filter_var($input, FILTER_SANITIZE_STRING))));
                break;
            case "int":
                return htmlspecialchars(trim(filter_var($input, FILTER_SANITIZE_NUMBER_INT)));
                break;
            case "float":
                return htmlspecialchars(trim(filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT)));
                break;
            case "email":
                return htmlspecialchars(trim(filter_var($input, FILTER_SANITIZE_EMAIL)));
                break;
        }
    }

    /**
     * Display a message depending on the type.
     * @param string $message
     * @param string $type - message means a normal message and success message and error message.
     */
    public static function message($message, $type) {
        switch($type) {
            case "message":
                echo "<font color='lightseagreen'> $message </font>";
                break;
            case "success":
                echo "<font color='green'> $message </font>";
                break;
            case "error":
                echo "<font color='red'> $message </font>";
                break;
        }
    }    
    
    /**
     * Redirect a user to a certain page.
     * @param string $page - The page name you'd like to redirect to.
     * @param string $type - Instant or timed.
     * @param int $time - If timed then set a time limit.
     */
    public static function redirect($page, $type, $time = "") {
        switch($type) {
            case "timed":
                header("Refresh: $time; URL=$page");
                break;
            case "instant":
                header("Location: $page");
                break;
        }
    }
    
    /**
     * Get the IP of the user.
     * @return string - The IP of the user.
     */
    public static function getIP() {
        $IP = NULL;
        if(isset($_SERVER['HTTP_CLIENT_IP'])) {
            $IP = $_SERVER['HTTP_CLIENT_IP'];
        } else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $IP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if(isset($_SERVER['HTTP_X_FORWARDED'])) {
            $IP = $_SERVER['HTTP_X_FORWARDED'];
        } else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $IP = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if(isset($_SERVER['HTTP_FORWARDED'])) {
            $IP = $_SERVER['HTTP_FORWARDED'];
        } else if(isset($_SERVER['REMOTE_ADDR'])) {
            $IP = $_SERVER['REMOTE_ADDR'];
        } else {
            $IP = "UNKNOWN";      
        }
        return $IP;
    }
    
    /**
     * A simple email error logger or general logger.
     * @param string $message - The main contents of the email.
     */
    public static function logger($message) {
        $timeStamp = date("h:i:s d/m/Y");
        mail($this->loggerSendTo, "PHP Logger", $message . PHP_EOL . PHP_EOL . "Sent On - " . $timeStamp, "From: $sendTo");
    }
    
    /**
     * Check if a variable is not set.
     * @param $variable - The variable you'd like to check.
     * @return - TRUE if empty, otherwise the variable.
     */
    public static function isEmpty($variable) {
        return !isset($variable) || empty($variable) ? FALSE : $variable;
    }
    
    /**
     * Generate a random salted string.
     * @param int $size - The length of the string you want to generate (max 40).
     * @return string - A random string at the length of $size;
     */
    public static function generateSalt($size) {  
        return substr(sha1(mt_rand()), 0, $size);
    }
    
    /**
     * Hash a string using your a hash type.
     * @param string $hashType - The type of hash you want to use.
     * @param string $input - The string you'd like to hash.
     * @return string - The hashed string.
     */
    public static function hash($hashType, $input) {
        return hash($hashType, $input);
    }
    
    /**
     * Encode a string.
     * @param string $input - The string to encode. DON'T USE FOR PASSWORDS.
     * @return string - Encoded string.
     */
    public static function encode($input) {
        return strtr(base64_encode($input), '+/=', '-_,');
    }

    /**
     * Decode a string.
     * @param string $input - The encoded string you'd like to decode.
     * @return string - Decoded string.
     */
    public static function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_,', '+/='));
    }
    
    /**
     * Create a session.
     */
    public static function createSession() {
        session_start();
    }
    
    /**
     * Destroys the created session.
     */
    public static function destroySession() {
        session_unset();
        session_destroy();
    }
    
    /**
     * Kill the page.
     * @param string $message - Optional message to display.
     */
    public static function endPage($message = NULL) {
        isset($message) && !empty($message) ? exit($message) : exit;
    }
}