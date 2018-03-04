<?php
   
   include("classes/DB.class.php");      /*include class file as we are using class methods to fetch data from database*/
   session_start();              /*start session*/

   if(isset($_POST["login"])){   /*check if submit button on login page is clicked*/

      $myusername = $_POST['username'];
      $mypassword = sha1($_POST['password']);

      $db = new DB();
      $data = $db->checkLogin($myusername,$mypassword);  /*call method to check username and password are correct*/
      
      if($data['userid'] != ""){    /*if some user is returned then continue*/
         session_register("myusername");     /*store required values in session*/
         $_SESSION['login_user'] = $myusername;
         $_SESSION['pwd'] = $mypassword;
         $_SESSION['isadmin'] = $data['isadmin'];
         $_SESSION['uid'] = $data['userid'];
         
         header("location: index.php");      /*redirect user to index page where he can add items to cart*/
      }
      else
         $error = "Your Login Name or Password is invalid"; /*throw invalid username or password error*/
   } 
?>

<!-- display login page with username and password -->
<!DOCTYPE html>
<html>   
   <head>
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <title>Login Page</title>
      <link href="style/style.css" type="text/css" rel="stylesheet" />      
   </head>
   
   <body>	
      <div class="login_main">
         <div style = "width:300px; border: solid 1px #333333; ">
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login</b></div>
            <div style = "margin:30px">
               <form method = "post"><!-- action = "login.php" -->
                  <label>UserName  :</label><input type = "text" name = "username" class = "box"/><br /><br />
                  <label>Password  :</label><input type = "password" name = "password" class = "box" /><br/><br />
                  <input type = "submit" name='login' value = " Submit "/><br />
               </form>
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
            </div>
         </div>
      </div>
   </body>
</html>