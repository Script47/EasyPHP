<?php
/**
 * This class is intended to make coding in PHP more cleaer by renaming functions. It will also speed up coding instead of writing lines of code you could 
 * simply use functions which have been used to make easier.
 *
 * @author Script47
 * @copyright (c) 2014, Script47
 * @version 1.1.4
 * @license https://github.com/Script47/EasyPHP/blob/master/LICENSE.md LICENSE.md
 */
class EasyPHP {
    /**
     * @var string - An array of emails which you'd like to inform in the log function. 
     */
    private static $mailTo = array(
        "example@example.com"
    );
    
    /**
     * @var string - DIR to the file you want to include. 
     */
    private static $includes = array(
        "test.php"
    );
    
    /**
     * The autoload function don't forget to instantiate the object.
     */
    public static function autoLoader() {
        foreach(self::$includes as $include) {
            require_once $include;
        }
    }
    
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
            case "IP":
                return filter_var($input, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ? $input : filter_var($input, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? $input : FALSE;
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
                return "<font color='lightseagreen'> $message </font>";
                break;
            case "success":
                return "<font color='green'> $message </font>";
                break;
            case "error":
                return "<font color='red'> $message </font>";
                break;
        }
    }    
    
    /**
     * Redirect a user to a certain page.
     * @param string $page - The page name you'd like to redirect to.
     * @param int $time - If timed then set a time limit.
     */
    public static function redirect($page, $time = 0) {
        header("Refresh: $time; URL=$page");
    }
    
    /**
     * Get the IP of the user.
     * @return string - The IP of the user.
     */
    public static function getIP() {
        $IP = NULL;
        if(isset($_SERVER['HTTP_CLIENT_IP'])) {
            $IP = $_SERVER['HTTP_CLIENT_IP'];
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
     * Add an item to the log (errors.txt).
     * @param $log - The log message you'd like to insert.
     * @param bool $isErrorMajor - Allows you to check if the error in question is major or not, if the log is major then it will be emailed to all the emails from the $mailTo array. Otherwise it will be simply logged in the errors.txt files.
     * @return bool - Returns TRUE for success, FALSE if anything goes wrong.
     */
    public static function log($log, $isErrorMajor = FALSE) {
        if($isErrorMajor == null || $isErrorMajor == FALSE) {
            if(file_exists("logs") == TRUE) {
                $fileToBeOpened = fopen("logs/errors.txt", 'a');
                $timestamp = date("d/m/Y h:i:s");
               
                fwrite($fileToBeOpened, "Minor Error ($timestamp) - " . $log . PHP_EOL . PHP_EOL);
                fclose($fileToBeOpened);
                return TRUE;
            } else {
                if(!mkdir("logs")) {
                    trigger_error("Could not create logs directory.");
                    return FALSE;
                }
            }
        } else if($isErrorMajor == TRUE) {
            if(file_exists("logs") == TRUE) {
                $fileToBeOpened = fopen("logs/errors.txt", 'a');
                $timestamp = date("d/m/Y h:i:s");
               
                fwrite($fileToBeOpened, "Major Error ($timestamp) - " . $log . PHP_EOL . PHP_EOL);
                fclose($fileToBeOpened);
 
                self::notify($log, $timestamp);
                return TRUE;
            } else {
                if(!mkdir("logs")) {
                    trigger_error("Could not create logs directory.");
                }
            }
        }
    }
    
    /**
     * Parse a string as BBCode.
     * @param string $value - The string you'd like to parse.
     * @return string - Return a parsed string.
     */
    public static function BBCode($value) {
        $findAndReplace = array(
            "[b]" => "<strong>",
            "[/b]" => "</strong>",
            "[i]" => "<i>",
            "[/i]" => "</li>",
            "[u]" => "<u>",
            "[/u]" => "</u>",
            "[s]" => "<s>",
            "[/s]" => "</s>",
            "[br]" => "<br/>",
            "[li]" => "<li>",
            "[/li]" => "</li>",
            "[numli]" => "<ol><li>",
            "[/numli]" => "</li></ol>"
        );
        return str_replace(array_keys($findAndReplace), array_values($findAndReplace), $value);
    }
 
    /**
     * Notifies the people from $mailTo array via email.
     * @param $message - The email's main content.
     * @param $logGeneratedOn - A timestamp of when the email was generated on.
     */
    public static function notify($message, $logGeneratedOn) {
        return mail(implode(",", self::$mailTo), "New Major Error Logged" . $message . PHP_EOL . PHP_EOL . "This log was generated on " . $logGeneratedOn . PHP_EOL . PHP_EOL . "The time above is set using the server time.", "From: Logs@EasyPHPClass\n");
    }
    
    /**
     * Check if a variable is not set.
     * @param $variable - The variable you'd like to check.
     * @return - TRUE if empty, otherwise the variable.
     */
    public static function isNotEmpty($variable) {
        return isset($variable) || !empty($variable) ? $variable : FALSE;
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
    public static function decode($input) {
        return base64_decode(strtr($input, '-_,', '+/='));
    }
    
    /**
     * Create a session.
     */
    public static function createSession() {
        session_start();
        return new self;
    }
    
    /**
     * Return the session ID of the user.
     * @return string - Session ID.
     */
    public function sessionID() {
        return session_id();
    }
    
    /**
     * Destroys the created session.
     */
    public static function destroySession() {
        session_unset();
        session_destroy();
    }
    
    /**
     * Debug a variable using this function.
     * @param $debugVariable - The variable you'd like to debug.
     * @return - Debugged results.
     */
    public static function debug($debugVariable) {
        return var_dump($debugVariable);
    }
    
    /**
     * Kill the page.
     * @param string $message - Optional message to display.
     */
    public static function endPage($message = NULL) {
        isset($message) && !empty($message) ? exit($message) : exit;
    }   
}