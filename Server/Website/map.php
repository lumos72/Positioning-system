<!doctype html>
  <head>
    <meta charset="UTF-8" />
    <style type="text/css">
     #container{
        height: 360px;
        width: 100%;
      }
      #title{
        width: 100%;
        height: 55px;
        margin-bottom: 12px;
        background-color: #f0f0f0;
        border-radius: 6px;
      }
      #title p{
        position: relative;
        top: 10px;
        margin-top: 10px;
        text-align: center;
        font-size: 23px;
      }

      #line{
        height: 1px;
        width: 100%;
        margin-top: 20px;
        margin-bottom: 15px;
        background-color: #4f4f4f;
        border-radius: 6px;
        position: relative;
        top: 120px;
      }
      #footer{
        font-size: 13px;
        text-align: center;
      }
      #cover p{
        float: left;
      }
      #cover button{
        width: 100px;
        height: 30px;
        position: relative;
        top: 13px;
        left: 10px;
        border-width: 0px;
        background-color: #bbffee;
        border-radius: 3px;
      }
      #cover button:hover{ background-color: #33ffff; }
      #fun p{
        float: left;
        position: relative;
        right: 65px;
        top: 5px;
      }
      #fun button{
        width: 100px;
        height: 30px;
        border-width: 0px;
        background-color: #bbffee;
        border-radius: 3px;
        position: relative;
        top : 17px;
        right: 55px;
      }
      #fun button:hover{ background-color: #33ffff; }
      #sg{
        position: relative;
        top :0px;
      }
      #sa{
        position: relative;
        top :0px;
      }
      #eg{
        position: relative;
        top :0px;
      }
      #ea{
        position: relative;
        top :0px;
      }
      #gh{
        float: left;
        position: relative;
        top: 7px;
        right: 130px;
      }
      #gh button{
        width: 100px;
        height: 30px;
        border-width: 0px;
        background-color: #bbffee;
        border-radius: 3px;
        position: relative;
        top: 15px;
      }
      #gh button:hover{ background-color: #33ffff; }
    </style>
    <title>MAP</title>
  </head>
  <body>
    <div id="title">
      <p>基于GPS & GPRS的定位系统</p>
    </div>

    <div id="container" tabindex="0"></div>
    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.4.6&key=042d208a3473f60e4cd397531dc9b8a3">
    </script>
    <script type="text/javascript">

        var map = new AMap.Map('container',{
            resizeEnable: true,
            zoom: 14,
            center: [108.983815,34.246502]
          });
        
        AMap.plugin(['AMap.ToolBar','AMap.Scale','AMap.OverView'],
          function(){
            map.addControl(new AMap.ToolBar());
            map.addControl(new AMap.Scale());
            map.addControl(new AMap.OverView({isOpen:true}));
          });

        function addPiont(longi, lati)
        {
          marker = new AMap.Marker({
            position: [longi, lati]
          });
          map.add(marker);
        }

        function delPoint()
        {
          map.remove(marker);
        }

    </script>

    <p id="data">数据状态</p>

    <?php
    
      $link = mysql_connect('123.207.242.95','root', 'fucknopwd0')
          or die ("Cannot connent to the database,please try later.".mysql_error());
      $db = mysql_select_db("gps",$link);
      echo "SQL OK... ";
      $result = mysql_query("select * from GpsData",$link);
      echo "DATA OK... ";
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
        
      echo "Ready!";
      echo "
        <script type=\"text/javascript\">
          lng = $longi;
          lat = $lati;
        </script>
      ";
    ?>

    <script type="text/javascript">
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
        //map.remove(marker);
    </script>
    
    <script type="text/javascript">
      var flag = 0;

      function line(){
        var lineArr = [start, end];
        var polyline = new AMap.Polyline({
          path: lineArr,
          strokeColor: "#3366FF",
          strokeWeight: 5,
          strokeStyle: "solid",
        });
        map.add(polyline);
      }

      function success(text) {
        end = [lng, lat];
        if(flag == 1 && j() == 1) line();
        start = end; flag = 1;
        eval(text);
      }

      function j() 
      {
        var dis_lng = Math.abs(end[0] - start[0]);
        var dis_lat = Math.abs(end[1] - start[1]);
        if(dis_lat < 0.005 && dis_lng < 0.001) return 1;
        else return 0;
      }

      function fail(text) {
        alert(text);
      }
      
      var request;

      if (window.XMLHttpRequest) {
        request = new XMLHttpRequest();
      } 
      else {
        request = new ActiveXObject('Microsoft.XMLHTTP');
      }

      request.onreadystatechange = function () {
        if (request.readyState === 4) {
          if (request.status === 200) {
            return success(request.responseText);
          }
          else {
            return fail(request.status);
          }
        }
      }

      function next(){
        request.open('GET', '/ajax.php');
        request.send();
      }
      
      setInterval(next, 4000);

    </script>

    <script type="text/javascript">
    flag_s = 0;
    flag_r = 0;
    flag_t = 0;

    flag_sc = 0;
    flag_ex = 0;

    function scope() {
      if(flag_sc == 0) {
        circleMarker = new AMap.CircleMarker({
        center: new AMap.LngLat(108.984815,34.247502),
        radius: 150,
        fillColor: '#ff0000',
        fillOpacity: 0.4,
        strokeColor: '#ff0000',
        strokeOpacity: 1,
        strokeWeight: 2,
        });
        map.add(circleMarker);
        flag_sc = 1;
      }
      else { map.remove(circleMarker); flag_sc = 0; }
    }

    function exception() {
      alert("没有发现异常点");
    }

    function SatelliteLayer() {
      if(flag_s == 0) {
        satellite = new AMap.TileLayer.Satellite();
        map.add(satellite);
        flag_s = 1;
      }
      else { map.remove(satellite); flag_s = 0; }
    }

    function RoadNetLayer() {
      if(flag_r == 0) {
        roadNet = new AMap.TileLayer.RoadNet({zIndex:10}); //实例化路网图层
        roadNet.setMap(map);
        //roadNet = new Amap.TileLayer.RoadNet();
        //roadNet.setMap(map);
        //map.add(roadNet);
        flag_r = 1;
      }
      else { map.remove(roadNet); flag_r = 0;}
    }

    function TrafficLayer() {
      if(flag_t == 0) {
        traffic = new AMap.TileLayer.Traffic();
        map.add(traffic);
        flag_t = 1;
      }
      else { map.remove(traffic); flag_t = 0;}
    }
    </script>

    <div id="cover">
      <p>切换图层</p>
      <button type="button" onclick="SatelliteLayer()" id="sa">卫星图层</button>
      <button type="button" onclick="RoadNetLayer()" id="ro">路网图层</button>
      <button type="button" onclick="TrafficLayer()" id="tr">实时交通图层</button>
    </div>

    <div id="fun">
      <p>功能选择</p>
      <button type="button" onclick="scope()">活动范围</button>
      <button type="button" onclick="exception()" id="exc">异常检测</button>
    </div>

    <script type="text/javascript">
    flag_gh = 0
    function gh() {
      start = [document.form.start_lon.value, document.form.start_lat.value;];
      end = [document.form.end_lon.value, document.form.end_lat.value];
      AMap.plugin('AMap.Driving', function() {
          driving = new AMap.Driving({
            policy: AMap.DrivingPolicy.LEAST_TIME
          })
          driving.search(startLngLat, endLngLat, function (status, result) {
            if(status == 'complete') {
              alert("miao");
            }
          })
      })
    }
    </script>

    <div id="gh">
      <p>路径规划</p>
      <form name="form">
        <input type="text" name="start_lon"  id="sg" />
        <input type="text" name="start_lat" id="sa" />
        <input type="text" name="end_lon" id="eg" />
        <input type="text" name="end_lat" id="ea" />
      </form>
      <button type="button" onclick="gh()">路径规划</button>
    </div>

    <div id="line">
    </div>
    <div id="footer">
      <p>Positioning system based on GPS & GPRS </p>
      <p>For electronic system design course </p>
      <p>Designed by : Ma Yanqing & Tang Lina</p>
    </div>
  </body>
</html>