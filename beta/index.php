<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link type="text/css" rel="stylesheet" href="css/styles.css" />
        <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
        <script type="text/javascript" src="js/WriteConfiguration.js"></script>
        <title>EasyPHP Plugin Manager</title>
    </head>
    <body>
        <?php
        ob_start();
        require_once 'library/EasyPHPManager.php';
        
        if (file_exists("config/config.php")) {
            require_once 'config/config.php';
        }
        
        EasyPHP\EasyPHPManager::autoLoad();
        
        if (defined("AUTO_UPDATE_PLUGINS") && AUTO_UPDATE_PLUGINS == 1) {
            echo \EasyPHP\EasyPHPManager::updateAll();
        }
        ?>
        
        <h1>EasyPHP Plugin Manager</h1>
        
        <div id="navigationBar">
            <ul>
                <li class="active">
                    <a href="index.php">
                        <span>Home</span>
                    </a>
                </li>
                <li>
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
        
        <p>
            <strong>Notice!</strong> Some plugins might require other plugins to be downloaded for them to work correctly and other might rely on a specific PHP Version.
        </p>     
                
        
        <p>
            Easily manage plugins through the new in built CPanel.
        </p>
        
        <div id="infoBox">
            <br/>
            
            <span class="label">
                Your PHP Version
            </span>
            
            <span class="labelText">
                <?= phpversion() ?>
            </span>
            
            <br/>
            <br/>
            
            <span class="label">
                Local EasyPHP Version
            </span>
            
            <span class="labelText">
                <?= EasyPHP\EasyPHPManager::getLocalEasyPHPVersion(); ?>
            </span>  
            
            <br/>
            <br/>
            
            <span class="label">
                Official EasyPHP Version
            </span>
            
            <span class="labelText">
                <?= EasyPHP\EasyPHPManager::getPluginServerVersion("EasyPHPManager") ?>
            </span>
            
            <br/>
            <br/>
        </div>   
        
        <?php
        if (EasyPHP\EasyPHPManager::getPluginServerVersion("EasyPHPManager") > EasyPHP\EasyPHPManager::getLocalEasyPHPVersion()) {    
            ?>
            <div id="updateNotice">
                <strong>You're using an outdated version of EasyPHP. Please </strong><a href="index.php?updateEasyPHP=true">Update EasyPHP</a>.
            </div>
            <?php

            if (isset($_GET['updateEasyPHP']) && $_GET['updateEasyPHP'] === "true") {
                EasyPHP\EasyPHPManager::downloadPlugin("EasyPHPManager");
            }
        }
        ?>        
        
        <a href="index.php?showAbout=true">About</a>
        
        <br/>
        
        <table>
            <th>Number</th>
            <th>Name</th>
            <th>Description</th>
            <th>Local Version</th>
            <th>Server Version</th>
            <th>Installed?</th>
            <th>Action</th>
            
            <?php
            $moduleCount = 1;
            $moduleDetailsCount = 0;
            foreach (EasyPHP\EasyPHPManager::getBasePlugins() as $plugin) {
                if (file_exists("library/" . $plugin . ".php")) {
                    ?>
                    <tr>
                        <td class="centre">
                            <?= $moduleCount ?>
                        </td>
                        <td class="justify">
                            <?= $plugin ?>
                        </td>
                        <td class="justify">
                            <?= EasyPHP\EasyPHPManager::getBasePluginDescriptions()[$moduleDetailsCount] ?>
                        </td>
                        <td>
                            <?= EasyPHP\EasyPHPManager::getBasePluginVersions()[$moduleDetailsCount] ?>
                        </td>
                        <td>
                            <?= EasyPHP\EasyPHPManager::getPluginServerVersion($plugin) ?>
                        </td>
                        <td>
                            <span class="moduleExists">Yes</span>
                        </td>
                        <td class="justify">
                            <?php
                            if (EasyPHP\EasyPHPManager::getBasePluginVersions()[$moduleDetailsCount] == EasyPHP\EasyPHPManager::getServerPluginVersions($plugin)){
                                ?>
                                <a href="index.php?delete=true&pluginName=<?= $plugin ?>">Delete <?= $plugin ?></a>
                                <?php
                            } else if (EasyPHP\EasyPHPManager::getPluginServerVersion($plugin) > EasyPHP\EasyPHPManager::getBasePluginVersions()[$moduleDetailsCount] && EasyPHP\EasyPHPManager::checkPluginExistsInLibrary($plugin)) {    
                                ?>
                                <a href="index.php?download=true&pluginName=<?= $plugin ?>">Update <?= $plugin ?></a>
                                <?php
                            }
                            ?>                            
                        </td>                        
                    </tr>
                    <?php
                } else {    
                    ?>
                    <tr>
                        <td class="centre">
                            <?= $moduleCount ?>
                        </td>                        
                        <td class="justify">
                            <?= $plugin ?>
                        </td>
                        <td class="justify">
                            <?= EasyPHP\EasyPHPManager::getBasePluginDescriptions()[$moduleDetailsCount] ?>
                        </td>   
                        <td>
                            <?= EasyPHP\EasyPHPManager::getBasePluginVersions()[$moduleDetailsCount] ?>
                        </td>                       
                        <td>
                            <?= EasyPHP\EasyPHPManager::getServerPluginVersions($plugin) ?>
                        </td>                          
                        <td>
                            <span class="moduleDoesNotExist">No</span>
                        </td>
                        <td class="justify">
                            <?php
                            if (EasyPHP\EasyPHPManager::getPluginServerVersion($plugin) > EasyPHP\EasyPHPManager::getBasePluginVersions()[$moduleDetailsCount - 1] && EasyPHP\EasyPHPManager::checkPluginExistsInLibrary($plugin)) {    
                                ?>
                                <a href="index.php?download=true&pluginName=<?= $plugin ?>">Update <?= $plugin ?></a>
                                <?php
                            } else {
                                ?>
                                <a href="index.php?download=true&pluginName=<?= $plugin ?>">Download <?= $plugin ?></a>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php    
                }
                $moduleCount++;
                $moduleDetailsCount++;
            }
            ?>
            </table>
            
            <?php
            if (isset($_GET['delete']) && $_GET['delete'] === "true") {
                if (EasyPHP\EasyPHPManager::deletePlugin($_GET['pluginName'])) {
                    header("Location: index.php");
                }
            }
            
            if (isset($_GET['download']) && $_GET['download'] === "true") {  
                if (EasyPHP\EasyPHPManager::downloadPlugin($_GET['pluginName'])) {
                    echo "File Downloaded. Automatically refreshing CPanel in 3 seconds.";
                    
                    header("Refresh:3; URL=index.php");
                }
            }
            
            if (isset($_GET['showAbout']) && $_GET['showAbout'] === "true") {
                ?>
                <p>
                    EasyPHP is an open source PHP framework consisting of several plugins to make PHP developers lives easier. Anyone can contribute to the framework which is available <a href="http://www.github.com/Script47">here</a>, you can fork it, update it then push it to the master repository. This way all developers and users can benefit from the most up to date code. 
                </p>        
                <?php
            }
            ?>
            <div id="footer">
                EasyPHP &COPY; <a href="http://www.script47.tk/" target="_blank">Script47</a> 2015
            </div>
    </body>
</html>
