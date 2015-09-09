<?php
/**
 * CPanel Manager for EasyPHP.
 * 
 * @author Script47 <Script47@hotmail.com>
 * @version 1.0
 * @copyright (c) 2015, Script47
 */
namespace EasyPHP;

use Exception;

class EasyPHPManager
{
    /** Where EasyPHP plugins are held. **/
    const EASY_PHP_URL = "http://easyphp.script47.net/plugins/";
    /** The "library" dir in EasyPHP, full location on users server/device. **/
    const LIBRARY_DIR = __DIR__;
    
    /** All the exceptions that could occur. **/
    const FILE_PATH_ERROR = "EasyPHPManager Error: Plugin file location cannot be found.";
    const PLUGIN_DOES_NOT_EXIST_ERROR = "EasyPHPManager Error: Plugin does not exist in core EasyPHP plugins." ;
    const PLUGIN_COULD_NOT_BE_WRITTEN_ERROR_ERROR = "EasyPHPManager Error: Plugin file could not be written. Check your server permissions.";
    
    /** All the base names for the plugins I have made. **/
    private static $allBasePlugins = ["EasyPHPManager", "EasyPHPSessionManager", "EasyPHPAuthManager", "EasyPHPSanitizeManager", "EasyPHPValidationManager", "EasyPHPCookieManager", "EasyPHPFileManager", "EasyPHPLogManager"];
   
    /** All the base descriptions for the plugins I have made. **/
    private static $allBasePluginsDescriptions = ["The Core plugin manager for EasyPHP.", "The Session manager for EasyPHP, allows easy session management.", "The Auth manager for EasyPHP, allows easy hashing and encryption.", "The Sanitize manager for EasyPHP, allows easy sanitizing.", "The Validation manager for EasyPHP, allows easy validation testing.", "The Cookie manager for EasyPHP, allows easy cookie management. (Experimental)", "The File manager for EasyPHP, allows easy file managing.", "The Log manager for EasyPHP, allows easy error logging."];

    /** All the base versions for the plugins I have made. **/
    private static $allBasePluginsVersions = ["1.5", "1.0", "1.0", "1.0", "1.0", "1.0", "1.0", "0.5"];
    
    public static function autoLoad() 
    {
        foreach (self::$allBasePlugins as $plugin) {
            if (self::checkPluginExistsInLibrary($plugin)) {
                require_once self::LIBRARY_DIR . "\\" . $plugin . ".php";
            }
        }
    }

    /** Return the array of base plugin names. **/
    public static function getBasePlugins() 
    {
        return self::$allBasePlugins;
    }
    
    /** Return the array of base plugin descriptions.  **/
    public static function getBasePluginDescriptions()
    {
        return self::$allBasePluginsDescriptions;
    }
    
    /** Return the array of base plugin versions. **/
    public static function getBasePluginVersions()
    {
        return self::$allBasePluginsVersions;
    }
    
    /** Return the server version of a plugin. **/
    public static function getServerPluginVersions($plugin) 
    {
        return file_get_contents(self::EASY_PHP_URL . "/" . $plugin . "/Version.php");
    }    
    
    /** Download a plugin using the plugin name. **/
    public static function downloadPlugin($plugin)
    {
        $plugin = htmlspecialchars(trim($plugin));
        
        if (self::checkPluginExistsInArray($plugin)) {
            /** Check if file already exists. **/
            if (self::checkPluginExistsInLibrary($plugin)) {
                
                /** Delete existing file. **/
                unlink(self::LIBRARY_DIR . "\\" . $plugin . ".php");
                
                /** Try to get file from server. **/
                if (@file_get_contents(self::EASY_PHP_URL . $plugin . "/" . $plugin . ".txt")) {            
                    /** Try to create and write to file. **/
                    if (file_put_contents(self::LIBRARY_DIR . "\\" . $plugin . ".php", file_get_contents(self::EASY_PHP_URL . $plugin . "/" . $plugin . ".txt"))) {
                        return true;
                    } else {
                        throw new Exception(self::PLUGIN_COULD_NOT_BE_WRITTEN_ERROR);
                    }
                /** Failed to get file from server. **/    
                } else {
                    /** Check response status. **/
                    switch ($http_response_header[0]) {
                        case "HTTP/1.1 404 Not Found":
                            echo "File could not be downloaded as it cannot be found on the EasyPHP server (404 Not Found).";
                            break;
                        
                        default:
                            echo "Unknown error occurred when trying to get file from server. Header list " . $http_response_header[0] . ".";
                            break;                        
                    }
                }
            } else {
                /** Try to get file from server. **/
                if (@file_get_contents(self::EASY_PHP_URL . $plugin . "/" . $plugin . ".txt")) {
                    /** Try to create and write to file. **/
                    if (@file_put_contents(self::LIBRARY_DIR . "\\" . $plugin . ".php", file_get_contents(self::EASY_PHP_URL . $plugin . "/" . $plugin . ".txt"))) {
                        return true;
                    } else {
                        throw new Exception(self::PLUGIN_COULD_NOT_BE_WRITTEN_ERROR);
                    }
                /** Failed to get file from server. **/    
                } else {
                    /** Check response status. **/
                    switch ($http_response_header[0]) {
                        case "HTTP/1.1 404 Not Found":
                            echo "File could not be downloaded as it cannot be found on the EasyPHP server (404 Not Found).";
                            break;
                        
                        default:
                            echo "Unknown error occurred when trying to get file from server. Header list " . $http_response_header[0] . ".";
                            break;
                    }
                }                
            }
        } else {
            throw new Exception(self::PLUGIN_DOES_NOT_EXIST_ERROR);
        }
    }
    
    /** Delete a plugin using the plugin name. **/
    public static function deletePlugin($plugin) 
    {
        $plugin = htmlspecialchars(trim($plugin));
        
        /** Stop them from deleting the core class for EasyPHP, otherwise the front end (CPanel) won't work. **/
        if ($plugin == "EasyPHPManager") {
            exit("You have to delete the EasyPHPManager manually.");
        }
        
        /** Check if plugin they're trying to delete actually exists in array. **/
        if (self::checkPluginExistsInArray($plugin)) {
            /** Check if plugin they're trying to delte actually exists in the library DIR. **/
            if (self::checkPluginExistsInLibrary($plugin)) {
                unlink(self::LIBRARY_DIR . "\\" . $plugin . ".php");
                
                return true;
            } else {
                throw new Exception(self::FILE_PATH_ERROR);
            }
        } else {
            throw new Exception(self::PLUGIN_DOES_NOT_EXIST_ERROR);
        }
    }
    
    public static function updateAll()         
    {
        $pluginVersionCount = 0;
        
        foreach (self::getBasePlugins() as $plugin) 
        {
            if (self::getPluginServerVersion($plugin) > self::getBasePluginVersions()[$pluginVersionCount]) {
                self::downloadPlugin($plugin);
            }
            $pluginVersionCount++;
        }
    }


    public static function checkPluginExistsInArray($plugin) 
    {
        return in_array($plugin, self::getBasePlugins());
    }    
    
    public static function checkPluginExistsInLibrary($plugin)
    {
        return file_exists(self::LIBRARY_DIR . "\\" . $plugin . ".php");
    }
    
    public static function getPluginServerVersion($plugin) 
    {
        return @file_get_contents(self::EASY_PHP_URL . $plugin . "/Version.php");
    }
    
    /** Return the current EasyPHP Version (class constant). **/
    public static function getLocalEasyPHPVersion() 
    {
        return self::getBasePluginVersions()[0];
    }
}