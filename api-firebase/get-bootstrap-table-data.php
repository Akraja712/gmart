<?php
session_start();

// set time for session timeout
$currentTime = time() + 25200;
$expired = 3600;

// if session not set go to login page
if (!isset($_SESSION['username'])) {
    header("location:index.php");
}

// if current time is more than session timeout back to login page
if ($currentTime > $_SESSION['timeout']) {
    session_destroy();
    header("location:index.php");
}

// destroy previous session timeout and create new one
unset($_SESSION['timeout']);
$_SESSION['timeout'] = $currentTime + $expired;

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');

include_once('../includes/custom-functions.php');
$fn = new custom_functions;
include_once('../includes/crud.php');
include_once('../includes/variables.php');
$db = new Database();
$currentdate = date('Y-m-d');
$db->connect();

        // Get the current date and time
        $date = new DateTime('now');

        // Round off to the nearest hour
        $date->modify('+' . (60 - $date->format('i')) . ' minutes');
        $date->setTime($date->format('H'), 0, 0);
    
        // Format the date and time as a string
        $date_string = $date->format('Y-m-d H:i:s');
        $currentdate = date('Y-m-d');

        //users 
        if (isset($_GET['table']) && $_GET['table'] == 'users') {

            $offset = 0;
            $limit = 10;
            $where = '';
            $sort = 'id';
            $order = 'DESC';

            if (isset($_GET['referred_by']) && $_GET['referred_by'] != '') {
                $referred_by = $db->escapeString($fn->xss_clean($_GET['referred_by']));
                $where .= "AND referred_by = '$referred_by' "; // Properly append the condition
            }
            if (isset($_GET['profile']) && $_GET['profile'] != '') {
                $profile = $db->escapeString($fn->xss_clean($_GET['profile']));
                if ($profile == 'text') {
                    $where .= "AND profile <> '' "; 
                } else if ($profile == 'NULL') {
                    $where .= "AND (profile = '' OR profile IS NULL) "; 
                }
            }
            if (isset($_GET['offset']))
                $offset = $db->escapeString($_GET['offset']);
            if (isset($_GET['limit']))
                $limit = $db->escapeString($_GET['limit']);
            if (isset($_GET['sort']))
                $sort = $db->escapeString($_GET['sort']);
            if (isset($_GET['order']))
                $order = $db->escapeString($_GET['order']);
        
                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    $search = $db->escapeString($_GET['search']);
                    $where .= " AND (id LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' OR mobile LIKE '%" . $search . "%' OR refer_code LIKE '%" . $search . "%')";
                }
            if (isset($_GET['sort'])){
                $sort = $db->escapeString($_GET['sort']);
            }
            if (isset($_GET['order'])){
                $order = $db->escapeString($_GET['order']);
            }
            $sql = "SELECT COUNT(`id`) as total FROM `users`WHERE 1=1 " . $where;
            $db->sql($sql);
            $res = $db->getResult();
            foreach ($res as $row)
             $total = $row['total'];
           
             $sql = "SELECT * FROM users WHERE 1=1 " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
             $db->sql($sql);
            $res = $db->getResult();
        
            $bulkData = array();
            $bulkData['total'] = $total;
            
            $rows = array();
            $tempRow = array();
        
            foreach ($res as $row) {
           
                $operate = ' <a href="edit-users.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
                $operate .= ' <a class="text text-danger" href="delete-users.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
                $tempRow['id'] = $row['id'];
                $tempRow['name'] = $row['name'];
                $tempRow['mobile'] = $row['mobile'];
                $tempRow['email'] = $row['email'];
                $tempRow['referred_by'] = $row['referred_by'];
                $tempRow['refer_code'] = $row['refer_code'];
                $tempRow['account_num'] = $row['account_num'];
                $tempRow['holder_name'] = $row['holder_name'];
                $tempRow['bank'] = $row['bank'];
                $tempRow['branch'] = $row['branch'];
                $tempRow['ifsc'] = $row['ifsc'];
                $tempRow['age'] = $row['age'];
                $tempRow['city'] = $row['city'];
                $tempRow['state'] = $row['state'];
                $tempRow['device_id'] = $row['device_id'];
                $tempRow['today_income'] = $row['today_income'];
                $tempRow['total_income'] = $row['total_income'];
                $tempRow['balance'] = $row['balance'];
                $tempRow['withdrawal_status'] = $row['withdrawal_status'];
                $tempRow['recharge'] = $row['recharge'];
                $tempRow['total_recharge'] = $row['total_recharge'];
                $tempRow['team_size'] = $row['team_size'];
                $tempRow['valid_team'] = $row['valid_team'];
                $tempRow['total_assets'] = $row['total_assets'];
                $tempRow['total_withdrawal'] = $row['total_withdrawal'];
                $tempRow['team_income'] = $row['team_income'];
                $tempRow['registered_datetime'] = $row['registered_datetime'];
                $tempRow['latitude'] = $row['latitude'];
                $tempRow['longitude'] = $row['longitude'];
                if (!empty($row['profile'])) {
                    $tempRow['profile'] = "<a data-lightbox='category' href='" . $row['profile'] . "' data-caption='" . $row['profile'] . "'><img src='" . $row['profile'] . "' title='" . $row['profile'] . "' height='50' /></a>";
                } else {
                    $tempRow['profile'] = 'No Image';
                }
                $tempRow['operate'] = $operate;
                $rows[] = $tempRow;
            }
            $bulkData['rows'] = $rows;
            print_r(json_encode($bulkData));
        }

        //plan
        if (isset($_GET['table']) && $_GET['table'] == 'plan') {

            $offset = 0;
            $limit = 10;
            $where = '';
            $sort = 'id';
            $order = 'DESC';
            if (isset($_GET['offset']))
                $offset = $db->escapeString($_GET['offset']);
            if (isset($_GET['limit']))
                $limit = $db->escapeString($_GET['limit']);
            if (isset($_GET['sort']))
                $sort = $db->escapeString($_GET['sort']);
            if (isset($_GET['order']))
                $order = $db->escapeString($_GET['order']);
        
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search = $db->escapeString($_GET['search']);
                $where .= "WHERE id like '%" . $search . "%' OR products like '%" . $search . "%'";
            }
            if (isset($_GET['sort'])){
                $sort = $db->escapeString($_GET['sort']);
            }
            if (isset($_GET['order'])){
                $order = $db->escapeString($_GET['order']);
            }
            $sql = "SELECT COUNT(`id`) as total FROM `plan` ";
            $db->sql($sql);
            $res = $db->getResult();
            foreach ($res as $row)
                $total = $row['total'];
           
            $sql = "SELECT * FROM plan " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
            $db->sql($sql);
            $res = $db->getResult();
        
            $bulkData = array();
            $bulkData['total'] = $total;
            
            $rows = array();
            $tempRow = array();
        
            foreach ($res as $row) {
        
                
                $operate = ' <a href="edit-plan.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
                $operate .= ' <a class="text text-danger" href="delete-plan.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
                $tempRow['id'] = $row['id'];
                $tempRow['products'] = $row['products'];
                $tempRow['price'] = $row['price'];
                $tempRow['daily_income'] = $row['daily_income'];
                $tempRow['daily_quantity'] = $row['daily_quantity'];
                $tempRow['monthly_income'] = $row['monthly_income'];
                $tempRow['invite_bonus'] = $row['invite_bonus'];
                $tempRow['category'] = $row['category'];
                $tempRow['unit'] = $row['unit'];
                if (!empty($row['image'])) {
                    $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='50' /></a>";
                } else {
                    $tempRow['image'] = 'No Image';
                }
                if($row['stock']==1)
                $tempRow['stock'] ="<p class='text text-success'>Enabled</p>";
                else
              $tempRow['stock']="<p class='text text-danger'>Disabled</p>";
                $tempRow['operate'] = $operate;
                $rows[] = $tempRow;
            }
            $bulkData['rows'] = $rows;
            print_r(json_encode($bulkData));
        }

