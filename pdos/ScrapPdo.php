<?php

function Scrap($id, $contentsNo){
    $pdo = pdoSqlConnect();
    $query = "insert into Scrap (userId, contentsNo) values (?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$id, $contentsNo]);
    $st = null;
    $pdo = null;
}

function alreadyScrap($id, $contentsNo){
    $pdo = pdoSqlConnect();
    $query = "select exists (select * from Scrap where userId = ? and contentsNo =? and isDeleted = 'N') as exist;";
    $st = $pdo->prepare($query);
    $st->execute([$id, $contentsNo]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);
}

function validNo($contentsNo){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM Contents WHERE no = ?) AS exist;";
    $st = $pdo->prepare($query);
    $st->execute([$contentsNo]);
    $res = $st->fetchAll();

    return intval($res[0]["exist"]);
}

function deleteScrap($id, $contentsNo){
    $pdo = pdoSqlConnect();
    $query = "update Scrap set isDeleted = 'Y' where userId =? and contentsNo =?;";
    $st = $pdo->prepare($query);
    $st->execute([$id, $contentsNo]);
    $st = null;
    $pdo = null;
}

function myScrap($id){
    $pdo = pdoSqlConnect();
    $query = "select no, title, posterUrl from Contents
inner join (select contentsNo, userId, isDeleted
    from Scrap group by contentsNo) scrapTB
on Contents.no = scrapTB.contentsNo
where userId = ? and isDeleted = 'N';";
    $st = $pdo->prepare($query);
    $st->execute([$id]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res;
}

function likes($id, $contentsNo){
    $pdo = pdoSqlConnect();
    $query = "insert into Likes (userId, contentsNo, likeFlag) values (?,?,1);";
    $st = $pdo->prepare($query);
    $st->execute([$id, $contentsNo]);
    $st = null;
    $pdo = null;
}

function existsLikes($id, $contentsNo){
    $pdo = pdoSqlConnect();
    $query = "select exists (select * from Likes where userId = ? and contentsNo =? and likeFlag = 1 and isDeleted = 'N') as exist;";
    $st = $pdo->prepare($query);
    $st->execute([$id, $contentsNo]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);
}

function deleteLikes($id, $contentsNo){
    $pdo = pdoSqlConnect();
    $query = "update Likes set isDeleted = 'Y' where userId = ? and contentsNo = ? and likeFlag = 1;";
    $st = $pdo->prepare($query);
    $st->execute([$id, $contentsNo]);
    $st = null;
    $pdo = null;
}

function existsDislikes($id, $contentsNo){
    $pdo = pdoSqlConnect();
    $query = "select exists (select * from Likes where userId = ? and contentsNo =? and likeFlag = 0 and isDeleted = 'N') as exist;";
    $st = $pdo->prepare($query);
    $st->execute([$id, $contentsNo]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);
}

function dislikes($id, $contentsNo){
    $pdo = pdoSqlConnect();
    $query = "insert into Likes (userId, contentsNo, likeFlag) values (?,?,0);";
    $st = $pdo->prepare($query);
    $st->execute([$id, $contentsNo]);
    $st = null;
    $pdo = null;
}

function deleteDislikes($id, $contentsNo){
    $pdo = pdoSqlConnect();
    $query = "update Likes set isDeleted = 'Y' where userId = ? and contentsNo = ? and likeFlag = 0;";
    $st = $pdo->prepare($query);
    $st->execute([$id, $contentsNo]);
    $st = null;
    $pdo = null;
}

function watchingVideo($id, $contentsNo){
    $pdo = pdoSqlConnect();
    $query = "insert into WatchingVideo (userId, contentsNo) values (?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$id, $contentsNo]);
    $st = null;
    $pdo = null;
}

function countPlay($id, $contentsNo){
    $pdo = pdoSqlConnect();
    $query = "update WatchingVideo set hit = hit + 1 where userId = ? and contentsNo = ?";
    $st = $pdo->prepare($query);
    $st->execute([$id, $contentsNo]);
    $st = null;
    $pdo = null;
}

function alreadyWatching($id, $contentsNo){
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from WatchingVideo where userId = ? and contentsNo = ?)AS exist;";
    $st = $pdo->prepare($query);
    $st->execute([$id, $contentsNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}