<?php

 use \koolreport\widgets\koolphp\Table;
    use \koolreport\widgets\google\ColumnChart;
    use \koolreport\widgets\koolphp\Card;
?>
<style type="text/css">
body {
    padding-top: 1rem;
}

.col-md-1,
.col-md-2,
.col-md-3 {
    text-align: center;
}

.card-body {
    -ms-flex: 0 0 auto;
    flex: 0 0 auto !important;
    padding: 0rem;
}

.koolphp-card .card-value {
    font-size: 13px;
    text-align: center;
    /*font-weight: 1000;*/
    padding-bottom: 5px;
}

.koolphp-card {
    /*border: solid 1px #ddd;*/
    padding-bottom: 0.5em;
    padding-top: 0.5em;
}

.koolphp-card .card-title {
    font-weight: 700;
    font-size: 13px;
    text-align: center;
    color: #aaa;
    /*font-weight: 1000; */
}

@media print {
    @page {
        size: landscape
    }
}

.cls-0 {
    width: 4em;
    text-align: center !important;
}

.cls-0-min {
    width: 3em;
    text-align: center !important;
}

.cls-1 {
    width: 7em;
    text-align: center !important;
    font-size: 11px;
    font-weight: 1000;
}

.cls-2 {
    width: 6em;
    text-align: center !important;
    font-size: 14px;
    font-weight: 1000;
}

.cls-3 {
    width: 9em;
    text-align: right !important;
    font-weight: 1000;
}

.cls-4 {
    width: 21em;
    text-align: right !important;
    font-size: 7px;
    font-weight: 1000;
}

.col-md-1,
.col-md-2,
.col-md-3 {
    text-align: right !important;
}


.cls-0-h {
    width: 4em;
    text-align: center !important;
}

.cls-0-min-h {
    width: 3em;
    text-align: center !important;
}

.cls-1-h {
    width: 5em;
    text-align: center !important
}

.cls-2-h {
    width: 6em;
    text-align: center !important;
}

.cls-3-h {
    width: 9em;
    text-align: center !important;
}

.hr_min {
    margin-top: 0rem;
    margin-bottom: 0rem;
}

/*.container{max-width: 1140px;}*/
/*.container{padding-right: none;padding-left: none;margin-right: none;margin-left: none;}*/
</style>

<!-- <style type="text/css" media="print">
    @page {    size: landscape;}
    body{ writing-mode:tb-rl;}
