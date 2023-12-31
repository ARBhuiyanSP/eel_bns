<?php
if (isset($_GET['process_type']) && $_GET['process_type'] == "equipment_wise_issue") {
    session_start();
    date_default_timezone_set("Asia/Dhaka");
    include '../connection/connect.php';
    include '../helper/utilities.php';


    $package_id = $_POST['package_id'] ?? '';
    $sql=" SELECT round(SUM(t1.issue_qty),2) AS _issue_qty,SUM(round(t1.issue_qty,2)*round(t1.issue_price,2)) AS _total_price,DATE_FORMAT(t1.issue_date, '%m') as _month,DATE_FORMAT(t1.issue_date, '%y') as _year, MONTHNAME(t1.issue_date) as _m_name,t1.package_id
FROM inv_issuedetail AS t1
WHERE (t1.issue_date > now() - INTERVAL 11 month ) ";
if($package_id !=''){
    $sql.=" AND t1.package_id='".$package_id."' ";
    $sql.=" GROUP BY YEAR(t1.issue_date),MONTH(t1.issue_date) ORDER BY YEAR(t1.issue_date),MONTH(t1.issue_date) ASC ";
}else{
    $sql.=" GROUP BY YEAR(t1.issue_date),MONTH(t1.issue_date) ORDER BY YEAR(t1.issue_date),MONTH(t1.issue_date),t1.package_id ASC ";
}


     $equipment_months=[];
    $equipment_amounts=[];
    $equipment_qtys=[];
    $eq_res = mysqli_query($conn, $sql);
    if($eq_res){
        while($row = mysqli_fetch_array($eq_res)){
            $month_and_year=$row["_m_name"]."-".$row["_year"];
            array_push($equipment_months, $month_and_year);
            array_push($equipment_amounts, round($row["_total_price"], 2));
            array_push($equipment_qtys, round($row["_issue_qty"], 2));


        }
    }

    $data=[];
    $series_data =[];
    $data['equipment_months']=$equipment_months;
    $data['equipment_amounts']=$equipment_amounts;
    $data['equipment_qtys']=$equipment_qtys;
    //$data['series_data']=$series_data;

    echo json_encode($data);
   
}

//Material wise Stock Search

if (isset($_GET['process_type']) && $_GET['process_type'] == "mater_wise_stock_search") {
    session_start();
    date_default_timezone_set("Asia/Dhaka");
    include '../connection/connect.php';
    include '../helper/utilities.php';


    $material_name=$_POST['material_name'];
    $material_id_code = $material_name;
    $sqlmat =   "SELECT SUM(t1.mbin_qty-t1.mbout_qty) AS total_stock ,t2.name
FROM `inv_materialbalance` as t1
LEFT JOIN inv_warehosueinfo AS t2 ON t1.warehouse_id=t2.id
WHERE t1.mb_materialid = '".$material_id_code."'
GROUP BY t1.warehouse_id";
$sqlmatres = mysqli_query($conn, $sqlmat);

$data=[];
if($sqlmat){
    while ($row = $sqlmatres->fetch_assoc()) {
        $data[]=$row;
    }
}

  


    echo json_encode($data);
   
}