if (isset($_GET['table']) && $_GET['table'] == 'categories') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= "WHERE id like '%" . $search . "%' OR name like '%" . $search . "%'";
        }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `categories` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM categories " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {

        
        $operate = ' <a href="edit-categories.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-categories.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        if (!empty($row['image'])) {
            $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='50' /></a>";
        } else {
            $tempRow['image'] = 'No Image';
        }
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

if (isset($_GET['table']) && $_GET['table'] == 'sub_categories') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['offset'])) {
        $offset = $db->escapeString($_GET['offset']);
    }
    if (isset($_GET['limit'])) {
        $limit = $db->escapeString($_GET['limit']);
    }
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($_GET['search']);
        $where .= " AND (l.id LIKE '%" . $search . "%' OR l.name LIKE '%" . $search . "%' OR c.name LIKE '%" . $search . "%')";
    }  
    
    $join = "LEFT JOIN `categories` c ON l.category_id = c.id WHERE l.id IS NOT NULL " . $where;

    $sql = "SELECT COUNT(l.id) AS total FROM `sub_categories` l " . $join ;
    $db->sql($sql);
    $res = $db->getResult();
    $total = $res[0]['total'] ?? 0;

    $sql = "SELECT l.id AS id, l.*, c.name AS category_name FROM `sub_categories` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();

    foreach ($res as $row) {
        $operate = '<a href="edit-sub_categories.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-sub_categories.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        
        $tempRow = array();
        $tempRow['id'] = $row['id'];
        $tempRow['category_name'] = $row['category_name'];
        $tempRow['name'] = $row['name'];
        if (!empty($row['image'])) {
            $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='50' /></a>";
        } else {
            $tempRow['image'] = 'No Image';
        }
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}


if (isset($_GET['table']) && $_GET['table'] == 'seller') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= "WHERE id like '%" . $search . "%' OR store_name like '%" . $search . "%' OR name like '%" . $search . "%' OR mobile_number like '%" . $search . "%'";
        }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `sellers` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM sellers " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {

        
        $operate = ' <a href="edit-seller.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-seller.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['store_name'] = $row['store_name'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile_number'] = $row['mobile_number'];
        $tempRow['password'] = $row['password'];
        $tempRow['store_url'] = $row['store_url'];
        if (!empty($row['image'])) {
            $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='50' /></a>";
        } else {
            $tempRow['image'] = 'No Image';
        }
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

$db->disconnect();

