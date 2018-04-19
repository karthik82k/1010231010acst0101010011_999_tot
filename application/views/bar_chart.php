<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Total</title>
<link href="css/timesheet_admin.css" type="text/css" rel="stylesheet" media="screen" />
 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Sales', 'Expenses', 'Profit'],
          ['2014', 1000, 400, 200],
          ['2015', 1170, 460, 250],
          ['2016', 660, 1120, 300],
          ['2017', 1030, 540, 350]
        ]);

        var options = {
          chart: {
            title: 'Company Performance',
            subtitle: 'Sales, Expenses, and Profit: 2014-2017',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>
</head>
<body>
<div id="wrapper">
  <div><div class="site-name"><span style="font-size: 1.5em; color: red">t</span><u style="color: red">otal</u> <span style="font-size: 1.2em; color: #519CFF"><b>a</b></span><span style="color: #519CFF">ccounting</span></div></div>
   <?php include("navigation.php") ?>
   <form name="" id="" action="" method="post" >
    <div id="adcontent" style="height: 300px;">
    <div id="columnchart_material" style="width: 800px; height: 500px;"></div>
      </div>
     </form>
</div>
</body>
</html>
