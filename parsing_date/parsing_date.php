        <?php
        function parsing_facebook($string){
                //spezzo la data in più parti
                $array = explode('T', $string);
                //$array1 = explode('+', $array[1]);
                $date = explode('-', $array[0]);
                $time = explode(':', $array[1]);
                $separation = explode('+', $time[2]);
        
                //setto ciascun valore dalla separazione della stringa
                $ore = $time[0];
                $minuti = $time[1];
                $secondi = $separation[0];
                $giorno = $date[2];
                $mese = $date[1];
                $anno = $date[0];

                //genero il timestamp in questo modo avrò un facile controllo delle date
                $timestamp=mktime($ore, $minuti, $secondi, $mese, $giorno, $anno);
                
                //ritorno la data nel formato salvato nel db
                $date = date("Y-m-d H:i:s", $timestamp);
                return $date;
        }
        function parsing_twitter($string){
            $month  = array('Jan'=>'01', 'Feb'=>'02', 'Mar'=>'03', 'Apr'=>'04', 'May'=>'05', 'Jun'=>'06', 'Jul'=>'07', 'Aug'=>'08', 'Sep'=>'09', 'Oct'=>'10', 'Nov'=>'11', 'Dec'=>'12');
                $array=explode(' ', $string);
                $time = explode(':', $array[3]);
                $mese = $month[$array[1]];
                $giorno = $array[2];
                $anno = $array[5];
                $ore = $time[0];
                $minuti = $time[1];
                $secondi = $time[2]; 
  
                //genero il timestamp in questo modo avrò un facile controllo delle date
                $timestamp=mktime($ore, $minuti, $secondi, $mese, $giorno, $anno);
                $date = date("Y-m-d H:i:s", $timestamp);
                return $date;
        }
        ?>
