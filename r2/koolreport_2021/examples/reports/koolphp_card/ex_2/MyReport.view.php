<?php
    use \koolreport\widgets\koolphp\Card;
?>
<div class="report-content">
    <div class="text-center">
        <h1>Master View</h1>
        <p class="lead">
          Master View Details
        </p>
    </div>
    <?php
    $sql2=$_GET['query'];//"select * from reseller r where r.IsEnable !=0 ";
    //$sql2=" select * from  master_view m where m.family_id =9280  ";
     $config = include "../../../config.php";
  //  print_r($config); 
//echo '<br /><br />'.
 $conn=$config['churchcrm']['con'];
    //exit;
    //$con=$config['churchcrm'][conn];
    $result2=mysqli_query($conn,$sql2);
/*    mysqli_query($config['churchcrm']['con'],"set character_set_server='utf8'");
mysqli_query($config['churchcrm']['con'],"set names utf8");

mysqli_query($config['churchcrm']['con'],"set character_set_database=utf8");//,$link);
mysqli_query($config['churchcrm']['con'],"set session character_set_server=utf8");
mysqli_query($config['churchcrm']['con'],"set character_set_database=utf8");//,$link);
mysqli_query($config['churchcrm']['con'],"set session character_set_server=utf8");
mysqli_query($config['churchcrm']['con'],"set global character_set_server=utf8");
//mysql_query("set names utf8",$link);
mysqli_query($config['churchcrm']['con'],"SET CHARACTER SET 'utf8';");*/

$i=0;
    while ($row2=mysqli_fetch_array($result2))
        {

         //print_r($row2);
         $team_name   = $row2['team_name'];
         $family_name = $row2['family_name'];
         $family_add  = $row2['fam_add'];
         $i++;
    ?>
    <div class="row">
        <div class="col-md-3">
            <?php
            Card::create(array(
                "title"=>$team_name,
                //"title"=>"Team Name",
                "cssClass"=>array(
                    "card"=>"bg-info",
                    "title"=>"text-white",
                    "value"=>"text-white"
                )
            ));
            ?>
        </div>
        <div class="col-md-3">
            <?php
            Card::create(array(
                "text"=>$family_name,
                "title"=>"Family Name",
                "cssClass"=>array(
                    "card"=>"bg-warning",
                    "title"=>"text-white",
                    "value"=>"text-white"
                )
            ));
            ?>
        </div>
        <div class="col-md-3">
            <?php
            Card::create(array(
                "text"=>$family_add,
                "title"=>"Family Address",
                "format"=>array(
                    "value"=>array(
                        "prefix"=>"$"
                    )
                ),
                "cssClass"=>array(
                    "card"=>"bg-success",
                    "title"=>"text-white",
                    "value"=>"text-white"
                )
            ));
            ?>
        </div>

        <div class="col-md-3">
            <?php
            Card::create(array(
                "value"=>6912,
                "title"=>"Cost",
                "format"=>array(
                    "value"=>array(
                        "prefix"=>"$"
                    )
                ),
                "cssClass"=>array(
                    "card"=>"bg-danger",
                    "title"=>"text-white",
                    "value"=>"text-white"
                )
            ));
            ?>
        </div>
        <?php 
        }
    ?>
    </div>

    <div class="text-center" style="margin-top:30px;">
        <h3>Showing indicator</h3>
        <p class="lead">
            Card is able to show the percentage increased or decreased
        </p>
    </div>

    <div class="row">
        <div class="col-md-3 offset-md-3">
        <?php
        Card::create(array(
            "value"=>11249,
            "baseValue"=>9230,
            "format"=>array(
                "value"=>array(
                    "prefix"=>"$"
                )
            ),
            "title"=>"Month Sale",
        ));
        ?>    
        </div>
        <div class="col-md-3">
        <?php
        Card::create(array(
            "value"=>13,
            "baseValue"=>15,
            "format"=>array(
                "value"=>array(
                    "suffix"=>"k"
                )
            ),
            "title"=>"Visitors",
        ));
        ?>
        </div>        
    </div>

    <div class="text-center" style="margin-top:30px;">
        <h3>Get value with SQL Query</h3>
        <p class="lead">
            The value of card is able to receive value from SQL
        </p>
    </div>

    <div class="row">
        <div class="col-md-4 offset-md-4">
        <?php
        Card::create(array(
            "value"=>$this->src("churchcrm")->query("select m.master_id,m.family_name from master_view m where m.family_id=9280"),
            "format"=>array(
                "value"=>array(
                    "prefix"=>"$"
                )
            ),
            "title"=>"Total Sale",
            "cssClass"=>array(
                "card"=>"bg-primary",
                "title"=>"text-white",
                "value"=>"text-white"
            )
        ));
        ?>    
        </div>
    </div>

</div>
