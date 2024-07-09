<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('../includes/crud.php');

$db = new Database();
$db->connect();

    if (empty($_POST['category_id'])) {
        $response['success'] = false;
        $response['message'] = "Category Id is Empty";
        print_r(json_encode($response));
        return false;
    }
$category_id = $db->escapeString($_POST['category_id']);

$sql = "SELECT * FROM sub_categories WHERE category_id = $category_id;";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);

if ($num >= 1){
    foreach ($res as $row) {
        $temp['id'] = $row['id'];
        $temp['category_id'] = $row['category_id'];
        $temp['name'] = $row['name'];
        $temp['image'] = DOMAIN_URL . $row['image'];
        $rows[] = $temp;
    }
    $response['success'] = true;
    $response['message'] = "Sub Categories Listed Successfully";
    $response['data'] = $rows;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Sub Categories Not found";
    print_r(json_encode($response));

}
?>