<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">        
        <link type="text/css" rel="stylesheet" href="css/styles.css" />
        <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
        <script type="text/javascript" src="js/WriteConfiguration.js"></script>
        <title>EasyPHP Configuration Writer</title>
    </head>
    <body>
        <?php
        ob_start();
        require_once 'library/EasyPHPManager.php';
        
        if (file_exists("config/config.php")) {
            require_once 'config/config.php';
        }
        EasyPHP\EasyPHPManager::autoLoad();
        ?>
        
        <h1>EasyPHP Configuration Writer</h1>
        
        <div id="navigationBar">
            <ul>
                <li>
                    <a href="index.php">
                        <span>Home</span>
                    </a>
                </li>
                <li class="active">
                    <a href="WriteConfiguration.php">
                        <span>Write Configuration</span>
                    </a>
                </li>                
                <li>
                    <a href="index.php?showFAQ=true"><span>FAQ</span></a>
                </li>
                <li class="last">
                    <a href="index.php?showVersionHistory=true">
                        <span>Version History</span>
                    </a>
                </li>
            </ul>
        </div>

        <p>Set some default options so EasyPHP can use your settings.</p>
        
        <br/>
        
        <?php
        
        if (defined("CONFIG_FILE_WRITTEN_ON") && trim(!empty("CONFIG_FILE_WRITTEN_ON"))) {
            echo '<a href="WriteConfiguration.php?deleteConfig=true">Delete Configuration</a>';
            
            if (array_key_exists("deleteConfig", $_GET)) {
                if (unlink("config/config.php")) {
                    echo "<p>Configuration file deleted.</p>";
                    
                    header("Refresh: 3; URL=WriteConfiguration.php");
                } 
            }
            echo "<p>Last configuration write was on " . CONFIG_FILE_WRITTEN_ON . ".</p>";
            echo "<p>IP Address of the person who wrote the config file last " . IP_ADDRESS_OF_LAST_WRITE . ".</p>";
        }       
        ?>
        
        <br/>
        
        <fieldset>
            <legend>Configuration</legend>
                <form action="#" method="post">            
                    <label>Default Hash Algorithm</label>

                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;            

                    <select onchange="WriteConfiguration.doGoogleSearch(this.value);" name="hashAlgo" id="hashAlgo">
                        <?php
                        foreach (hash_algos() as $algo) {
                            ?>
                            <?php 
                            if (defined("DEFAULT_HASH_ALGORITHM") && $algo == DEFAULT_HASH_ALGORITHM) {
                                ?>
                                <option value="<?= $algo ?>" selected><?= $algo ?></option>
                                <?php
                            } else {
                                ?>
                                <option value="<?= $algo ?>"><?= $algo ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>

                    <span id="hashURL"></span>

                    <br/>
                    <br/>

                    <label>Default Salt Length</label>

                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;            
                    &nbsp;
                    &nbsp;             
                    &nbsp;
                    &nbsp;

                    <?php
                    if (defined("DEFAULT_SALT_LENGTH") == true) {
                        ?>
                        <input type="number" name="saltLength" value="<?= DEFAULT_SALT_LENGTH ?>" min="1" required />
                        <?php
                    } else {
                        ?>
                        <input type="number" name="saltLength" value="<?= DEFAULT_SALT_LENGTH ?>" min="1" required />
                        <?php
                    }
                    ?>

                    <br/>
                    <br/>

                    <label>Auto Update Plugins</label>

                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;

                    <?php
                    if (defined("AUTO_UPDATE_PLUGINS") && AUTO_UPDATE_PLUGINS == 1) {
                        ?>
                        <input type="checkbox" name="autoUpdate" checked />
                        <?php
                    } else {
                        ?>
                        <input type="checkbox" name="autoUpdate" />
                        <?php
                    }
                    ?>

                    <br/>
                    <br/>

                    <input type="submit" name="writeConfig" class="writeConfigButton" value="Write Configuration" />
                </form>
        
                <p style="text-align: center;">
                    <?php
                    if (array_key_exists("writeConfig", $_POST)) {
                        $hashAlgorithm = trim(htmlspecialchars($_POST['hashAlgo']));
                        $saltLength = trim(htmlspecialchars(intval($_POST['saltLength'])));
                        $fileCreatedOn = date("d/m/Y h:i:s A", time());
                        $autoUpdate;
                        $IP = null;

                        if (isset($_POST['autoUpdate'])) {
                            $autoUpdate = 1;
                        } else {
                            $autoUpdate = 0;
                        }

                        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
                            $IP = $_SERVER['HTTP_CLIENT_IP'];
                        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
                            $IP = $_SERVER['HTTP_X_FORWARDED'];
                        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
                            $IP = $_SERVER['HTTP_FORWARDED_FOR'];
                        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
                            $IP = $_SERVER['HTTP_FORWARDED'];
                        } else if (isset($_SERVER['REMOTE_ADDR'])) {
                            $IP = $_SERVER['REMOTE_ADDR'];
                        }

                        $configFile = '<?php' 
                                . PHP_EOL 
                                . '/**'
                                . PHP_EOL
                                . ' * The EasyPHP configuration file.'
                                . PHP_EOL
                                . ' *' 
                                . PHP_EOL 
                                . ' * Created on ' . $fileCreatedOn . '.'
                                . PHP_EOL
                                . ' * IP Address of the person who wrote it last ' . $IP . '.'
                                . PHP_EOL
                                . ' */' 
                                . PHP_EOL . PHP_EOL
                                . '/** Last time the config file was written on. **/'
                                . PHP_EOL
                                . 'define("CONFIG_FILE_WRITTEN_ON", "' . $fileCreatedOn . '");'
                                . PHP_EOL . PHP_EOL
                                . '/** IP Address of the last person who wrote this file.. **/'
                                . PHP_EOL                        
                                . 'define("IP_ADDRESS_OF_LAST_WRITE", "' . $IP . '");'
                                . PHP_EOL . PHP_EOL
                                . '/** User defined default hash algorithm. **/'
                                . PHP_EOL
                                . 'define("DEFAULT_HASH_ALGORITHM", "' . $hashAlgorithm . '");'
                                . PHP_EOL . PHP_EOL 
                                . '/** User defined default salt length. **/'
                                . PHP_EOL
                                . 'define("DEFAULT_SALT_LENGTH", ' . $saltLength . ');' 
                                . PHP_EOL . PHP_EOL
                                . '/** Should we auto update plugins? 0 => No, 1 => Yes. **/'
                                . PHP_EOL
                                . 'define("AUTO_UPDATE_PLUGINS", ' . $autoUpdate . ');'
                                . PHP_EOL;

                        if (file_exists("config/")) {
                            if (file_exists("config/config.php")) { 
                                unlink("config/config.php");

                                if (file_put_contents("config/config.php", $configFile)) {
                                    echo "Config file created, refreshing in 3 seconds.";

                                    header("Refresh: 3; URL=WriteConfiguration.php");
                                }
                            } else {
                                if (file_put_contents("config/config.php", $configFile)) {
                                    echo "Config file created, refreshing in 3 seconds.";

                                    header("Refresh: 3; URL=WriteConfiguration.php");
                                }                        
                            }
                        } else {
                            if (mkdir("config")) {                    
                                if (file_put_contents("config/config.php", $configFile)) {
                                    echo "Config file created, refreshing in 3 seconds.";

                                    header("Refresh: 3; URL=WriteConfiguration.php");
                                }
                            }
                        }
                    }
                    ?>
                </p>
        </fieldset>
    </body>
</html>
