<?php


//function movieDetail($movieId){
//    $pdo = pdoSqlConnect();
//    $query = "select id, Contents.title, director, cast, `release`, posterPath, rating, duration, overview, videoUrl from Contents
//left join (select MovieData.title, posterUrl as posterPath
//    from MovieData group by MovieData.title) TB
//on Contents.title = TB.title
//where posterPath is not null and id = ?;";
//    $st = $pdo->prepare($query);
//    $st->execute($movieId);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return $res;
//}

function movieDetail($movieNo){
    $pdo = pdoSqlConnect();
    $query = "select no, Contents.title, director, cast, `release`, posterPath, rating, duration, overview, videoUrl from Contents
left join (select MovieData.title, posterUrl as posterPath
    from MovieData group by MovieData.title) TB
on Contents.title = TB.title
where posterPath is not null and no = ?;
";

    $st = $pdo->prepare($query);
    $st->execute([$movieNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res[0];
}


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

function popular(){
    $pdo = pdoSqlConnect();
    $query = "select no, title, posterUrl from Movies order by popularity desc limit 0,20;";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function kids(){
    $pdo = pdoSqlConnect();
    $query = "select no, title, posterUrl from Movies where genre like '%family%' and genre like '%comedy%'
order by voteAverage desc limit 0, 20;";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function newAdd(){
    $pdo = pdoSqlConnect();
    $query = "select no, title, posterUrl from Movies order by no desc limit 0, 20;";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function show(){
    $pdo = pdoSqlConnect();
    $query = "select genre from Movies limit 6;";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}


function genreList(){
    $pdo = pdoSqlConnect();
    $query = "select id, description from Genre;";
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

function searchGenre(){
    $pdo = pdoSqlConnect();
    $query = "select no, title, posterUrl from Movies where genre like ? order by popularity desc limit 0,20;";
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

function movieListGenre($genre){
    $pdo = pdoSqlConnect();
    $query = "select no, title, posterUrl from Movies where genre like ? limit 0,10;";

    $st = $pdo->prepare($query);
    $st->execute([$genre]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
