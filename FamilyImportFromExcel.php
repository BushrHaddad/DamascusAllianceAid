<?php
/*******************************************************************************
 *
 *  filename    : FamilyEditor.php
 *  last change : 2003-01-04
 *  website     : http://www.churchcrm.io
 *  copyright   : Copyright 2001, 2002, 2003 Deane Barker, Chris Gebhardt
  *
 ******************************************************************************/

//Include the function library
require 'Include/Config.php';
require 'Include/Functions.php';
require 'Include/CanvassUtilities.php';

use ChurchCRM\dto\SystemConfig;
use ChurchCRM\Note;
use ChurchCRM\FamilyQuery;
use ChurchCRM\Utils\InputUtils;
use ChurchCRM\dto\SystemURLs;
use ChurchCRM\Emails\NewPersonOrFamilyEmail;
use ChurchCRM\Utils\RedirectUtils;
use ChurchCRM\Bootstrapper;
use ChurchCRM\Authentication\AuthenticationManager;


use Phppot\DataSource;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

// require_once 'DataSource.php';
// $db = new DataSource();
// $conn = $db->getConnection();
// require_once ('./vendor/autoload.php');

if(isset($_POST["import"]))
{

    // $url='localhost';
    // $username='root';
    // $password='';
    // $conn=mysqli_connect($url,$username,$password,"location");
    // if(!$conn){
    // die('Could not Connect My Sql:' .mysqli_error());
    // }
    $file = $_FILES['file']['tmp_name'];
    $handle = fopen($file, "r");
    $c = 0;
    while(($filesop = fgetcsv($handle, 10000, ",")) !== false)
    {
        $action = $filesop[0];
        $id = $filesop[1];
        $main_name = $filesop[2];
        $main_id = $filesop[1];
        $partner_name = $filesop[1];
        $partner_id = $filesop[1];
        $address1 = $filesop[1];
        $address2 = $filesop[1];
        $region = $filesop[1];
        $state = $filesop[1];
        $home_phone = $filesop[1];
        $aid_phone = $filesop[1];
        $mobile_phone = $filesop[1];
        $status = $filesop[1];
        $aid_notes = $filesop[1];
        $general_notes = $filesop[1];
        $aid_notes = $filesop[1];
        $team_notes = $filesop[1];
        $ref = $filesop[1];
        $membership_status = $filesop[1];
        $members_num = $filesop[1];
        $children = $filesop[1];
        $without_money = $filesop[1];
        $other_notes = $filesop[1];
        $question = $filesop[1];

        // echo \n;
        echo $id."  ".$main_name;
        echo "/n";
        // $sql = "insert into excel(fname,lname) values ('$fname','$lname')";
        // $stmt = mysqli_prepare($conn,$sql);
        // mysqli_stmt_execute($stmt);

        $c = $c + 1;
    }

    if($sql){
        echo "sucess";
    } 
    else {
        echo "Sorry! Unable to import this File.";
    }

}
//Set the page title
$sPageTitle = gettext('Importing Families Data From Excel');
require 'Include/Header.php';

?>

<body>
    <p> Make sure to have a table of this form: </p>

    <table id="example" class="display table  table-bordered data-table" cellspacing="0" style="width:100%;">

        <thead>
            <tr>
                <th>Main Header</th>
            </tr>
            <tr>
                <th>ID</th>
                <th>Main Name</th>
                <th>Partner Name</th>
                <th>Main National ID</th>
                <th>Partner National ID</th>
                <th>Address1</th>
                <th>Address2</th>
                <th>Region</th>
                <th>State</th>
                <th>General Notes</th>
                <th>Team Notes</th>
                <th>Additional Notes</th>
                <th>Family Situation</th>

            </tr>
        </thead>
        <body>
        <tr>
        <td>1</td>
        <td>بشر حداد</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
    
            </tr>
        </body>
        
    </table>

    </br></br>
    <form enctype="multipart/form-data" method="post" role="form">
        <div class="form-group">
            <h2 for="exampleInputFile">File Upload</h2>
            <input type="file" name="file" id="file" size="150"  accept=".xls,.xlsx">
            <p class="help-block">Only Excel/CSV File Import.</p>
        </div>
        <button type="submit" class="btn btn-default" name="import" value="submit">Upload</button>
    </form>
</body>
<?php require 'Include/Footer.php' ?>