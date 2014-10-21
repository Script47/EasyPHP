EasyPHP
=======

A class containing various PHP functions to make a developers life easier.

Example
=======

I did simple test which involved a HTML text input and a submit button, the goal was to showoff the functions which are used in EasyPHP to show you how you can write code quicker and more clearly using it.

<h3>Without EasyPHP</h3>

```
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

<h3>With EasyPHP</h3>

```
        if(isset($_POST['test'])) {
            if(PHP::isEmpty($_POST['email']) == FALSE) {
                PHP::message("Email field empty.", "error");
            } else if(PHP::validate($_POST['email'], "email") == FALSE) {
                PHP::message("Email format incorrect.", "error");
            } else {
                $emailAddress = PHP::sanitize($_POST['email'], "email");
                PHP::message("Your Email: " . $emailAddress . ". You will be redirected in 3 seconds.", "message");
                PHP::redirect("index.php", "timed", 3);
            }           
        }    
```

I then did a character count test and the results were as shown below.

<h3>Without EasyPHP</h3>

<b>Total Characters</b>: 670 characters
<b>Average Characters Per Sentence</b>: 168

<h3>With EasyPHP</h3>

<b>Total Characters</b>: 584 characters
<b>Average Characters Per Sentence</b>: 146

With a difference of 86 characters you can see it is quite a big margin and coding is quicker with EasyPHP.


