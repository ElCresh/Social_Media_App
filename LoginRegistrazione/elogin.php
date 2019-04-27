<?php
    require '../database_query/db_conn_query.php';
    require '../php-smtp-email-validation/mail/smtp_validateEmail.class.php';
    require ("../vendor/autoload.php");
    require_once('../vendor/j7mbo/twitter-api-php/TwitterAPIExchange.php');
    use Facebook\Facebook;
    session_start();
    
    //varibile globale che contiene il risultato della verifica esistenza email
    global $results;
    
    //recupero il contenuto della textbox email
    if((isset($_POST['email'])||isset($_POST['password']))){
        $_SESSION['email'] = $_POST['email'];
        //...e quello della textbox password
        $_SESSION['password'] = $_POST['password'];
    }
    
    //verifico che la pagina sia stata richiesta dalla form o dal bottone accedi di facebook
    if($_SERVER['REQUEST_METHOD']=='POST' || isset($_GET['code']))
    {
        //ho premuto il bottone accedi e non è settato il get di facebook
        if(isset($_POST['btnAccedi']) && empty($_GET['code']))
        {        
            $_SESSION['btnAccedi']=$_POST['btnAccedi'];
            $autenticazione_cliente = "select pass from tb_clienti where email ='".$_SESSION['email']."'"; 
            
            db_select("localhost", "root", "", "socialmediadata");
            
            $risultato_query=query($autenticazione_cliente);
                    
            //data l'email inserita verifico la corrispondenza della psw
            if(($riga_cliente = mysqli_fetch_assoc($risultato_query)))
            {
                
                //verifico la corrispondeza della psw inserita(hashed) e quella stored nel db
                $autenticato_utente  = password_verify($_SESSION['password'], $riga_cliente['pass']);
                 
            }else{
                $autenticato_utente = false;
            }
            
            //l'autenticazione è andata a buon fine
            if($autenticato_utente){
                $id_utente = mysqli_fetch_assoc(query($query));
                db_close_conn($conn);    
                header("Location:../Home/Home.php");
                
            //l'autenticazione è fallita quindi rimando errore 1
            }else{
                    header("Location:Login.php?errore=1");//posizioniamo un errore
                                                                //dovuto alla non
                                                                //autenticazione
                                                                //che verrà gestito  
                                                                //nella pagina relativa
                    exit;
            }
            
        //è stato premuto il pulsante registra            
        } else{
            if(isset($_POST['facebook']) || isset($_POST['twitter']) || isset($_POST['instagram'])){
                if(isset($_POST['facebook'])){
                    $_SESSION['facebook'] = $_POST['facebook'];
                }
                if(isset($_POST['twitter'])){
                    $_SESSION['twitter'] = $_POST['twitter'];
                }
                //TODO:instagram
            }
            //prima verifico che sia stato spuntato almeno un social
            //rimando errore altrimenti
            if(isset($_SESSION['facebook']) || isset($_POST['facebook'])|| 
               isset($_SESSION['twitter']) || isset($_POST['twitter'])){
                //TODO:aggiungere instagram
                
                //al primo accesso alla pagina verifico l'esistenza della mail inserita
                if(isset($_POST['facebook']) || isset($_POST['twitter'])){
                    //TODO:aggiungere instagram
                    
                    //utilizzo il protocollo smtp per verificare l'esistenza della mail inserita
                    $sender = 'link89luca@gmail.com';
                    $SMTP_Validator = new SMTP_validateEmail();
                    //$SMTP_Validator->debug = true;-->debug true viene mostrata la serie di richieste/risposte
                    $SMTP_Validator->debug = false;
                    $results = $SMTP_Validator->validate(array($_SESSION['email']), $sender);
                    $_SESSION['risultato'] = $results[$_SESSION['email']];
                }
                
                //ricerco che la mail non sia stata già utilizzata
                db_select("localhost", "root", "", "socialmediadata");                   
                $query = "select email from tb_clienti where email = '".$_SESSION['email']."'";
                $risultato_utenti = mysqli_fetch_assoc(query($query));
                
                //se la mail è già stata utilizzata o la mail non esiste rimando errore
                if(($risultato_utenti !== null) || (!$_SESSION['risultato']))
                {
                    db_close_conn($conn);
                    header("Location:Login.php?errore=2");
                    exit();
                } 
                
                else{
                    //tutti i controlli sono andati a buon fine
                    //devo richiedere l'accesso ai social che sono stati spuntati per poter inserire i dati nel db
                    ?>
                    <html>
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width = device-width, initial-scale = 1">
                            <link rel="stylesheet" href="../css/w3.css">
                            <link rel="stylesheet" href="../bootstrap/btt.css">
                            <link rel="stylesheet" href="../css/mybtt.css">
                            <link rel="stylesheet" href="../css/mycss.css">
                            <title>Login</title>
                        </head> 
                        <body class="body-style" >
                            <div class="container-fluid" style="background-color: #10707f">  
                                <nav class="navbar navbar-expand-xl"style="background-color:  #10707f">
                                    <div style="text-align: center;margin-left: 0"
                                        <ul class="navbar-nav">
                                            <li>
                                                <div style="border-right: 1px solid lightgray; width: 105%">
                                                    <div style>
                                                        <b class="myfont w3-opacity" style="font-size: 22px; color: white; margin-right:  15%">RegisterLogin</b>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="nav-link">
                                                <div>
                                                <a class="nav-item alert-link w3-hover-text-lime" href="Login.php?home"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 25 30" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                                    home
                                                </a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </nav>
                            </div>
                            <?php
                            //TODO:aagiungere instagram
                                if(isset($_SESSION['facebook'])&&isset($_SESSION['twitter'])){
                                    echo"<div><b><p style='color:red; margin-left: 2%; margin-top: 2%'>Effettua i login ai tuoi social:</p></b></div>";
                                }
                                elseif(isset($_SESSION['facebook'])){
                                    echo"<div><b><p style='color:red; margin-left: 2%; margin-top: 2%'>Effettua i login ai tuoi social:</p></b></div>";
                                }
                                elseif (isset ($_SESSION['twitter'])) {
                                    echo"<div><b><p style='color:red; margin-left: 2%; margin-top: 2%'>Effettua i login ai tuoi social:</p></b></div>";
                                }
                                
                            ?>    
                    <?php
                        //se è stata settato la checkbox facebook allora mostro il pulsante di connessione
                        if(isset($_SESSION['facebook']) || isset($_POST['facebook'])){
                            //step 1: Enter credentials
                            //utilizzo la libreria dell'SDK
                            $fb = new Facebook([
                                'app_id' => '646457439148163',
                                'app_secret' => 'caae448a3967762864ddcf43a979d514',
                                'default_graph_version' => 'v3.2'
                            ]);
                            
                            //pulsante rimanda al login facebook
                            ?>
                                <div id='connection' class='container' style='margin-left: 0; margin-top: 1%; position: absolute; max-width:max-content'>
                                <ul>
                                    <li>
                                        <p style='font-size: 20px;'>Effettua il login su FaceBook:</p>
                                    </li>
                                </ul>
                            <?php
                                echo"<a href='{$fb->getRedirectLoginHelper()->getLoginUrl("http://localhost/Social_Media_App/LoginRegistrazione/elogin.php")}'><img id='btn_image' src='../content/login_fb_button.png' width=200 class='hoverable' style='position:absolute; margin-left:10%'></a></div>";
                                //ottengo il token di accesso
                                $access_token = $fb->getRedirectLoginHelper()->getAccessToken();

                                /*Step 4: Get the graph user*/
                                if(isset($access_token) || isset($_SESSION['token'])) {
                                    try {
                                        if(isset($access_token)){
                                            //salvo il token di accesso nella variabile di sessione
                                            $_SESSION['token'] = $access_token->getValue();
                                            //ho effettuato l'accesso
                                            $response = $fb->get('/me', $access_token);
                                            //get user graph
                                            $fb_user = $response->getGraphUser();
                                            //ottengo l'id di facebook
                                            $_SESSION['facebook_id']=$user_id = $fb_user->getId();
                                        }else{
                                            //ho effettuato l'accesso
                                            $response = $fb->get('/me', $_SESSION['token']);
                                        }
                                        //rendo invisibile il pulsante
                                        if(isset($_SESSION['token'])){
                                            echo"<script>document.getElementById('connection').style.display='none'</script>";
                                            echo"<script>document.getElementById('btn_image').style.display='none'</script>";
                                        }

                                        //estraggo i post
                                        $posts_request= $fb->get('/me/posts?fields=message, created_time, id, permalink_url', $_SESSION['token']);
                                        //ottengo il body della richiesta. ovvero il testo dei post
                                        $_SESSION['post'] = $posts_request->getBody();
                                        //true ritorna un'arrai invece di un oggetto
                                        $_SESSION['facebook_data']=$json=json_decode($_SESSION['post'], true);
                                        
                                                                                
                                        //verifico che sia stato settato twitter come social o che sia stato settato l'id
                                        if(isset($_SESSION['twitter']) || isset($_SESSION['twitter_id'])){
                                            //ho l'id di twitter
                                            if(isset($_SESSION['twitter_id_ok'])){
                                                $_SESSION['facebook_id_ok']=true;
                                                header('Location:../Home/Home.php');
                                            //altrimenti significa che ancora non mi sono loggato su twitter quindi rimango sulla pagina
                                            }else{
                                                $_SESSION['facebook_id_ok']=true;
                                                unset($_SESSION['facebook']);
                                            }
                                        //non ho settato twitter e di conseguenza nemmeno l'id posso andare alla home    
                                        }else{
                                            //setto che non ho l'id di twitter TODO:instagram e rimando alla Home
                                            $_SESSION['twitter_id_ok']=false;
                                            $_SESSION['facebook_id_ok']=true;
                                            db_close_conn($conn);
                                            unset($_SESSION['facebook']);
                                            header('Location:../Home/Home.php');
                                        }
                                        
                                    } catch (\Facebook\Exceptions\FacebookResponseException $e) {
                                        echo  'Graph returned an error: ' . $e->getMessage();
                                    } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                                    // When validation fails or other local issues
                                    echo 'Facebook SD returned an error: ' . $e->getMessage();
                                }
                            }
                        }
                        //se è stata selezionata la checkbox di twitter
                        if(isset($_SESSION['twitter'])){
                            //se non è stato settato facebook mostro una interfaccia altrimenti ne mostro un'altra
                            if(!isset($_POST['facebook'])){
                                //pulsante rimanda al login di twitter
                                ?>
                                    <div id='twit_connection' class='container' style='margin-left: 0; margin-top: 1%; position: absolute; max-width:max-content'>
                                        <ul>
                                            <li>
                                                <p style='font-size: 20px;'>Iserisci il tuo UserName Twitter:</p>
                                            </li>
                                        </ul>
                                        <form class='form-inline' name = 'get_twit' action = 'elogin.php' method = 'POST' style='margin-top: 1.5vw; margin-left: 3vw; max-width: max-content'>
                                            <div class='form-group'>
                                                <input type='text' class='form-control' id='twitter_name' name='twitter_name' placeholder='Enter Twitter Name'>
                                            </div>
                                            <div style='margin-left: 1vw'>
                                                <button type='submit' name='btnTwit' class='btn btn-light w3-hover-yellow' style='border-style: solid; border-color: lightgrey; background-color: greenyellow; color: blue'>Submit</button>
                                            </div>
                                        </form>
                                    </div>
                            <?php
                            }else{
                            ?>
                                    <div id='twit_connection' class='container' style='margin-left: 0; margin-top: 8%; position: absolute; max-width:max-content'>
                                    <ul>
                                        <li>
                                            <p style='font-size: 20px;'>Iserisci il tuo UserName Twitter:</p>
                                        </li>
                                    </ul>
                                    <form class='form-inline' name = 'get_twit' action = 'elogin.php' method = 'POST' style='margin-top: 1.5vw; margin-left: 3vw; max-width: max-content'>
                                        <div class='form-group'>
                                            <input type='text' class='form-control' id='twitter_name' name='twitter_name' placeholder='Enter Twitter Name'>
                                        </div>
                                        <div style='margin-left: 1vw'>
                                            <button type='submit' name='btnTwit' class='btn btn-light w3-hover-yellow' style='border-style: solid; border-color: lightgrey; background-color: greenyellow; color: blue'>Submit</button>
                                        </div>
                                    </form>
                                </div> 
                            <?php
                            }
                                /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
                                $settings = array(
                                    'oauth_access_token' => "1116240988954071040-sE450blS1aBYGNDiAh338jlum6KEJX",
                                    'oauth_access_token_secret' => "zmKuK6tQMr2CO07KxZgEDaYlbULOxwnlxbd5jcLebGBiF",
                                    'consumer_key' => "PKPZ8tUJVDXPK7RwZUMz3Z8tJ",
                                    'consumer_secret' => "MkMXiFpvNfDEXawZZnieInFd8g3d8LTdGK7krmu3JvYtWA67r5"
                                );

                                $url = "https://api.twitter.com/1.1/statuses/user_timeline.json";

                                $requestMethod = "GET";

                                if (isset($_POST['twitter_name'])) {
                                    $twitter_name = preg_replace("/[^A-Za-z0-9_]/", '', $_POST['twitter_name']);
                                    $getfield = "?screen_name=".$twitter_name."&since_id=7.3484950086766E+17 ";
                                    $twitter = new TwitterAPIExchange($settings);
                                    //convert to an associative array
                                    $_SESSION['twitter_data']=$string = json_decode($twitter->setGetfield($getfield)
                                        ->buildOauth($url, $requestMethod)
                                        ->performRequest(),$assoc = TRUE);
                                    //setto il twitter_id questo permette di segnalare che si è loggati su twitter. In questo modo
                                    //selezionando il pulsante facebook e loggati si viene rimandati all'altra pagina
                                    $_SESSION['twitter_id']=$string[0]['user']['id_str'];
                       
                                    //stampo l'eventuale errore ritornato da twitter
                                    if(array_key_exists('errors', $string)) {
                                        echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string['errors'][0]["message"]."</em></p>";exit();}
          
                                    if(isset($_SESSION['facebook']) || isset($_SESSION['facebook_id'])){
                                            if(isset($_SESSION['facebook_id_ok'])){
                                                $_SESSION['twitter_id_ok']=true;
                                                echo"<script>
                                                    document.getElementById('twit_connection').style.display='none';
                                                     location.href = '../Home/Home.php';
                                                </script>";
                                                unset($_SESSION['twitter']);
                                            }else{
                                                $_SESSION['twitter_id_ok']=true;
                                                //script per eliminare dalla pagina gli elementi di twitter
                                                echo"<script>
                                                    document.getElementById('twit_connection').style.display='none';
                                                </script>";
                                                unset($_SESSION['twitter']);
                                            }
                                        }else{
                                            //altrimenti setto che non ho l'id di twitter TODO:instagram e rimando alla Home
                                            $_SESSION['facebook_id_ok']=false;
                                            $_SESSION['twitter_id_ok'] = true;
                                            db_close_conn($conn);
                                            unset($_SESSION['facebook']);
                                            echo"<script>
                                                document.getElementById('twit_connection').style.display='none';
                                                location.href = '../Home/Home.php';
                                            </script>";
                                        }
                                ?>
                                    </body>
                                </html>
                                <?php        
                                }
                        }
                        //TODO:aggiungi parte instagram
                    }
            }else{
                db_close_conn($conn);
                //rimando errore se non è stata spuntato nessun social di interesse
                header("Location:Login.php?errore=3");
            }
        }
    }
?>
