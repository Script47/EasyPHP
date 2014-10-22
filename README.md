EasyPHP
=======

A class containing various PHP functions to make a developers life easier. Please feel free to add things to the class so we can make a better class.

<b>Version</b>: 1.0.0

Example
=======

I did simple test which involved a HTML text input and a submit button, the goal was to show the differences in not using EasyPHP class and using it.

<h3>Without EasyPHP</h3>

```php
if(isset($_POST['test'])) {
    if(!isset($_POST['email']) || empty($_POST['email'])) {
        echo "<font color='red'>Email field empty.</font>";
    } else if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == FALSE) {
        echo "<font color='red'>Email format incorrect.</font>";
    } else {
        $emailAddress = htmlspecialchars(trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)));
        echo "<font color='lightseagreen'>Your Email: " . $emailAddress . ". You will be redirected in 3 seconds.</font>";
        header("Refresh:3; URL=index.php");
    }           
}      

```

<b>Total Characters</b>: 670 characters
<br/>
<b>Average Characters Per Sentence</b>: 168

<h3>With EasyPHP</h3>

```php
if(isset($_POST['test'])) {
    if(EasyPHP::isEmpty($_POST['email']) == FALSE) {
        EasyPHP::message("Email field empty.", "error");
    } else if(EasyPHP::validate($_POST['email'], "email") == FALSE) {
        EasyPHP::message("Email format incorrect.", "error");
    } else {
        $emailAddress = EasyPHP::sanitize($_POST['email'], "email");
        EasyPHP::message("Your Email: " . $emailAddress . ". You will be redirected in 3 seconds.", "message");
        EasyPHP::redirect("index.php", "timed", 3);
    }           
}        
```

<b>Total Characters</b>: 584 characters
<br/>
<b>Average Characters Per Sentence</b>: 146

As the character count which I did shows EasyPHP has a difference of 86 characters and that is quite a big margin and coding is quicker and cleaner looking with EasyPHP.

Credits
=======

<b>Developer</b>: Script47
