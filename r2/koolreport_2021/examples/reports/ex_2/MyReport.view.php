<?php

 use \koolreport\widgets\koolphp\Table;
    use \koolreport\widgets\google\ColumnChart;
    use \koolreport\widgets\koolphp\Card;
?>
<style type="text/css">
    body{padding-top: 1rem;}
    .col-md-1,.col-md-2,.col-md-3{text-align: center;}
    .card-body {    -ms-flex: 0 0 auto;    flex: 0 0 auto !important;    padding: 0rem;}
    .koolphp-card .card-value {    font-size: 13px;    text-align: center;    /*font-weight: 1000;*/    padding-bottom: 5px;}
    .koolphp-card {    /*border: solid 1px #ddd;*/    padding-bottom: 0.5em;padding-top: 0.5em; }
    .koolphp-card .card-title { font-weight: 700;   font-size: 13px;    text-align: center;    color: #aaa; /*font-weight: 1000; */ }

    @media print{@page {size: landscape}}
    
    .cls-0{width:4em; text-align: center !important;}
    .cls-0-min{width:3em; text-align: center !important;}
    .cls-1{width:7em; text-align: center !important;font-size: 11px;font-weight: 1000;}
    .cls-2{width:6em;text-align: center !important;font-size: 14px;font-weight: 1000;}
    .cls-3{width:9em;text-align: right !important; font-weight: 1000; }
    .cls-4{width:21em;text-align: right !important; font-size: 7px;font-weight: 1000;}

    .col-md-1, .col-md-2, .col-md-3 {      text-align: right !important;}


    .cls-0-h{width:4em; text-align: center !important;}
    .cls-0-min-h{width:3em; text-align: center !important;}
    
    .cls-1-h{width:5em; text-align: center !important}
    .cls-2-h{width:6em;text-align: center !important;}
    .cls-3-h{width:9em;text-align: center !important;}
    
    .hr_min  {margin-top: 0rem;margin-bottom: 0rem;}
    /*.container{max-width: 1140px;}*/
    /*.container{padding-right: none;padding-left: none;margin-right: none;margin-left: none;}*/
</style>

<!-- <style type="text/css" media="print">
    @page {    size: landscape;}
    body{ writing-mode:tb-rl;}
</style> -->
<div class="container" dir="rtl" style="width:100%;margin: 0 auto !important; padding: 0 !important;" >

    <?php

    //  $sql2=" select * from  master_view m where m.family_id =9280  COLLATE utf8_bin ";
     $sql2=$_GET['query'];//" select * from  master_view m where m.family_id =9280  ";
    //$sql2=" select * from  master_view m where m.family_id =9280;";//"  limit 0, 10";
     //echo  $sql2; exit;
    $config = include "./../../config.php";
    $conn=$config['churchcrm']['con'];
    //remove the limit from query
    //this works
    //mysqli_query($conn,"set names utf8");
    //and this also works:
    mysqli_query($conn,"SET CHARACTER SET 'utf8';");

    $cols_titles1 = array("Team Name", "Team Address");
    $cols_titles = 
    array(
    "تسلسل"       ,
    "الأسم الثلاثي والتابع"   ,
    "الرقم الوطني"       ,
    "العدد"        ,
    "الموبايل"       ,
    "العنوان"        ,
    "التقييم"      ,
    "أسم وميلاد<br />
     الأولاد"      ,
    "ملاحظات<br />
     عامة"      ,
    "ملاحظات<br />
     الزيارة"      
    );


    $cols_titles_headers=array("cls-0-h","cls-3-h", "cls-3-h","cls-0-min-h", "cls-1-h"   , "cls-2-h" , "cls-0-min-h"     , "cls-2-h"     ,"cls-3-h" ,"cls-3-h");
 
    $cols_titles_classes=array("cls-0"  ,"cls-3"  ,  "cls-3" ,"cls-0-min"  , "cls-1"     , "cls-2"   , "cls-1" ,     "cls-2" ,         "cls-4"   ,"cls-4");
    $result2=mysqli_query($conn,$sql2);

    $result1=mysqli_query($conn,$sql2);
    $row1=mysqli_fetch_array($result1);
    print_r($row1); exit;
    $team_name   = $row1['team_name']; 
    $year_name= $row1['year_name']; 
    $month_name= $row1['month_name'];

    $sql3 = ' select c1 from  family_custom fc,master_view mv ,family_custom_master fcm where mv.master_id='.$row1['master_id'].' and fc.c1 is not null and   fcm.fam_custom_Field in ("c1");';
    $result3 = mysqli_query($conn,$sql3);
    $row3 = mysqli_fetch_array($result3); //print_r($row3);
    $resid="";
    $resid=$row3['c1'];
   //echo gettype($resid).'$$$$$$$$';
    if(strlen($resid)>1)
        $resid='مقيـــم';
    else
        $resid='مهجر';
    
    $i=0;
    ?>
    <?php
    //Table -1
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
    while ($row2=mysqli_fetch_array($result2))
    {
         $team_name   = $row2['team_name']; $year_name= $row2['year_name']; $month_name= $row2['month_name'];

         $family_name        = $row2['family_name'];
         $family_nat_id      = rand(1,9).''.rand(1,9).''.rand(999999999999,999999999999);//.''.rand('0','10000').''.rand("0",10000);// 'a3deer3rl03444222';//$row2['fam_nat_id'];
         $family_id          = $row2['family_id'];
         $family_phone       = '0932430116';//'.rand('0','10000').''.rand("0",10000);// '011-334420';//$row2['fam_add'];
         $family_phone2      = '0944506933';//.rand('0','10000').''.rand("0",10000);// '011-334420';//$row2['fam_add'];
         $family_add         = $row2['fam_add'];//'011-334420';//$row2['fam_add'];
         $family_add_custom         = 'ساحة القصور مقابل مطعم الرحمة';//$row2['fam_add_custom'];//'011-334420';//$row2['fam_add'];
         $fam_eval =' تقييم الحالة ';
         $fam_members_details_arr=  array("mem_1"=>array("name"=>"فادي","birthday"=>"2001"),"mem_2"=>array("name"=>"سالي","birthday"=>"2004"));
         $fam_members_details_string='';
         foreach ($fam_members_details_arr as $memebers => $meme) {
            $fam_members_details_string.=$meme['name'].' '.$meme['birthday'].'<br />';
         }
         $cash_aid = $row2['cash_name'];
         $fam_member_count=rand(0,9);// '3';//$row2['fam_number'];
         $notes_1='من عربين الزوج شوفير اجار 20000
        كانت على خلاف مع زوجها و تصالحوا تقول انها انفصلت عن زوجها
            8-2019  هل تسكن عند هلها عيسى نعمة حارة الاخ جبران انتقلت لجرمانا حسب ف 208';//$row2['fam_attend'];
        $notes_2='من عربين الزوج شوفير اجار 20000
        كانت على خلاف مع زوجها و تصالحوا تقول انها انفصلت عن زوجها
            8-2019  هل تسكن عند هلها عيسى نعمة حارة الاخ جبران انتقلت لجرمانا حسب ف 208';
         // $i++;
        ?>
        <?php
        if ($i%3==0){
        ?>
        <?php if ($i!=0){?>
            <br /><br /><br /><br /><br /><br /><br />
            <!-- <hr style="border: #000 0.3px solid;" /> -->
        <?php } ?>
         <!-- <br /><br /> -->
        <div class="text-center">
            <p class="lead">
                <?php  echo $team_name ?><?php  echo $month_name .' - '.$year_name ?>
            </p>
        </div>
        <div class="row">
            <?php for ($tt=0;$tt<count($cols_titles);$tt++){ ?>
                <div class=<?php echo $cols_titles_headers[$tt] ?> ><?php echo  $cols_titles[$tt];    ?>   </div>
            <?php } ?>
        </div>
        <hr />
        <?php 
        }
        //echo $i.']]]';
            $i++;
            $count1=0;
        ?>
        <div class="row">
            <div style="text-align: center;" class="<?php echo $cols_titles_classes[$count1] ?>">
                <?php
                Card::create(array(
                    "value"=>$family_id,
                    "title"=>'Family Name',
                    "htmlType"=>array(
                        "type"=>"textarea"
                    ),
                    "cssClass"=>array(
                        "change"=>'0',
                        "width"=>"15px",
                        //"card"=>"bg-info",    //"bg-warning"         
                        "title"=>"text-white",
                        "value"=>"text-white"
                        )
                    )
                );
                 Card::create(array(
                    "value"=>$family_id,
                    "title"=>'مالية',
                    "htmlType"=>array(
                        "type"=>"checkbox"
                        ),
                    "cssClass"=>array(
                        "active"=>'1',
                        "change"=>'0',
                        "width"=>"15px",
                        //"card"=>"bg-info",    //"bg-warning"
                        "title"=>"text-white",
                        "value"=>"text-white"
                        )
                    )
                );
                 Card::create(array(
                    "value"=>$family_id,
                    "title"=>'بدون.م',
                    "htmlType"=>array(
                        "type"=>"checkbox"
                        ),
                    "cssClass"=>array(
                        "active"=>'1',
                        "change"=>'0',
                        "width"=>"15px",
                        //"card"=>"bg-info",    //"bg-warning"
                        "title"=>"text-white",
                        "value"=>"text-white"
                        )
                    )
                );
                $count1++;
                ?>
            </div>
            <div class="<?php echo $cols_titles_classes[$count1] ?>">
                <?php
                Card::create(array(
                    "value"=>$family_name,
                    "title"=>'ايناس ',
                    /*"htmlType"=>array(
                        "type"=>"textarea"
                        ),*/
                    "cssClass"=>array(

                        "card"=>"bg-info",    //"bg-warning"         
                        "title"=>"text-white",
                        "value"=>"text-white"
                        )
                    )
                );
                $count1++;
                ?>
            </div>
            <div class="<?php echo $cols_titles_classes[$count1] ?>">
                <?php
                Card::create(array(
                    "value"=>$family_nat_id,//.'<br />'.$family_nat_id,//$family_add,
                    "title"=>$family_nat_id,
                  /*  "htmlType"=>array(
                        "type"=>"textarea"
                        ),*/
                    "cssClass"=>array(
                        "card"=>"bg-success",
                        "title"=>"text-white",
                        "value"=>"text-white"
                    )
                ));
                $count1++;
                ?>
            </div>
            <div class="<?php echo $cols_titles_classes[$count1] ?>">
                <?php
                Card::create(array(
                    "value"=>$fam_member_count,
                    //"title"=>'Family Name',
                    "htmlType"=>array(
                        "type"=>"textarea"
                        ),
                    "cssClass"=>array(
                        "change"=>'0',
                      //  "width"=>"5px",
                        //"card"=>"bg-info",    //"bg-warning"         
                        "title"=>"text-white",
                        "value"=>"text-white"
                        )
                    )
                );
                $count1++;
                ?>
            </div>
            <div class="<?php echo $cols_titles_classes[$count1] ?>">
                <?php
                Card::create(array(
                    "value"=>$family_phone.'<br />'.$family_phone2.'<br />'.$family_phone.'<br />'.$family_phone2,
                    "title"=>"",
                    "cssClass"=>array(
                        "card"=>"bg-danger",
                        "title"=>"text-white",
                        "value"=>"text-white"
                    )
                ));
                $count1++;
                ?>
            </div>
            <div class="<?php echo $cols_titles_classes[$count1] ?>">
                <?php
                Card::create(array(
                    "value"=>$family_add.'<br />'.$family_add_custom,
                    "title"=>"",
                     "htmlType"=>array(
                        "type"=>"textarea"
                        ),
                    "cssClass"=>array(
                        "card"=>"bg-danger",
                        "title"=>"text-white",
                        "value"=>"text-white"
                    )
                ));
                $count1++;
                ?>
                <div><h5><?php echo $resid; ?></h5></div>
            </div>  
            <div class="<?php echo $cols_titles_classes[$count1] ?>">
                <?php
                Card::create(array(
                    "value"=>$fam_eval,//.'<br />'.$family_add_custom,
                    "title"=>"",
                     "htmlType"=>array(
                        "type"=>"textarea"
                        ),
                    "cssClass"=>array(
                        "card"=>"bg-danger",
                        "title"=>"text-white",
                        "value"=>"text-white"
                    )
                ));
                $count1++;
                ?>
            </div>
            <div class="<?php echo $cols_titles_classes[$count1] ?>">
                <?php
                Card::create(array(
                    "value"=>$fam_members_details_string,//.'<br />'.$family_add_custom,
                    "title"=>"",
                    //"htmlType"=>array("type"=>"textarea" ),
                    "cssClass"=>array(
                        "card"=>"bg-success",
                        "title"=>"text-white",
                        "value"=>"text-white"
                    )
                ));
                
                $count1++;
                ?>
            </div>
            <div class="<?php echo $cols_titles_classes[$count1] ?>">
                <?php
                Card::create(array(
                    "value"=>$notes_1,//.'<br />'.$family_add_custom,
                    "title"=>"",
                    //"htmlType"=>array("type"=>"textarea" ),
                    "cssClass"=>array(
                        "card"=>"bg-success",
                        "title"=>"text-white",
                        "value"=>"text-white"
                    )
                ));
                $count1++;
                ?>
            </div>
            <div class="<?php echo $cols_titles_classes[$count1] ?>">
                <?php
                Card::create(array(
                    "value"=>$notes_2,
                    "title"=>"",
                    //"htmlType"=>array("type"=>"textarea" ),
                    "cssClass"=>array(
                        "card"=>"bg-success",
                        "title"=>"text-white",
                        "value"=>"text-white"
                    )
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