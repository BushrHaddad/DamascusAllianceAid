<?php session_start();?>
<html>
    <head>
        <title>Datatable Master Table</title>
        <!-- Datatable CSS -->
        <link href='DataTables/datatables.min.css' rel='stylesheet' type='text/css'>

        <!-- jQuery Library -->
        <script type="text/javascript" charset="utf-8" language="javascript" src="jquery-3.3.1.min.js"></script>
        
        <!-- Datatable JS -->
        <script type="text/javascript" charset="utf-8" language="javascript" src="DataTables/datatables.min.js"></script>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css">  
   <!--      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
  <!-- <script    src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/jquery.dataTables.min.js"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
        
    </head>
    <body >
<?php 
$quy_session=0;
if(isset($_SESSION['query_is']))
    $quy_session=$_SESSION['query_is'];

echo '<div>
    <form name="form1" id="form1"><a id="ahref" name="ahref" href="koolreport_2021/examples/reports/ex_4/index.php?query='. $quy_session.'">Publish</a></form></div>'; 
?>
        <div >
            <!-- Custom Filter -->
            <table>
                <tr>
                    <td>
                        <input type='text' id='searchByName' placeholder='Enter name'>
                    </td>
                    <td>
                        <select id='searchByGender'>
                            <option value=''>-- Select Visiting--</option>
                            <option value='yes'>Yes</option>
                            <option value='no'>No</option>
                        </select>
                    </td>
                </tr>
            </table>
            <!-- Table -->
            <table id='empTable' class='display dataTable'>
                <thead>
                    <tr>
                        <th>Master Id</th>
                        <th>Family Id</th>
                        <th>Year</th>
                        <th>Month</th>
                        <th>Team</th>
                        <th>Cash</th>
                        <th>Bags</th>
                        <th>Suppliments</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- Script -->
        <script type="text/javascript">
            function TableDisplay(Tablevalues) {
                alert("sec"+Tablevalues);
                //alert with json stringfy
                //$('#table_test').html('');
            }
        
            $(document).ready(function(){
            //access the datatable
            var cellval='11';
            //var table=$('#table').Datatable
            $('#empTable th').each( function () {
                var title = $(this).text();
                $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
            });
            
            dataTable = $('#empTable').DataTable({
              //  pageLength: 5, 
              //  ordering: false, 
               /* drawCallback: function(dt) {
                  console.log("draw() callback; initializing Select2's.");
                  $('.experience-jquerySelect2-tag').select2({tags: true, width: "6em"});
                }*/

                initComplete: function () {
                // Apply the search
                this.api().columns().every( function () {
                    var that = this;
                    $( 'input', this.header() ).on( 'keyup change clear', function () {
                        if ( that.search() !== this.value ){
                            console.log(this);
                            that.search( this.value ).draw();  
                        }
                    });
                    //alert(this.query); //alert(responseData);
                });
            },
                /* initComplete: function () {
                this.api().columns().every( function () {
                    var that = this;
                    $( 'input', this.footer() ).on( 'keyup change clear', function () {
                        if ( that.search() !== this.value ) {
                            that
                                .search( this.value )
                                .draw();
                            }
                        });
                    } );
                },*/
                processing: true,
                serverSide: true,
                keys: true,
               /* dataSrc: function (json){
                    data22=JSON.parse(json);
                    //return json.aaData;
                    //alert(json.query);
                },*/
                //"searching": false,
                'serverMethod': 'post',
                //'searching': false, // Remove default Search Control
                'ajax': {
                    url:'./ajaxfile.php',
                    "dataType": "json",
                    //dataSrc:"responseData",
                    //"contentType": 'application/x-www-form-urlencoded; charset=UTF-8',
                    type: "POST",
                    /* success:function(data2){
                        //data22=json.parse(data2);
                        //alert(data2)
                    },*/
                    //cache: false,
                    "dataSrc": function (response){
                        if(response!=0){
                        
                           //alert(0);
                        }
                        //alert(response['data']);
                        //console.log(response.aaData);
                      //alert(response.query);
                        $("#ahref").attr('href','koolreport_2021/examples/reports/ex_2/index.php?query='+response.query);
                        return response.aaData;
                    }
                   
                },
                //buttons: [   'copy', 'csv', 'excel', 'pdf'],
                'columns': [
                    { data: 'master_id' },
                    { data: 'family_id' },
                    { data: 'year_name' },
                    { data: 'month_name' },
                    { data: 'team_name' },
                    { data: 'cash_name' },
                    { data: 'bag_name' },
                    { data: 'sup_name' }
                ],
              /*  searchCols: [
                  null,
                  { "search": "sum" },
                  null
               ]*/
            });
             //alert(response);
         })
          
           /* $('#searchByName').keyup(function(){
                dataTable.draw();
            });

            $('#searchByGender').change(function(){
                dataTable.draw();
            });*/
    
        //function TableDisplay(Tablevalues, stringfy,object) 
        </script>
    </body>
</html>