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
        
    </head>
    <body >
<?php echo '<div><a href="koolreport_2021/examples/reports/ex_2/index.php?query='. $_SESSION['query_is'].'">Publish</a></div>'; ?>
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
                    <th>Bags</th>
                    <th>Cash</th>
                    <th>Month</th>
                    <th>Year</th>
                    <th>Suppliments</th>
                    <th>Teams</th>
                    <th>Visiting</th>
                    <th>Created</th>
                    <th>Family</th>
                    <th>Address</th>
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

            var dataTable = $('#empTable').DataTable({
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
                        } );
                    } );
                },*/
                processing: true,
                serverSide: true,
                //"searching": false,
                'serverMethod': 'post',
                //'searching': false, // Remove default Search Control
                'ajax': {
                    url:'./ajaxfile.php',
                    "dataType": "json",
                      
                    //"contentType": 'application/x-www-form-urlencoded; charset=UTF-8',
                    type: "POST",
                    /* success:function(data2){
                        //data22=json.parse(data2);
                        //alert(data2)
                    },*/
                    //cache: false,
                    'data': function(data){
                        //alert(data.aaData[0].'bag_name');
                        //alert("response is  : " + JSON.stringify(response));
                        //alert(data.draw);
                        //alert(data['query']); //object
                        //alert(JSON.stringify(data)); //string
                        //alert(data.columns[0].data);
                        //alert(data.columns[0].search['value']);
                        ///////////////////////////////////////////////////////
                        //alert(data.query);
                        // Read values
                        var gender = $('#searchByGender').val();
                        var name = $('#searchByName').val();
                        console.log(data);
                        // Append to data
                        data.searchByGender = gender;
                        data.searchByName = name;
                    },
                   
                },
                buttons: [   'copy', 'csv', 'excel', 'pdf'],
                'columns': [
                    { data: 'bag_name' },
                    { data: 'cash_name' },
                    { data: 'month_name' },
                    { data: 'year_name' },
                    { data: 'sup_name' },
                    { data: 'team_name' },
                    { data: 'visiting_name' },
                    { data: 'created' },
                    { data: 'family_name' },
                    { data: 'fam_add' },
                ],
              /*  searchCols: [
                  null,
                  { "search": "sum" },
                  null
               ]*/
            });
            
            $('#searchByName').keyup(function(){
                dataTable.draw();
            });

            $('#searchByGender').change(function(){
                dataTable.draw();
            });
        });
        //function TableDisplay(Tablevalues, stringfy,object)
            
        </script>
    </body>
 
</html>


