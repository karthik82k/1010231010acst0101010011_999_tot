<?php $this->load->view('include/header'); 

  $role_id =  $this->session->userdata('user_role');
  $user_id =  $this->session->userdata('user_id');
  $name = $this->session->userdata('name');
  $comp_name = $this->session->userdata('company_name');  
  $financial_year_selected = $this->session->userdata('financial_year');
  $financial_year_id = $this->session->userdata('financial_id');
  $financial_year_from = $this->session->userdata('financial_from');
  $financial_year_to = $this->session->userdata('financial_to');
  $company_logo = $this->session->userdata('logo');

$json = "[[";
$json .= "'PROFIT','LOSS'";
$json .= "],";

foreach ($profit_n_loss as $val) {
    $json .= "['INCOME'" . "," . $val['INCOME'] . "],";
    $json .= "['EXPENSE'" . "," . $val['EXPENSE'] ."],";  
}

$json = trim($json, ',');
$json .= "]"; 

$json_tax = "[[";
$json_tax .= "'INPUT GST','OUTPUT GST'";
$json_tax .= "],";

foreach ($tax as $key) {      
    $json_tax .= "['OUTPUT TAX'" . "," . $key['OutPutTax'] . "],";
    $json_tax .= "['INTPUT TAX'" . "," . $key['InPutTax'] ."],";
}

$json_tax = trim($json_tax, ',');
$json_tax .= "]"; 

$json_sales = "[[";
$json_sales .= "'MONTH','SALES'";
$json_sales .= "],";

foreach ($sales as $value) {      
    $json_sales .= "['" .$value['Month']. "'," . $value['SalesAmount'] . "],";
}

$json_sales = trim($json_sales, ',');
$json_sales .= "]"; 

echo $json_sales;

