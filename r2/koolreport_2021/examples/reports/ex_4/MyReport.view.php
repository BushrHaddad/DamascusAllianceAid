<?php 
    use \koolreport\widgets\koolphp\Table;
    use \koolreport\widgets\google\ColumnChart;
    use \koolreport\widgets\koolphp\Card;
?>

<div class="report-content">
    <div class="text-center">
        <h1>Team 1</h1>
        <p class="lead">2021</p>
    </div>
    <?php
    //  $sql2=" select * from  master_view m where m.family_id =9280  COLLATE utf8_bin ";
      $sql2=$_GET['query'];//" select * from  master_view m where m.family_id =9280  ";     
    //$sql2=" select * from  master_view m where m.family_id =9280  limit 20";
    $config = include "./../../config.php";
    $conn=$config['churchcrm']['con'];
    //remove the limit from query
    //this works
    //mysqli_query($conn,"set names utf8");
    //and this also works:
    mysqli_query($conn,"SET CHARACTER SET 'utf8';");
    $result2=mysqli_query($conn,$sql2);
    $i=0;
    /**/
    ?>
    <?php
   /* ColumnChart::create(array(
        "dataStore"=>$this->dataStore('sale_by_month'),  
        "columns"=>array(
            "payment_date"=>array(
                "label"=>"Month",
                "type"=>"datetime",
                "format"=>"Y-n",
                "displayFormat"=>"F, Y",
            ),
            "amount"=>array(
                "label"=>"Amount",
                "type"=>"number",
                "prefix"=>"$",
            )
        ),
        "width"=>"100%",
    ));*/
    ?>
    <?php
    $team_name=7777;
    //for ($i=0;$i<=3;$i++)
    Table::create(array(
        "dataStore"=>$this->dataStore('churchcrm'),
        "columns"=>array(
            "family_name"=>array(
                "label"=>"Name",
                "type"=>"string",
                //"format"=>"Y-n",
                //"displayFormat"=>"center",
            ),
            "fam_add"=>array(
                "label"=>"Address",
                "type"=>"string",
                //"prefix"=>"$",
                        )
        ),
        "cssClass"=>array(
            "table"=>"table table-hover table-bordered"
        ),
        )
    );
    ?>
    <?php
    while ($row2=mysqli_fetch_array($result2))
    {
        $team_name   = $row2['team_name'];
        $family_name = $row2['family_name'];
        $family_add  = $row2['fam_add'];
        $i++;

        Table::create(    
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
            )  
    )
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