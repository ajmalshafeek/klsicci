<?php
$config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");

function insertStoreBanner($con, $filePath, $startDate, $endDate)
{
    $feedback = false;
    $lastId = 0;
    $query = "INSERT INTO storebanner (`path`,`start`,`end`) VALUES (?,?,?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'sss', $filePath, $startDate, $endDate);
    if (mysqli_stmt_execute($stmt)) {
        $feedback = true;
    }
    mysqli_stmt_close($stmt);
    return $feedback;
}

function fetchBannerListAll($con)
{
    $dataList = array();
    $query = "SELECT * FROM storebanner WHERE 1";
    $stmt = mysqli_prepare($con, $query);
    //mysqli_stmt_bind_param($stmt);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = $result->fetch_assoc()) {
        $dataList[] = $row;
    }
    mysqli_stmt_close($stmt);

    return $dataList;
}

function deleteBannerById($con, $bannerId)
{
    $success = false;

    $query = "DELETE FROM storebanner WHERE id=?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $bannerId);
    if (mysqli_stmt_execute($stmt)) {
        $success = true;
    }
    mysqli_stmt_close($stmt);
    return $success;
}

function updateBannerById($con, $filePath, $startDate, $endDate, $bannerId)
{
    $query = "";
    $stmt = "";
    if (empty($filePath)) {
        $query = "UPDATE storebanner SET `start`=?,`end`=? WHERE id=?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'ssi', $startDate, $endDate, $bannerId);
    } else {
        $query = "UPDATE storebanner SET `path`=?, `start`=?,`end`=? WHERE id=?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'sssi', $filePath, $startDate, $endDate, $bannerId);
    }
    $success = false;
    if (mysqli_stmt_execute($stmt)) {
        $success = true;
    }
    return $success;
}

function fetchBannerListById($con, $productId)
{
    $query = "";
    $query = "SELECT * FROM storebanner WHERE id=?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $productId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = $result->fetch_assoc();
    mysqli_stmt_close($stmt);

    return $row;
}
