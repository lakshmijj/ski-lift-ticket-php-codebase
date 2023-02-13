<?php
session_start();
include_once('includes/functions.php');


$downloadType = $_POST['downloadType'];
$fromDate = $_POST['fromDt'];
$toDate = $_POST['toDt'];
$hillsArr = $_POST['hillsArrh'];
$hillsArrNew = [];
if($hillsArr!=''){
    $hillsArrNew = explode("," , $hillsArr);

}
$res = reportDownload($fromDate,$toDate,$hillsArrNew);

$finalArr = [];
for($i=0;$i<sizeof($res);$i++){
    $finalArr[$i]['SiNo.'] = $i+1;
    $finalArr[$i]['MemberPassNumber'] = $res[$i]['mem_pass_number'];
    $finalArr[$i]['HillName'] = $res[$i]['hill_name'];
    $finalArr[$i]['VisitedOn'] = $res[$i]['visited_on'];
}

if($downloadType=='csv'){
    array_to_csv_download($finalArr);
}else{
    array_to_xls_download($finalArr);
}




function array_to_csv_download(array &$array) {

    if (count($array) == 0) {
        return null;
    }

    $filename = "data_export_" . date("Y-m-d") . ".csv";
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");

    $df = fopen("php://output", 'w');
    fputcsv($df, array_keys(reset($array)));
    foreach ($array as $row) {
        fputcsv($df, $row);
    }
    fclose($df);
    die();
}







function array_to_xls_download($finalArr, $filename = "export.csv", $delimiter=";") {
    date_default_timezone_set('America/Los_Angeles');
    $keys = array_keys($finalArr[0]);
    $table = '<table><tbody><tr>';
    foreach ($keys as $keyVal){
        $table .= "<td>".$keyVal."</td>";
    }
    $table .= '</tr>';

    for($k=0;$k<count($finalArr);$k++){
        $table.= '<tr>';
        $row = $finalArr[$k];
        foreach ($row as $key=>$lval) {
            $table.= '<td>'.  $lval . '</td>';
        }
        $table.= '</tr>';

    }
    $table.= '</tbody></table>';

    $fileToDownload = "Report.xls";
    $contentType = 'application/x-msexcel';

    header('Content-Encoding: UTF-8');
    header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header ("Cache-Control: no-cache, must-revalidate");
    header ("Pragma: no-cache");
    header ("Content-type: ".$contentType.";charset=UTF-8");
    header ("Content-Disposition: attachment; filename=".$fileToDownload );

    echo $table;
    exit;
}



?>
