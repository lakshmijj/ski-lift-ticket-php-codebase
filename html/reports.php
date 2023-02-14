<?php
//start session if not started
session_start();

include_once('includes/functions.php');
commonCheck();

//get current request method from $_SERVER GLOBAL
$request_method = strtoupper($_SERVER['REQUEST_METHOD']);

$hills = getHills();


?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<link href="css/jquery.multiselect.css" rel="stylesheet" type="text/css">
<script src="js/jquery.multiselect.js"></script>


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>




<script>
    $( function() {
        /*$( "#datepickerFrom" ).datepicker();*/

        $("#datepickerFrom").datepicker({

            dateFormat: 'yy-mm-dd',
            onSelect: function(dateText, inst){
                $("#datepickerTo").datepicker("option","minDate",
                    $("#datepickerFrom").datepicker("getDate"));
            }
        });

        $( "#datepickerTo" ).datepicker({ dateFormat: 'yy-mm-dd' });
    } );
</script>

<script>
    $(document).ready(function(){

        $("#searchbutton").click(function(){
            $('#reportDiv').hide();
            $('#notificationBlock').hide();
            var fromDate = $("#datepickerFrom").val();
            var toDate = $("#datepickerTo").val();

            if(fromDate!='' && toDate!=''){
                var hillsArr = $("#hillsArr").val();
                var postData = {'fromDate':fromDate , 'toDate':toDate , 'hillsArr':hillsArr};
                $('#jquery-datatable-ajax-php').DataTable({
                    'processing': true,
                    'serverSide': true,
                    "bDestroy": true,
                    'serverMethod': 'post',
                    'ajax': {
                        'url':'reportsSearch.php',
                         data : postData
                    },
                    'columns': [
                        { data: 'mem_pass_number' },
                        { data: 'hill_name' },
                        { data: 'visited_on' }
                    ]
                });

                setTimeout(function() { checkData(); }, 2000);






                $("input[name='fromDt']").val(fromDate);
                $("input[name='toDt']").val(toDate);
                $("input[name='hillsArrh']").val(hillsArr.toString());

            }else{
                $('#notificationBlock').show();
            }


        });
    });


    function checkData() {
        if ( $("#jquery-datatable-ajax-php").DataTable().rows().count()==0 ) {
            $('#reportDiv').hide();
        }else{
            $('#reportDiv').show();
        }
    }
</script>

<script>
    $('select[multiple]').multiselect({
        columns: 2,
        placeholder: 'Select All'
    });
</script>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link href="./css/styles.css" rel="stylesheet" />
</head>

<body class="loggedin">
<div id="notificationBlock" style="display: none" class="alert alert-danger alert-dismissible fade show text-center">
    <strong>Error!&nbsp;&nbsp;&nbsp;</strong>Please specify the date range to search
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
		<nav class="navtop">
        <?php include 'includes/header.php'; ?>
		</nav>
		<div class="content">
			<h2>Reports</h2>
            <div class="row">
                <div class="col-md-6">
                    <span>From Date: <input type="text" id="datepickerFrom" required></span>
                </div>
                <div class="col-md-6">
                    <span>To Date: <input type="text" id="datepickerTo" required></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h2>Hill</h2>
                    <select id="hillsArr" name="multicheckbox[]" multiple="multiple" class="4colactive">
                        <?php
                             foreach ($hills as $val){
                              echo '<option value="'.$val['hill_id'].'">'.$val['hill_name'].'</option>';
                             }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <button id="searchbutton" type="button" class="btn btn-success">Search</button>
                </div>
            </div>


            <div class="row" id="reportDiv" style="display: none">
                <div class="col-md-6">
                    <form action="reportsDownload.php" method="post">
                        <input type="hidden"  name="fromDt">
                        <input type="hidden"  name="toDt">
                        <input type="hidden"  name="hillsArrh">
                        <input type="hidden" name="downloadType" value="csv">
                        <button type="submit" class="btn reportBtn"><i class="fa fa-download"></i> Download CSV</button>
                    </form>

                </div>
                <div class="col-md-6">

                    <form action="reportsDownload.php" method="post">
                        <input type="hidden"  name="fromDt">
                        <input type="hidden"  name="toDt">
                        <input type="hidden"  name="hillsArrh">
                        <input type="hidden" name="downloadType" value="xls">
                        <button type="submit" class="btn reportBtn"><i class="fa fa-download"></i> Download Excel</button>

                    </form>
                </div>

            </div>



		</div>



<div class="content">

    <div class="container mt-5">
        <table id="jquery-datatable-ajax-php" class="display" style="width:100%">
            <thead>
            <tr>
                <th>Member pass number</th>
                <th>Hill Name</th>
                <th>Visited on</th>
            </tr>
            </thead>
        </table>
    </div>

</div>



</body>

</html>