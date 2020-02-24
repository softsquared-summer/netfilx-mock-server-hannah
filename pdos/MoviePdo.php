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

function selectMovieGenre($genreNo, $lastNo){
    $pdo = pdoSqlConnect();
    $query = "select no, type, title, posterUrl, date from Contents
inner join (select contentsNo, genre1, genre2, genre3
    from GenreList group by contentsNo) TB
on Contents.no = TB.contentsNo
WHERE type = 'Movie' and ? IN (genre1, genre2)
and Contents.no > ? order by date desc limit 0, 7;";
    $st = $pdo->prepare($query);
    $st->execute([$genreNo, $lastNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res;
}

function selectContentsGenre($genreNo, $lastNo){
    $pdo = pdoSqlConnect();
    $query = "select no, type, title, posterUrl from Contents
inner join (select contentsNo, genre1, genre2, genre3
    from GenreList group by contentsNo) TB
on Contents.no = TB.contentsNo
WHERE ? IN (genre1, genre2)
and Contents.no > ? order by date desc limit 0, 7;";
    $st = $pdo->prepare($query);
    $st->execute([$genreNo, $lastNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res;
}

function selectTvGenre($genreNo, $lastNo){
    $pdo = pdoSqlConnect();
    $query = "select no, type, title, posterUrl from Contents
inner join (select contentsNo, genre1, genre2, genre3
    from GenreList group by contentsNo) TB
on Contents.no = TB.contentsNo
WHERE ? IN (genre1, genre2)
and Contents.no > ? order by no desc limit 0, 7;";
    $st = $pdo->prepare($query);
    $st->execute([$genreNo, $lastNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res;
}


function movieDetail($movieNo){
    $pdo = pdoSqlConnect();
    $query = "select no, title, director, cast, overview, `release`, rating, duration, posterUrl, videoUrl from Contents
where no = ?;";
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

function genreTest($genre){
    $pdo = pdoSqlConnect();
    $query = "select * from NetflixContents where FIND_IN_SET(?, listed_in);";
    $st = $pdo->prepare($query);
    $st->execute([$genre]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res;
}

function genreDefault($default, $second){
    $pdo = pdoSqlConnect();
    $query = "select * from NetflixContents
where listed_in like ?;";
    $st = $pdo->prepare($query);
    $st->execute([$default, $second]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res;
}

function movieDefaultPopular($lastNo){
    $pdo = pdoSqlConnect();
    $query = "select no, Contents.title, posterUrl, popular, `release` from Contents
inner join (select MovieData.title, popularity as popular
from MovieData group by MovieData.title, popularity) dataTB
on Contents.title = dataTB.title
where type = 'Movie' 
and Contents.no > ? 
order by popular desc limit 0, 7;";
    $st = $pdo->prepare($query);
    $st->execute([$lastNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function movieNewAdd($lastNo){
    $pdo = pdoSqlConnect();
    $query = "select no, title, posterUrl, `release` from Contents
where type = 'Movie' and Contents.no > ? order by date desc limit 0,7;";
    $st = $pdo->prepare($query);
    $st->execute([$lastNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}



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

function secondGenre($genre1, $genre2, $lastNo){
    $pdo = pdoSqlConnect();
    $query = "select no, type, title, posterUrl from Contents
inner join (select contentsNo, genre1, genre2, genre3
    from GenreList group by contentsNo) TB
on Contents.no = TB.contentsNo
WHERE ? IN (genre1, genre2, genre3) and ? IN (genre1, genre2, genre3)
and type = 'Movie' and Contents.no > ? order by date desc limit 0, 7;";
    $st = $pdo->prepare($query);
    $st->execute([$genre1, $genre2, $lastNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res;
}

function genrePopular($genreNo, $lastNo){
    $pdo = pdoSqlConnect();
    $query = "select no, Contents.title, posterUrl, popular from Contents
inner join (select MovieData.title, popularity as popular
from MovieData group by MovieData.title, popularity) dataTB
on Contents.title = dataTB.title
inner join (select contentsNo, genre1, genre2, genre3
from GenreList group by contentsNo) genreTB
on Contents.no = genreTB.contentsNo
where type = 'Movie' and ? in (genre1,genre2, genre3 )
and Contents.no > ? order by popular desc limit 0, 7;";
    $st = $pdo->prepare($query);
    $st->execute([$genreNo, $lastNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res;
}

function genreNewAdd($genreNo, $lastNo){
    $pdo = pdoSqlConnect();
    $query = "select no, Contents.title, posterUrl from Contents
inner join (select contentsNo, genre1, genre2, genre3
from GenreList group by contentsNo) genreTB
on Contents.no = genreTB.contentsNo
where type = 'Movie' and ? in (genre1,genre2, genre3)
and Contents.no > ?
order by date desc limit 0,30;";
    $st = $pdo->prepare($query);
    $st->execute([$genreNo, $lastNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res;
}

function validGenreNo($genreNo){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM Genre WHERE no = ?) AS exist;";
    $st = $pdo->prepare($query);
    $st->execute([$genreNo]);
    $res = $st->fetchAll();

    return intval($res[0]["exist"]);
}

function similarContents($contentsNo1, $contentsNo2, $contentsNo3){
    $pdo = pdoSqlConnect();
    $query = "select Contents.no, title, posterUrl from Contents
inner join (select contentsNo, genre1, genre2, genre3
    from GenreList group by contentsNo)genreTB
on Contents.no = genreTB.contentsNo
where `release` = (select `release` from Contents
inner join (select contentsNo, genre1, genre2, genre3
    from GenreList group by contentsNo)genreTB
on Contents.no = genreTB.contentsNo
where Contents.no = ?)
or (select genreTB.genre1 from Contents
inner join (select contentsNo, genre1, genre2, genre3
    from GenreList group by contentsNo)genreTB
on Contents.no = genreTB.contentsNo
where Contents.no = ?) in (genre1, genre2, genre3)
and (select genreTB.genre2 from Contents
inner join (select contentsNo, genre1, genre2, genre3
    from GenreList group by contentsNo)genreTB
on Contents.no = genreTB.contentsNo
where Contents.no = ?) in (genre1, genre2, genre3)
order by Contents. no desc limit 0, 6;";
    $st = $pdo->prepare($query);
    $st->execute([$contentsNo1, $contentsNo2, $contentsNo3]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res;
}

function movieMain(){
    $pdo = pdoSqlConnect();
    $query = "SELECT *
    FROM (SELECT Contents.no, posterUrl, title, videoUrl, Gerne1.description as G1, Genre2.description as G2, Genre3.description as G3
 FROM Contents INNER JOIN GenreList
      ON Contents.no = GenreList.contentsNo
           INNER JOIN Genre as Gerne1
      ON GenreList.genre1 = Gerne1.no
 inner join Genre as Genre2
 on GenreList.genre2 = Genre2.no
 left join Genre as Genre3
 on GenreList.genre3 = Genre3.no
where type = 'Movie'
 ORDER BY Contents.no desc
limit 10) as randomContents
    ORDER BY rand() limit 1;";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function tvMain(){
    $pdo = pdoSqlConnect();
    $query = "SELECT *
    FROM (SELECT Contents.no, posterUrl, title, videoUrl, Gerne1.description as G1, Genre2.description as G2, Genre3.description as G3
 FROM Contents INNER JOIN GenreList
      ON Contents.no = GenreList.contentsNo
           INNER JOIN Genre as Gerne1
      ON GenreList.genre1 = Gerne1.no
 inner join Genre as Genre2
 on GenreList.genre2 = Genre2.no
 left join Genre as Genre3
 on GenreList.genre3 = Genre3.no
where type = 'TV Show'
 ORDER BY Contents.no desc
limit 10) as randomContents
    ORDER BY rand() limit 1;";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function allMain(){
    $pdo = pdoSqlConnect();
    $query = "SELECT *
    FROM (SELECT Contents.no, posterUrl, title, videoUrl, Gerne1.description AS G1, Genre2.description AS G2, Genre3.description AS G3
 FROM Contents INNER JOIN GenreList
      ON Contents.no = GenreList.contentsNo
           INNER JOIN Genre AS Gerne1
      ON GenreList.genre1 = Gerne1.no
 INNER JOIN Genre AS Genre2
 ON GenreList.genre2 = Genre2.no
 LEFT JOIN Genre AS Genre3
 ON GenreList.genre3 = Genre3.no
 ORDER BY Contents.no DESC
LIMIT 10) AS randomContents
    ORDER BY rand() LIMIT 1;";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res;
}

function contentsDefaultPopular($lastNo){
    $pdo = pdoSqlConnect();
    $query = "select no, Contents.title, posterUrl, popular, `release` from Contents
inner join (select MovieData.title, popularity as popular
from MovieData group by MovieData.title, popularity) dataTB
on Contents.title = dataTB.title
where Contents.no > ?
order by popular desc limit 0, 7;";
    $st = $pdo->prepare($query);
    $st->execute([$lastNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function tvPopular($lastNo){
    $pdo = pdoSqlConnect();
    $query = "select no, Contents.title, posterUrl, popular, `release`, type from Contents
inner join (select MovieData.title, popularity as popular
from MovieData group by MovieData.title, popularity) dataTB
on Contents.title = dataTB.title
where type='TV Show' and Contents.no > ?
order by popular desc limit 0, 7;";
    $st = $pdo->prepare($query);
    $st->execute([$lastNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function contentsNewAdd($lastNo){
    $pdo = pdoSqlConnect();
    $query = "select no, title, posterUrl, `release` from Contents
where Contents.no > ? order by date desc limit 0,7;";
    $st = $pdo->prepare($query);
    $st->execute([$lastNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function tvNewAdd($lastNo){
    $pdo = pdoSqlConnect();
    $query = "select no, title, posterUrl, `release`, date, type from Contents
where type = 'TV Show' and Contents.no > ? order by no desc limit 0,7;";
    $st = $pdo->prepare($query);
    $st->execute([$lastNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}


function tvDefaultPopular($lastNo){
    $pdo = pdoSqlConnect();
    $query = "select no, Contents.title, posterUrl, popular, `release` from Contents
inner join (select MovieData.title, popularity as popular
from MovieData group by MovieData.title, popularity) dataTB
on Contents.title = dataTB.title
where type = 'TV Show' 
and Contents.no > ? 
order by popular desc limit 0, 7;";
    $st = $pdo->prepare($query);
    $st->execute([$lastNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function contentsSecondGenre($genre1, $genre2, $lastNo){
    $pdo = pdoSqlConnect();
    $query = "select no, type, title, posterUrl from Contents
inner join (select contentsNo, genre1, genre2, genre3
    from GenreList group by contentsNo) TB
on Contents.no = TB.contentsNo
WHERE ? IN (genre1, genre2, genre3) and ? IN (genre1, genre2, genre3)
and Contents.no > ? order by no desc limit 0, 7;";
    $st = $pdo->prepare($query);
    $st->execute([$genre1, $genre2, $lastNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res;
}

function tvSecondGenre($genre1, $genre2, $lastNo){
    $pdo = pdoSqlConnect();
    $query = "select no, type, title, posterUrl from Contents
inner join (select contentsNo, genre1, genre2, genre3
    from GenreList group by contentsNo) TB
on Contents.no = TB.contentsNo
WHERE ? IN (genre1, genre2, genre3) and ? IN (genre1, genre2, genre3)
and type = 'TV Show' and Contents.no > ? order by no desc limit 0, 7;";
    $st = $pdo->prepare($query);
    $st->execute([$genre1, $genre2, $lastNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res;
}

function contentsGenrePopular($genreNo, $lastNo){
    $pdo = pdoSqlConnect();
    $query = "select no, Contents.title, posterUrl, popular from Contents
inner join (select MovieData.title, popularity as popular
from MovieData group by MovieData.title, popularity) dataTB
on Contents.title = dataTB.title
inner join (select contentsNo, genre1, genre2, genre3
from GenreList group by contentsNo) genreTB
on Contents.no = genreTB.contentsNo
where ? in (genre1,genre2, genre3 )
and Contents.no > ? order by popular desc limit 0, 7;";
    $st = $pdo->prepare($query);
    $st->execute([$genreNo, $lastNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res;
}

function tvGenrePopular($genreNo, $lastNo){
    $pdo = pdoSqlConnect();
    $query = "select no, Contents.title, posterUrl, popular from Contents
inner join (select MovieData.title, popularity as popular
from MovieData group by MovieData.title, popularity) dataTB
on Contents.title = dataTB.title
inner join (select contentsNo, genre1, genre2, genre3
from GenreList group by contentsNo) genreTB
on Contents.no = genreTB.contentsNo
where type = 'TV Show' and ? in (genre1,genre2, genre3)
and Contents.no > ? order by popular desc limit 0, 7;";
    $st = $pdo->prepare($query);
    $st->execute([$genreNo, $lastNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res;
}

function contentsGenreNewAdd($genreNo, $lastNo){
    $pdo = pdoSqlConnect();
    $query = "select no, Contents.title, posterUrl from Contents
inner join (select contentsNo, genre1, genre2, genre3
from GenreList group by contentsNo) genreTB
on Contents.no = genreTB.contentsNo
where ? in (genre1,genre2, genre3)
and Contents.no > ?
order by date desc limit 0,30;";
    $st = $pdo->prepare($query);
    $st->execute([$genreNo, $lastNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res;
}

function tvGenreNewAdd($genreNo, $lastNo){
    $pdo = pdoSqlConnect();
    $query = "select no, Contents.title, posterUrl from Contents
inner join (select contentsNo, genre1, genre2, genre3
from GenreList group by contentsNo) genreTB
on Contents.no = genreTB.contentsNo
where type = 'TV Show' and ? in (genre1,genre2, genre3)
and Contents.no > ?
order by date desc limit 0,30;";
    $st = $pdo->prepare($query);
    $st->execute([$genreNo, $lastNo]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res;
}