<?php
	$link = mysql_connect('123.207.242.95','root', 'fucknopwd0')
		or die ("Cannot connent to the database,please try later.".mysql_error());
    $db = mysql_select_db("gps",$link);
    //echo "SQL OK ";
    $result = mysql_query("select * from GpsData",$link);
    //echo "DATA OK ";
    $num = mysql_num_rows($result);
    if($num > 1)
    {
      while($num > 1)
      {
        $data = mysql_fetch_array($result);
        $num = $num - 1;
      }
    }
 
    $data = mysql_fetch_array($result);
    $longi = $data[7];
    $lati = $data[8];
    $longi = floor($longi) + ($longi - floor($longi)) / 0.6;
    $lati = floor($lati) + ($lati - floor($lati)) / 0.6;

    echo "map.remove(marker);";
    echo "
    	
          lng = $longi;
          lat = $lati;
        AMap.convertFrom([lng, lat], 'gps', function(status, result) {
          //alert('status=' + status + ', result=' + result);
          if(status == 'complete')
          {
            lng = result.locations[0].getLng();
            lat = result.locations[0].getLat();
            marker = new AMap.Marker({
              position: [lng, lat]
            });
            map.add(marker);
          }
        });
    	";
?>
