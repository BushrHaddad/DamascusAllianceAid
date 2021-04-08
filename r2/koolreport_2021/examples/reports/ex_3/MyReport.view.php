<?php
    use \koolreport\widgets\koolphp\Table;
    use \koolreport\widgets\koolphp\Card;
?>
<div class="container report-content">
    <div class="text-center">
        <h1>Team ....</h1>
        <p class="lead">2021</p>
    </div>
    <?php
    //  $sql2=" select * from  master_view m where m.family_id =9280  COLLATE utf8_bin ";
    //  $sql2=$_GET['query'];//" select * from  master_view m where m.family_id =9280  ";     
    $sql2=" select * from  master_view m where m.family_id =9280  limit 2";
    
    $config = include "./../../config.php";
  
    $conn=$config['churchcrm']['con'];
    
    //remove the limit from query      
    //this works:   //mysqli_query($conn,"set names utf8");
    //and this also works:
    mysqli_query($conn,"SET CHARACTER SET 'utf8';");
    $result2=mysqli_query($conn,$sql2);
    $i=0;
    

Table::create(array(
        "dataSource"=>$this->dataStore('churchcrm'),
        "columns"=>array(
            "name",
            "age",
            "income"=>array(
                "formatValue"=>function($value,$row)
                {
                    $color = $value>70000?"#718c00":"#e83e8c";
                    return "<span style='color:$color'>$".number_format($value)."</span>";
                }    
            )
            ),
        "cssClass"=>array(
            "table"=>"table-bordered table-striped table-hover"
        )
    ));

    while ($row2=mysqli_fetch_array($result2))
    {
         $team_name   = $row2['team_name'];
         $family_name = $row2['family_name'];
         $family_add  = $row2['fam_add'];
         $i++;
    ?>
    <div class="row">
        <div class="col-md-3">
            <?php
            Card::create(array(
                "value"=>$team_name,
                "title"=>'Team Name',
                "htmlType"=>array(
                    "type"=>"textarea"
                    ),
                "cssClass"=>array(
                    //"card"=>"bg-info",            
                    "title"=>"text-white",
                    "value"=>"text-white"
                    )
                )
            );
            ?>
        </div>
        <div class="col-md-3">
            <?php
            Card::create(array(
                "value"=>$family_name,
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
                "value"=>$family_add,
                "title"=>"Family Address",
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
                "cssClass"=>array(
                    "card"=>"bg-danger",
                    "title"=>"text-white",
                    "value"=>"text-white"
                )
            ));
            ?>
        </div>  
    </div>
    <?php
        }
    ?>
</div>
<!-- <div class="report-content"> -->
    
    <?php
    /*Table::create(array(
        "dataSource"=>$this->dataStore('data'),
        "columns"=>array(
            "name",
            "age",
            "income"=>array(
                "formatValue"=>function($value,$row)
                {
                    $color = $value>70000?"#718c00":"#e83e8c";
                    return "<span style='color:$color'>$".number_format($value)."</span>";
                }    
            )
            ),
        "cssClass"=>array(
            "table"=>"table-bordered table-striped table-hover"
        )
    ));*/
    ?>
<!-- </div> -->