?>


                  
<div class="empty-spacer">
<div class="container"></div>
</div>
<!-- ddf white space code ends here -->  
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable(<?php echo $json;?>);

        var options = {  title: 'INCOME & EXPENSE',
          colors: ['#2a4e59', '#2a98b9'],
          is3D: true,
          titleTextStyle: {
        fontName: 'Calibri', 
       fontSize: 14,
        bold: true
    }
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));

        chart.draw(data, options);
      }


      // google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart_donut);

      function drawChart_donut() {

        var data = google.visualization.arrayToDataTable(<?php echo $json_tax;?>);

        var options = {  title: 'OUTPUT TAX & INPUT TAX',
          colors: ['#5a99d3', '#eb7c30'],
          pieHole: 0.4,          
          titleTextStyle: {
        fontName: 'Calibri', 
       fontSize: 14,
        bold: true
    }
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart_gst'));

        chart.draw(data, options);
      }


     // google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart_line);

      function drawChart_line() {
        var data = google.visualization.arrayToDataTable(<?php echo $json_sales;?>);

        var options = {
          title: 'SALES',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
     
      
    </script>  
<style type="text/css">
#profilestatus {font-size:14px;font-weight:bold;}   
.textborder {border: 1px solid #555555;}
.form-control {width:90% !important;}
.ui-multiselect{width:50% !important;margin-bottom: 1%;}
label {
    margin-left: 2%;
    margin-top: 1%;
    }
    .well {
        padding: 5px;
    }
</style>

<div class="container category">        
  <div class="row">
   
    <div id="profilestatus" style="display:none;">&nbsp;</div>    
    <div class="col-md-12">
    <?php
        if($role_id != 1) {
    ?>

        <div class="col-md-12">
        <div class="col-md-4"  > <img  style="width:100%; text-align: center; "  responsive src="<?php echo base_url('/assets/image/total.png');?>">
        </div>
        <div class="col-md-8" style="text-align:left; padding:1px 1px 1px 1px;">
        <div class="col-md-12" style="height: 95px;">
        <div class="col-md-8" style="font-size: 25px; color: #893d12; font-weight: bold;"><?php echo $comp_name;?></div>
        <div class="col-md-3" style="">
            <?php
            if($company_logo != '' ){
            ?>
              <img src="<?php echo base_url('/assets/photo/'.$company_logo);?>" alt="Logo"  style="height:100px;width:160px;"/>
            <?php
            }
              ?>

        </div>

        </div>
         <div class="col-md-12 text-center"><br>
 <span style=" font-size: 18px; color: #00008b; font-weight: bold; "><b>BUSINESS DETAILS</b></span>
 </div>
        <div  class="col-md-6" style="border-radius: 1px 1px 1px 1px; border: solid 1px #e5e2e2; padding:1px 1px 1px 1px; width: 47%; margin-left: 18px; margin-right: 3px; font-weight: bold;">
       
            <div class="col-md-4"><b>GST</b></div>
             <div class="col-md-6 text-left"><?php 
            if($company_list[0]['VATCODE'] == ''){ echo '--'; } else { echo $company_list[0]['VATCODE']; }?></div>
        <div class="col-md-4"><b>PAN</b></div>
            <div class="col-md-6 text-left"><?php 
            if($company_list[0]['PANNO'] == ''){ echo '--'; } else { echo $company_list[0]['PANNO']; }?></div>
            
            <div class="col-md-4"><b>TAN</b></div>
            <div class="col-md-6 text-left"><?php 
            if($company_list[0]['TAN'] == ''){ echo '--'; } else { echo $company_list[0]['TAN']; }?></div>

        </div>
        <div  class="col-md-6" style="border-radius: 1px 1px 1px 1px; border: solid 1px #e5e2e2; padding:1px 1px 1px 1px; width: 49%; font-weight: bold;">
        <div class="col-md-4"><b>COUNTRY</b></div>
            <div class="col-md-6 text-left" ><?php echo $company_list[0]['COUNTRY'];?></div>
            <div class="col-md-4"><b>STATE</b></div>
            <div class="col-md-6 text-left" ><?php echo $company_list[0]['STATE'];?></div>
          <div class="col-md-4"><b>CONSTITUTION</b></div>
            <div class="col-md-6 text-left" >
            <?php 
              if($company_list[0]['COMPANYSUBTYPE'] == ''){ echo '--'; } else { echo $company_list[0]['COMPANYSUBTYPE']; } 
            ?>
            </div>
            
            
        </div>
        </div>
        
        <div class="col-md-12">
        <div class="col-md-4"  style="text-align:center; border-radius: 1px 1px 1px 1px; border: solid 1px #e5e2e2; padding:1px 1px 1px 1px; width: 34.5%; margin-right: 1px; "> <div id="curve_chart" style="width: 380px; height: 206px;"></div>
        </div>
        <div class="col-md-4"  style="text-align:center; border-radius: 1px 1px 1px 1px; border: solid 1px #e5e2e2; padding:1px 1px 1px 1px; width: 33%">  <div id="donutchart" style="width: 370px; height: 206px;"></div>
        </div>
        <div class="col-md-4"  style="text-align:center; border-radius: 1px 1px 1px 1px; border: solid 1px #e5e2e2; padding:1px 1px 1px 1px; width: 32%; margin-left: 2px;">  <div id="donutchart_gst" style="width: 360px; height: 206px;"></div>
        </div>

    <?php
       }else{
        ?>
        <img style="width:80%; text-align: center; "  responsive src="<?php echo base_url('/assets/image/admin_page.png');?>"
        <?php
       }
    ?>
    </div>    
  </div>
</div>
  <div style="height:25px;"></div>                
                    <!--  footer code starts here -->
<div class="panel-footer footer navbar-fixed-bottom">  
  <div class="container" style="width: 100%; float: left;">   
    <div class="text-right" style="width: 50%; float: left;"> 
      Copyrights &copy; 2017 Total Accounting 
    </div><div  class="text-right" style="width: 50%; float: right;"> 
      Powered by Salvo Systems Pvt Ltd 
    </div>
  </div>
</div>  

</body>
</html>