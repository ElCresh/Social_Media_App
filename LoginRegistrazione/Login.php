<?php
    session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width = device-width, initial-scale = 1">
        <link rel="stylesheet" href="../css/w3.css">
        <link rel="stylesheet" href="../bootstrap/btt.css">
        <link rel="stylesheet" href="../css/mybtt.css">
        <link rel="stylesheet" href="../css/mycss.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="../node_modules/feather-icons/dist/icons/facebook.svg">    
        <title>Social_media_Data</title>
    </head> 
    <body>
        <div class="container-fluid" style="background-image: linear-gradient(100deg, #9fb8ad 0%, #0062cc 50%, #2cb5e8 100%);position: absolute; border-style: solid; border-color: black; max-height: max-content">
            <div class="center-text-image" style="margin-left: 20px"><h1 class="myfont" style="color: white; font-size: 5vw">Social Media Data</h1></div>
            <img src="../content/social-image.jpg" alt="Social" class="img-header rounded-left" style="height: 12.9vw; width: 12.6vw;">
        </div>
        <div style="margin-top: 1vw; position: absolute">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"style="max-width: 1250; margin-left: 3%">
                <h1 class="h2" style="font-size: 3vw; margin-top: 14vw">What does the application do?</h1>    
            </div>
            <div style="text-align: center; font-size: 2vw; margin-top: 2%">
                <p class="text-info">The application allows you to get the time-line of social media posts to which you are subscribed.</p>
            </div>
            <div class="myfont" style="margin-top: 4%; margin-left: 4% ">
                <ul>
                    <li style="font-size: 1.5vw">First, enter your credentials and login or register</li>
                </ul>
            </div>
            <div style="margin-left: 8%; margin-right: 8%; margin-top: 1.5%">
                <form name = "login" action = "elogin.php" method = "POST">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input type="email" id="email" type="text" class="form-control" name="email" placeholder="Email">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input type="password" id="password" type="password" class="form-control" name="password" placeholder="Password">
                    </div>
                    <div style="margin-top: 2%">
                        <button type="submit" name="btnAccedi" class="btn btn-light w3-hover-yellow" style="border-style: solid; border-color: lightgrey; background-color: greenyellow; color: blue">ENTER</button>
                        <button  type="button" name="btnRegistrati" id ="reg" onclick="myFunction()" class="btn btn-light w3-hover-yellow" style="border-style: solid; border-color: lightgrey; background-color: greenyellow; color: blue">NEW USER</button>
                        <button type="reset" name="reset" class="btn btn-light w3-hover-yellow" style="border-style: solid; border-color: lightgrey; background-color: greenyellow; color: blue">reset</button> 
                    </div>
                    <div>
                        <div  id="check" class="checkbox w3-hide">
                            <p><b>Inserisci i social a cui sei iscritto:</b></p>
                            <label style="margin-right: 1%" ><input type="checkbox" name="facebook" value=1><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 28 30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3v2h-2c-0.269 0-0.528 0.054-0.765 0.152-0.246 0.102-0.465 0.25-0.649 0.434s-0.332 0.404-0.434 0.649c-0.098 0.237-0.152 0.496-0.152 0.765v3c0 0.552 0.448 1 1 1h2.719l-0.5 2h-2.219c-0.552 0-1 0.448-1 1v7h-2v-7c0-0.552-0.448-1-1-1h-2v-2h2c0.552 0 1-0.448 1-1v-3c0-0.544 0.108-1.060 0.303-1.529 0.202-0.489 0.5-0.929 0.869-1.299s0.81-0.667 1.299-0.869c0.469-0.195 0.985-0.303 1.529-0.303zM18 1h-3c-0.811 0-1.587 0.161-2.295 0.455-0.735 0.304-1.395 0.75-1.948 1.303s-0.998 1.212-1.302 1.947c-0.294 0.708-0.455 1.484-0.455 2.295v2h-2c-0.552 0-1 0.448-1 1v4c0 0.552 0.448 1 1 1h2v7c0 0.552 0.448 1 1 1h4c0.552 0 1-0.448 1-1v-7h2c0.466 0 0.858-0.319 0.97-0.757l1-4c0.134-0.536-0.192-1.079-0.728-1.213-0.083-0.021-0.167-0.031-0.242-0.030h-3v-2h3c0.552 0 1-0.448 1-1v-4c0-0.552-0.448-1-1-1z"></path></svg> Facebook</label>
                            <label><input type="checkbox" name="twitter" value=2><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M32 7.075c-1.175 0.525-2.444 0.875-3.769 1.031 1.356-0.813 2.394-2.1 2.887-3.631-1.269 0.75-2.675 1.3-4.169 1.594-1.2-1.275-2.906-2.069-4.794-2.069-3.625 0-6.563 2.938-6.563 6.563 0 0.512 0.056 1.012 0.169 1.494-5.456-0.275-10.294-2.888-13.531-6.862-0.563 0.969-0.887 2.1-0.887 3.3 0 2.275 1.156 4.287 2.919 5.463-1.075-0.031-2.087-0.331-2.975-0.819 0 0.025 0 0.056 0 0.081 0 3.181 2.263 5.838 5.269 6.437-0.55 0.15-1.131 0.231-1.731 0.231-0.425 0-0.831-0.044-1.237-0.119 0.838 2.606 3.263 4.506 6.131 4.563-2.25 1.762-5.075 2.813-8.156 2.813-0.531 0-1.050-0.031-1.569-0.094 2.913 1.869 6.362 2.95 10.069 2.95 12.075 0 18.681-10.006 18.681-18.681 0-0.287-0.006-0.569-0.019-0.85 1.281-0.919 2.394-2.075 3.275-3.394z"></path></svg> Twitter</label>
                            <label style="margin-left: 1%" ><input type="checkbox" name="instagram" value=3><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 25 30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7 1c-0.811 0-1.587 0.161-2.295 0.455-0.735 0.304-1.395 0.75-1.948 1.302s-0.998 1.213-1.302 1.948c-0.294 0.708-0.455 1.484-0.455 2.295v10c0 0.811 0.161 1.587 0.455 2.295 0.304 0.735 0.75 1.395 1.303 1.948s1.213 0.998 1.948 1.303c0.707 0.293 1.483 0.454 2.294 0.454h10c0.811 0 1.587-0.161 2.295-0.455 0.735-0.304 1.395-0.75 1.948-1.303s0.998-1.213 1.303-1.948c0.293-0.707 0.454-1.483 0.454-2.294v-10c0-0.811-0.161-1.587-0.455-2.295-0.304-0.735-0.75-1.395-1.303-1.948s-1.213-0.998-1.948-1.303c-0.707-0.293-1.483-0.454-2.294-0.454zM7 3h10c0.544 0 1.060 0.108 1.529 0.303 0.489 0.202 0.929 0.5 1.299 0.869s0.667 0.81 0.869 1.299c0.195 0.469 0.303 0.985 0.303 1.529v10c0 0.544-0.108 1.060-0.303 1.529-0.202 0.489-0.5 0.929-0.869 1.299s-0.81 0.667-1.299 0.869c-0.469 0.195-0.985 0.303-1.529 0.303h-10c-0.544 0-1.060-0.108-1.529-0.303-0.489-0.202-0.929-0.5-1.299-0.869s-0.667-0.81-0.869-1.299c-0.195-0.469-0.303-0.985-0.303-1.529v-10c0-0.544 0.108-1.060 0.303-1.529 0.202-0.489 0.5-0.929 0.869-1.299s0.81-0.667 1.299-0.869c0.469-0.195 0.985-0.303 1.529-0.303zM16.989 11.223c-0.15-0.972-0.571-1.857-1.194-2.567-0.383-0.437-0.842-0.808-1.362-1.092-0.503-0.275-1.061-0.465-1.647-0.552-0.464-0.074-0.97-0.077-1.477-0.002-0.668 0.099-1.288 0.327-1.836 0.655-0.569 0.341-1.059 0.789-1.446 1.312s-0.674 1.123-0.835 1.766c-0.155 0.62-0.193 1.279-0.094 1.947s0.327 1.288 0.655 1.836c0.341 0.569 0.789 1.059 1.312 1.446s1.122 0.674 1.765 0.836c0.62 0.155 1.279 0.193 1.947 0.094s1.288-0.327 1.836-0.655c0.569-0.341 1.059-0.789 1.446-1.312s0.674-1.122 0.836-1.765c0.155-0.62 0.193-1.279 0.094-1.947zM15.011 11.517c0.060 0.404 0.037 0.798-0.056 1.168-0.096 0.385-0.268 0.744-0.502 1.059s-0.528 0.584-0.868 0.788c-0.327 0.196-0.698 0.333-1.101 0.393s-0.798 0.037-1.168-0.056c-0.385-0.096-0.744-0.268-1.059-0.502s-0.584-0.528-0.788-0.868c-0.196-0.327-0.333-0.698-0.393-1.101s-0.037-0.798 0.056-1.168c0.096-0.385 0.268-0.744 0.502-1.059s0.528-0.584 0.868-0.788c0.327-0.196 0.698-0.333 1.101-0.393 0.313-0.046 0.615-0.042 0.87-0.002 0.37 0.055 0.704 0.17 1.003 0.333 0.31 0.169 0.585 0.391 0.815 0.654 0.375 0.428 0.63 0.963 0.72 1.543zM18.5 6.5c0-0.552-0.448-1-1-1s-1 0.448-1 1 0.448 1 1 1 1-0.448 1-1z"></path></svg> Instagram</label>
                            <button type="submit" name="btnRegistrati" class="btn btn-light w3-hover-yellow" style="border-style: solid; border-color: lightgrey; background-color: blue; color: white">REGISTRATI</button>
                        </div>
                    </div>
                </form>
                <?php
                    if(isset($_GET['errore']))
                    {
                        echo "<p id = 'box_errore'"
                              . "style='background-color:red;font-weight:bold; color:yellow; border:2px solid white;'>";
                        switch($_GET['errore']){
                            case 1:
                                echo "Errore!: Password o Email errati...";
                                break;
                            case 2:
                                echo "Errore!: Email già esistente o errata...";
                                break;
                            case 3:
                                echo "Errore!: inserisci almeno un social...";
                                break;
                        }           
                    }
                    //se ritorno alla home dalla registrazione distruggo la sessione
                    if(isset($_GET['home'])){
                        session_destroy();
                    }
                ?>
                <div style="margin-top: 4%">
                    <p style="color: red">Read your privacy policy!</p>
                    <a href="https://www.iubenda.com/privacy-policy/61171298" class="iubenda-white iubenda-embed" title="Privacy Policy ">Privacy Policy</a><script type="text/javascript">(function (w,d) {var loader = function () {var s = d.createElement("script"), tag = d.getElementsByTagName("script")[0]; s.src="https://cdn.iubenda.com/iubenda.js"; tag.parentNode.insertBefore(s,tag);}; if(w.addEventListener){w.addEventListener("load", loader, false);}else if(w.attachEvent){w.attachEvent("onload", loader);}else{w.onload = loader;}})(window, document);</script>
                </div>
            </div>
        </div>
        <script>
            function myFunction() {
                var check = document.getElementById("check");
                var btn = document.getElementById("reg");
                if (check.className.indexOf("w3-show") === -1){
                    check.className += "w3-show";
                    i++;
                }else{
                        check.className = check.className.replace("w3-show", "");                 
                }
            }    
        </script>  
    </body> 
</html> 