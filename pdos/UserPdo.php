<?php
function signUp($id, $pw, $email, $school, $studentNum)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO User (id, pw, email, school, studentNum) VALUES (?,?,?,?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$id, $pw, $email, $school, $studentNum]);
    $st = null;
    $pdo = null;
}

function validUser($id){

    $pdo = pdoSqlConnect();

    $query = "SELECT EXISTS(SELECT * FROM User WHERE id = ?) AS exist;";
    $st = $pdo->prepare($query);
    $st->execute([$id]);
    $res = $st->fetchAll();

    return intval($res[0]["exist"]);
}

function validPw($id, $pw){
    $pdo = pdoSqlConnect();

    $query = "SELECT EXISTS(SELECT * FROM User WHERE id=? and pw = ?) AS exist;";
    $st = $pdo->prepare($query);
    $st->execute([$id, $pw]);
    $res = $st->fetchAll();

    return intval($res[0]["exist"]);
}

function login($id, $pw){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM User WHERE id = ? AND pw = ?) AS exist;";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$id, $pw]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);
}

function userInfo($id){
    $pdo = pdoSqlConnect();
    $query = "select no, id, email, pw, school, studentNum from User where id = ?";

    $st = $pdo->prepare($query);
    $st->execute([$id]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res[0];
}

function userDetail($userNo){
    $pdo = pdoSqlConnect();
    $query = "select * from User where no = ?";

    $st = $pdo->prepare($query);
    $st->execute([$userNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res[0];
}

function validUserNo($userNo){
    $pdo = pdoSqlConnect();

    $query = "SELECT EXISTS(SELECT * FROM User WHERE no = ?) AS exist;";
    $st = $pdo->prepare($query);
    $st->execute([$userNo]);
    $res = $st->fetchAll();

    return intval($res[0]["exist"]);
}

function userAll(){
    $pdo = pdoSqlConnect();
    $query = "select no, id, email, pw, school, studentNum from User";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res;
}