</style> -->
<div class="container" dir="rtl" style="width:100%;margin: 0 auto !important; padding: 0 !important;">

    <?php
    // Set the connection
    $config = include "./../../config.php";
    $conn=$config['churchcrm']['con']; // connection parameters
    mysqli_query($conn,"SET CHARACTER SET 'utf8';");
    
    $month_name = $_GET['month'];
    $year_name = $_GET['year'];
    $team_name = $_GET['team'];


    $q = "SELECT family_id, cash_name FROM master_general_view 
                                        where   month_name = '$month_name' AND 
                                                year_name = '$year_name' AND
                                                team_name = '$team_name' ;";
    $result=mysqli_query($conn,$q);


    $cols_titles1 = array("Team Name", "Team Address");
    $cols_titles = array("تسلسل",
    "الاسم الثلاثي<br />التابع",
    "الرقم الوطني",
    "عدد الأفراد",
    "أرقام الهواتف",
    "العنوان",
    "التقييم",
    "اسم وميلاد<br />الأولاد",
    "ملاحظات عامة",
    "ملاحظات الزيارة"      
    );
    $cols_titles_headers=array("cls-0-h","cls-3-h", "cls-3-h","cls-0-min-h", "cls-2-h"   , "cls-1-h" , "cls-0-min-h"     , "cls-2-h"     ,"cls-3-h" ,"cls-3-h");
    $cols_titles_classes=array("cls-0"  ,"cls-3"  ,  "cls-3" ,"cls-0-min"  , "cls-2"     , "cls-1"   , "cls-1" ,     "cls-2" ,         "cls-4"   ,"cls-4");


    $i=0;
    ?>
    <?php
    if(1!=1)
      Table::create(array(
        "dataStore"=>$this->dataStore('churchcrm2'),
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
        ))
    );

    while ($row=mysqli_fetch_array($result))
    {
        $family_id  =   $row['family_id']; // team name
        $cash_name  =   $row['cash_name']; // year name

        $fam_detail_q = "SELECT main_name, partner_name, main_id, partner_id,
                            members_num, children, home_phone, aid_phone, mobile_phone,
                            address1, address2, city, state, poverty_rate, ref, general_note, team_note FROM families_view
                            WHERE id = $family_id;";
        $fam_result = mysqli_query($conn,$fam_detail_q);
        $fam_row=mysqli_fetch_array($fam_result);
        $fam_name = $fam_row['main_name'];
        $fam_partner_name =  $fam_row['partner_name'];
        $fam_nat_id = $fam_row['main_id'];
        $fam_partner_nat_id = $fam_row['partner_id'];
        $fam_home_phone = $fam_row['home_phone'];
        $fam_aid_phone = $fam_row['aid_phone'];
        $fam_mobile_phone = $fam_row['mobile_phone'];
        $fam_address = $fam_row['address2']."-".$fam_row['address1']."-".$fam_row['city']."-".$fam_row['state'];
        $fam_eval =$fam_row['poverty_rate'];
        $fam_ref = $fam_row['ref'];
        $fam_children =  $fam_row['children'];
        $fam_count = $fam_row['members_num'];
        $fam_general_note = $fam_row['general_note'];
        $fam_team_note = $fam_row['team_note'];

        ?>
    <?php if ($i%3==0){ ?>
    <?php if ($i!=0){?>
    <br /><br /><br /><br /><br /><br /><br />
    <!-- <br /><br /><br /> -->
    <?php } ?>
    <!-- <br /><br /> -->
    <div class="text-center">
        <p class="lead">
            <?php  echo "اسم الفريق: ". $team_name. " | ". $month_name. "-". $year_name?>
        </p>
    </div>
    <div class="row">
        <?php for ($tt=0;$tt<count($cols_titles);$tt++){ ?>
        <div class=<?php echo $cols_titles_headers[$tt] ?>><?php echo  $cols_titles[$tt];    ?> </div>
        <?php } ?>
    </div>

    <hr style="height:4px;border-width:0;color:gray;background-color:gray">

    <?php 
        }
            $i++;
            $count1=0;
        ?>
    <div class="row">
        <!-- Family ID -->
        <div style="text-align: center;" class="<?php echo $cols_titles_classes[$count1] ?>">
            <?php
                Card::create(array(
                    "value"=>$family_id,
                    "title"=>'Family Id',
                    "htmlType"=>array(
                        "type"=>"textarea"
                    ),
                ));
                 Card::create(array(
                    "title"=>'مالية',
                    "htmlType"=>array(
                        "type"=>"checkbox"
                        ),
                    )
                );
                 Card::create(array(
                    "title"=>'بدون.م',
                    "htmlType"=>array(
                        "type"=>"checkbox"
                        ),
                    )
                );
                $count1++;
                ?>
        </div>
        <!-- Names -->
        <div class="<?php echo $cols_titles_classes[$count1] ?>">
            <?php
                Card::create(array(
                    "value"=>$fam_name.'<br />'.$fam_partner_name,
                    
                    "htmlType"=>array("type"=>"textarea" ),
                    )
                );
                $count1++;
                ?>
        </div>
        <!-- National Ids -->
        <div class="<?php echo $cols_titles_classes[$count1] ?>">
            <?php
                Card::create(array(
                    "value"=>$fam_nat_id.'<br />'.$fam_partner_nat_id,
                    
                    "htmlType"=>array("type"=>"textarea" ),
                ));
                $count1++;
                ?>
        </div>
        <!-- Family Count -->
        <div class="<?php echo $cols_titles_classes[$count1] ?>">
            <?php
                Card::create(array(
                    "value"=>$fam_count,
                    "htmlType"=>array("type"=>"textarea" ),

                ));
                $count1++;
                ?>
        </div>
        <!-- Phones -->
        <div class="<?php echo $cols_titles_classes[$count1] ?>">
            <?php
                Card::create(array(
                    "value"=>$fam_home_phone.'<br />'.$fam_aid_phone.'<br />'.$fam_mobile_phone,

                    "htmlType"=>array("type"=>"textarea" ),
                ));
                $count1++;
                ?>
        </div>
        <!-- Address -->
        <div class="<?php echo $cols_titles_classes[$count1] ?>">
            <?php
                Card::create(array(
                    "value"=>$fam_address,
                    
                    "htmlType"=>array("type"=>"textarea" ),
                ));
                $count1++;
                ?>
            <div>
                <h5><?php echo $resid; ?></h5>
            </div>
        </div>
        <!-- Povert Rate & Ref -->
        <div class="<?php echo $cols_titles_classes[$count1] ?>">
            <?php
                Card::create(array(
                    "value"=>$fam_eval.'<br />'.'<br />'.$fam_ref,
                    
                    "htmlType"=>array("type"=>"textarea" ),
                ));
                $count1++;
                ?>
        </div>
        <!-- Children -->
        <div class="<?php echo $cols_titles_classes[$count1] ?>">
            <?php
                Card::create(array(
                    "value"=>$fam_children,//.'<br />'.$family_add_custom,
                    "htmlType"=>array("type"=>"textarea" ),

                ));
                
                $count1++;
                ?>
        </div>
        <!-- General Note -->
        <div class="<?php echo $cols_titles_classes[$count1] ?>">
            <?php
                Card::create(array(
                    "value"=>$fam_general_note,//.'<br />'.$family_add_custom,
                    
                    "htmlType"=>array("type"=>"textarea" ),
                ));
                $count1++;
                ?>
        </div>
        <!-- Team Note -->
        <div class="<?php echo $cols_titles_classes[$count1] ?>">
            <?php
                Card::create(array(
                    "value"=>$fam_team_note,
                    "htmlType"=>array("type"=>"textarea" ),
                ));
                $count1++;
                ?>
        </div>
    </div>
    <hr class="hr_min" />
    <?php
    }
    ?>
</div>