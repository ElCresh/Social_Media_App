<?php
$conn;

//selezione del db e connesisone
function db_select(){
    //DB Configs
    $host = "localhost";
    $user = "root";
    $pasw = "";
    $dbnm = "SocialMediaApp";
    
    global $conn;
    $conn = mysqli_connect($host,$user,$pasw,$dbnm);

    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error()."<br>";
        die;
    }else{
        mysqli_select_db($conn, $dbnm);
    }
}

//chiusura connessione al db
function db_close_conn(){
    global $conn;
    mysqli_close($conn);
}

//query
function query($query){
    global $conn;
    $risultato = mysqli_query($conn, $query);
    return $risultato;
}

//inserimento dati cliente nel db
function insert_daticliente_db($data){
    global $conn;
    //la password inserita nella registrazione la modifico con la funzione hash
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    //se twitter non è stato scelto eseguo query facebook
    if(!$data['twitter_id']){
        $query = "insert into tb_clienti (id_client, email, pass, facebook_id, twitter_id, facebook_name, twitter_name) values(null,'".$data['email']."', '".$data['password']."', '".$data['facebook_id']."', null,'".$data['facebook_name'].",null)";
    //se facebook non è stato scelto eseguo query twitter        
    }elseif(!$data['facebook_id']){
        $query = "insert into tb_clienti (id_client, email, pass, facebook_id, twitter_id, facebook_name, twitter_name) values(null,'".$data['email']."', '".$data['password']."', null, '".$data['twitter_id']."', null, '".$data['twitter_name']."')";
    //altrimenti sono stati settati entrambe    
    }else{
        $query = "insert into tb_clienti (id_client, email, pass, facebook_id, twitter_id, facebook_name, twitter_name) values(null,'".$data['email']."', '".$data['password']."', '".$data['facebook_id']."', '".$data['twitter_id']."', '".$data['facebook_name']."', '".$data['twitter_name']."')";
    }
    mysqli_query($conn, $query);
}

//inserimento dati post
function insert_post_data($data){
    global $conn;
    $query = "insert into tb_post (id, date_time, body, social, user_id) values('".$data['id_post']."','".$data['timestamp']."', '".$data['body']."', ".$data['id_social'].", ".$data['userID'].")";
    mysqli_query($conn, $query);
}

//ottenere tutti i post
function getAllPost($userid){
    global $conn;
    $query = "select body, date_time, social from tb_post where user_id =".$userid." group by(date_time) desc;";
    $risultato = mysqli_query($conn, $query);
    return $risultato;
}

//ottenere i post facebook
function getFbPost($userid){
    global $conn;
    $query = "select body, date_time, social from tb_post where user_id =".$userid." and social = 1 group by(date_time) desc;";
    $risultato = mysqli_query($conn, $query);
    return $risultato;
}

//ottenere i post twitter
function getTwPost($userid){
    global $conn;
    $query = "select body, date_time, social from tb_post where user_id =".$userid." and social = 2 group by(date_time) desc;";
    $risultato = mysqli_query($conn, $query);
    return $risultato;
}
//ottengo i dati dell'utente(id, nome facebook e username twitter)
function getUserData($email){
    global $conn;
    $query = "select id_client, facebook_name, twitter_name from tb_clienti where email ='".$email."'";
    $risultato = mysqli_fetch_assoc(mysqli_query($conn, $query));
    return $risultato;
}