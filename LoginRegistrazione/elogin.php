<?php
    require_once ('../settings.php');
    require_once ('../database_query/db_conn_query.php');
    require_once ("../vendor/autoload.php");
    require_once ("../functions.php");

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
    if($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_GET['code']))
    {
        //ho premuto il bottone accedi e non è settato il get di facebook
        if(isset($_POST['btnAccedi']) && empty($_GET['code']))
        {        
            $_SESSION['btnAccedi'] = $_POST['btnAccedi'];
            $autenticazione_cliente = "select pass from tb_clienti where email ='".$_SESSION['email']."'"; 
            
            db_select();
            
            $risultato_query = query($autenticazione_cliente);
                    
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
                $query = "select id_client from tb_clienti where email = '".$_SESSION['email']."'";
                $_SESSION['id_cliente'] = mysqli_fetch_assoc(query($query));
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
            if(isset($_POST['facebook'])){
                $_SESSION['facebook'] = $_POST['facebook'];
            }
            if(isset($_POST['twitter'])){
                $_SESSION['twitter'] = $_POST['twitter'];
            }
            if(isset($_POST['instagram'])){
                $_SESSION['instagram'] = $_POST['instagram'];
            }

            //prima verifico che sia stato spuntato almeno un social
            //rimando errore altrimenti
            if(
                isset($_SESSION['facebook']) || isset($_POST['facebook']) || 
                isset($_SESSION['twitter']) || isset($_POST['twitter']) ||
                isset($_SESSION['instagram']) || isset($_POST['instagram'])
            ){   
                $_SESSION['risultato'] = true;
                
                // ricerco che la mail non sia stata già utilizzata
                db_select();
                $query = sprintf("SELECT email
                                    FROM tb_clienti
                                    WHERE email = '%s'",
                                    $_SESSION['email']
                                );
                $risultato_utenti = mysqli_fetch_assoc(query($query));
                db_close_conn($conn);
                
                //se la mail è già stata utilizzata o la mail non esiste rimando errore
                if(($risultato_utenti !== null) || (!$_SESSION['risultato'])) {
                    header("Location:Login.php?errore=2");
                    exit();
                } else {
                    //tutti i controlli sono andati a buon fine
                    //devo richiedere l'accesso ai social che sono stati spuntati per poter inserire i dati nel db
                    ?>
                    <html>
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width = device-width, initial-scale = 1">
                            <link rel="stylesheet" href="../css/w3.css">
                            <link rel="stylesheet" href="../css/bootstrap.min.css">
                            <link rel="stylesheet" href="../css/mybtt.css">
                            <link rel="stylesheet" href="../css/mycss.css">
                            <script src="../bootstrap.min.js"></script>
                            <script src="../popper.min.js"></script>
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
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <!-- TODO:aagiungere instagram -->
                                    <?php if(isset($_SESSION['facebook']) || isset($_SESSION['twitter']) || isset($_SESSION['instagram'])){ ?>
                                        <br />
                                        <div>
                                            <h3 style='color:red;'>Effettua i login ai tuoi social:</h3>
                                        </div>
                                        <br />
                                    <?php }  ?>    
                                    <?php
                                        //step 1: Enter credentials
                                        //utilizzo la libreria dell'SDK
                                        $fb = new Facebook([
                                            'app_id' => $fb_app_id,
                                            'app_secret' => $fb_app_secret,
                                            'default_graph_version' => 'v3.2'
                                        ]);
                                    
                                        //pulsante rimanda al login facebook
                                    ?>
                                    <?php if(isset($_SESSION['facebook']) && !(isset($_SESSION['facebook_id_ok']) && $_SESSION['facebook_id_ok'])){ ?>
                                        <div id='connection'>
                                            <h3>Effettua il login su Facebook:</h3>
                                            <a href='<?= $fb->getRedirectLoginHelper()->getLoginUrl($app_url."LoginRegistrazione/elogin.php") ?>'>
                                                <img id='btn_image' src='../content/login_fb_button.png' width=200 class='hoverable'>
                                            </a>
                                        </div>
                                        <br />
                                    <?php } ?>
                                    <?php
                                        //ottengo il token di accesso
                                        $access_token = $fb->getRedirectLoginHelper()->getAccessToken();
                                        /*Step 4: Get the graph user*/
                                        if(isset($access_token) || isset($_SESSION['fb_token'])) {
                                            
                                            try {
                                                if(isset($access_token)){
                                                    //salvo il token di accesso nella variabile di sessione
                                                    $_SESSION['fb_token'] = $access_token->getValue();
                                                    //ho effettuato l'accesso
                                                    $response = $fb->get('/me', $access_token);
                                                    //get user graph
                                                    $fb_user = $response->getGraphUser();
                                                    //ottengo l'id di facebook
                                                    $_SESSION['facebook_id']=$user_id = $fb_user->getId();
                                                    $_SESSION['facebook_name'] = $response->getGraphUser()->getName();
                                                    
                                                }else{
                                                    //ho effettuato l'accesso
                                                    $response = $fb->get('/me', $_SESSION['fb_token']);
                                                }

                                                //estraggo i post anche quelli in cui sono taggato
                                                $posts_request= $fb->get('me/feed?fields=message, created_time, id, permalink_url,full_picture', $_SESSION['fb_token']);
                                                //ottengo il body della richiesta. ovvero il testo dei post
                                                $_SESSION['post'] = $posts_request->getBody();
                                                //true ritorna un'arrai invece di un oggetto
        
                                                $_SESSION['facebook_data']=$json=json_decode($_SESSION['post'], true);

                                                $_SESSION['facebook_id_ok']=true;
                                                redirect_if_completed();

                                                //rendo invisibile il pulsante
                                                if(isset($_SESSION['fb_token'])){ ?>
                                                    <script>
                                                        document.getElementById('connection').style.display='none';
                                                        document.getElementById('btn_image').style.display='none';
                                                    </script>
                                                <?php }
                                                
                                            } catch (\Facebook\Exceptions\FacebookResponseException $e) {
                                                echo  'Graph returned an error: ' . $e->getMessage();
                                            } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                                            // When validation fails or other local issues
                                                echo 'Facebook SD returned an error: ' . $e->getMessage();
                                            }
                                        }
                                //se è stata selezionata la checkbox di twitter
                                if(isset($_SESSION['twitter']) && !(isset($_SESSION['twitter_id_ok']) && $_SESSION['twitter_id_ok'])){ ?>
                                    <!-- pulsante rimanda al login di twitter -->
                                    <div id='twit_connection'>
                                        <h3>Inserisci il tuo username Twitter:</h3>
                                        <form class='form-inline' name = 'get_twit' action = 'elogin.php' method = 'POST'>
                                            <div class='form-group'>
                                                <input type='text' class='form-control' id='twitter_name' name='twitter_name' placeholder='Enter Twitter Name'>
                                            </div>
                                            <div>
                                                <button type='submit' name='btnTwit' class='btn btn-light w3-hover-yellow' style='border-style: solid; border-color: lightgrey; background-color: greenyellow; color: blue'>Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                    <br />
                                    <?php 
                                        /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
                                        $settings = array(
                                            'oauth_access_token' => $tw_oauth_access_token,
                                            'oauth_access_token_secret' => $tw_oauth_access_token_secret,
                                            'consumer_key' => $tw_consumer_key,
                                            'consumer_secret' => $tw_consumer_secret
                                        );

                                        $url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
                                        $requestMethod = "GET";
                                        
                                        if (isset($_POST['twitter_name'])) {
                                            $_SESSION['twitter_name']=$twitter_name = preg_replace("/[^A-Za-z0-9_]/", '', $_POST['twitter_name']);
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

                                            $_SESSION['twitter_id_ok'] = true; ?>
                                            <script>
                                                document.getElementById('twit_connection').style.display='none';
                                            </script>
                                            <?php redirect_if_completed();
                                        }
                                    }

                                    // se si è selezionato instagram
                                    if(isset($_SESSION['instagram'])){ ?>
                                        <?php if (isset($_POST['ig_name']) && isset($_POST['ig_password'])) {
                                            $_SESSION['ig_data'] = getInstagramPost($_POST['ig_name'],$_POST['ig_password']);
                                        } ?>

                                        <?php if(!isset($_SESSION['ig_data']) || empty($_SESSION['ig_data'])){ ?>
                                            <div id='inst_connection'>
                                                <h3>Inserisci le tue credenziali Instagram:</h3>
                                                <form class='form-inline' name = 'get_ig' action = 'elogin.php' method = 'POST'>
                                                    <div class='form-group'>
                                                        <input type='text' class='form-control' id='ig_name' name='ig_name' placeholder='Username'>
                                                        <input type='password' class='form-control' id='ig_password' name='ig_password' placeholder='Password'>
                                                    </div>
                                                    <div>
                                                        <button type='submit' name='btnTwit' class='btn btn-light w3-hover-yellow' style='border-style: solid; border-color: lightgrey; background-color: greenyellow; color: blue'>Submit</button>
                                                    </div>
                                                </form>
                                                <?php if(isset($_SESSION['ig_data']) && empty($_SESSION['ig_data'])){ ?>
                                                    <h4><font color="red">Credenziali non valide</font></h4>
                                                <?php } ?>
                                            </div>
                                            <br />
                                        <?php }else{
                                            $_SESSION['instagram_id_ok'] = true;
                                            redirect_if_completed();
                                        } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </body>
                    </html>
                <?php }
            }else{
                //rimando errore se non è stata spuntato nessun social di interesse
                header("Location:Login.php?errore=3");
            }
        }
    }
?>
