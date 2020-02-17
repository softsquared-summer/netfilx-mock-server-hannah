<?php

function movieGenre(){
    $pdo = pdoSqlConnect();
    $query = "select no, posterUrl from Movie limit 0, 15;";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

//function genreList(){
//    $pdo = pdoSqlConnect();
//    $query = "select no, description from Genre;";
//    $st = $pdo->prepare($query);
//    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return $res;
//}


function genreList(){
    $pdo = pdoSqlConnect();
    $query = "select no, description from Genre;";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function movieList(){
    $pdo = pdoSqlConnect();
    $query = "select no, title, posterUrl from Contents limit 0, 10;";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function exceptGenre($listNo, $lastNo){
    $pdo = pdoSqlConnect();
    $query = "select no, title, posterUrl
from Movie
where listNo = ? and no > ? limit 0,2;";

    $st = $pdo->prepare($query);
    $st->execute([$listNo, $lastNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}