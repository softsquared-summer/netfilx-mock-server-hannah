<?php
require 'function.php';

const JWT_SECRET_KEY = "TEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEY";

$res = (Object)Array();//배열을 object로 변환
header('Content-Type: json');
$req = json_decode(file_get_contents("php://input"));
try {
    addAccessLogs($accessLogs, $req);
    switch ($handler) {
        case "index":
            echo "API Server";
            break;
        case "ACCESS_LOGS":
            //            header('content-type text/html charset=utf-8');
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/access.log");
            break;
        case "ERROR_LOGS":
            //            header('content-type text/html charset=utf-8');
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/errors.log");
            break;
        /*
         * API No. 0
         * API Name : 테스트 API
         * 마지막 수정 날짜 : 19.04.29
         */
        case "selectMovieGenre":
            $lastNo = $_GET["lastNo"];
            $genreNo = $vars["genreNo"];

            if (!is_numeric($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "게시글 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            else if (!is_numeric($genreNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "장르 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            else if (!validGenreNo($genreNo)){
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 장르번호 입니다. 정확히 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
            if(selectMovieGenre($genreNo, $lastNo)==null){
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "해당 장르에 존재하는 영화가 아직 없습니다. 다른 장르를 선택해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                http_response_code(200);
                $res->result = selectMovieGenre($genreNo, $lastNo);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "장르 별 영화 리스트 조회";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            }


        case "movieDetail" :
            $contentsNo = $vars["contentsNo"];
//            $res->result = movieDetail($contentsNo);
            if(!is_numeric($contentsNo)){ //이건 왜 체크가 안되지..
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "게시글 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                if (!validNo($contentsNo)) {
                    http_response_code(200);
                    $res->isSuccess = FALSE;
                    $res->code = 400;
                    $res->message = "존재하지 않는 컨텐츠 번호 입니다. 정확히 입력해주세요.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    return;
                }
                http_response_code(200);
                $res->result = movieDetail($contentsNo);
                $res->result["season info"] = seriesList($contentsNo);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "영화 정보 상세 조회 페이지";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

        case "genreList" :
            http_response_code(200);
            $res->result = genreList();
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "장르 목록";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "movieDefaultPopular" : {
            $lastNo = $_GET["lastNo"];
            if (!is_numeric($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "게시글 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            http_response_code(200);
            $res->result = movieDefaultPopular($lastNo);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "인기 영화 조회";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        }

        case "movieNewAdd" : {
            $lastNo = $_GET["lastNo"];
            if (!is_numeric($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "게시글 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            http_response_code(200);
            $res->result = movieNewAdd($lastNo);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "새로 추가된 영화";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        }

        case "selectSecondGenre":
        {
            $genre1 = $vars["genreNo1"];
            $genre2 = $vars["genreNo2"];
            $lastNo = $_GET["lastNo"];
            if (!is_numeric($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "게시글 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (!validGenreNo($genre1)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 장르번호 입니다. 정확히 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (!validGenreNo($genre2)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 장르번호 입니다. 정확히 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                http_response_code(200);
                $res->result = secondGenre($genre1, $genre2, $lastNo);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "장르 별 영화 조회";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        }

        case "genrePopular" :
        {
            $genreNo = $vars["genreNo"];
            $lastNo = $_GET["lastNo"];
            if (!is_numeric($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "게시글 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (!validGenreNo($genreNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 장르번호 입니다. 정확히 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                http_response_code(200);
                $res->result = genrePopular($genreNo, $lastNo);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "장르 별 인기 영화 조회";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        }

        case "genreNewAdd" :
        {
            $genreNo = $vars["genreNo"];
            $lastNo = $_GET["lastNo"];
            if (!is_numeric($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "게시글 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (!validGenreNo($genreNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 장르번호 입니다. 정확히 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                http_response_code(200);
                $res->result = genreNewAdd($genreNo, $lastNo);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "장르 별 영화 조회";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        }

        case "similarContents":
        {
            $contentsNo1 = $vars["contentsNo"];
            $contentsNo2 = $vars["contentsNo"];
            $contentsNo3 = $vars["contentsNo"];
            if (!is_numeric($contentsNo1)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "게시글 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                if (!validNo($contentsNo1)) {
                    http_response_code(200);
                    $res->isSuccess = FALSE;
                    $res->code = 400;
                    $res->message = "존재하지 않는 컨텐츠 번호 입니다. 정확히 입력해주세요.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    return;
                }
                http_response_code(200);
                $res->result = similarContents($contentsNo1, $contentsNo2, $contentsNo3);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "비슷한 영화 조회 페이지";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        }
        case "movieMain" :
            http_response_code(200);
            $res->result = movieMain();
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "영화 메인 페이지";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "selectContentsGenre":
            $lastNo = $_GET["lastNo"];
            $genreNo = $vars["genreNo"];

            if (!is_numeric($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "게시글 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            else if(!validGenreNo($genreNo)){
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 장르번호 입니다. 정확히 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if(selectContentsGenre($genreNo, $lastNo) == null){
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "아직 등록되지 않은 콘텐츠 입니다 다른 장르를 선택해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                http_response_code(200);
                $res->result = selectContentsGenre($genreNo, $lastNo);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "장르 별 영화 리스트 조회";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

        case "selectTVGenre":
            $lastNo = $_GET["lastNo"];
            $genreNo = $vars["genreNo"];

            if (!is_numeric($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "게시글 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            else if(!validGenreNo($genreNo)){
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 장르번호 입니다. 정확히 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if(selectTvGenre($genreNo, $lastNo) == null){
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "아직 등록되지 않은 콘텐츠 입니다 다른 장르를 선택해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                http_response_code(200);
                $res->result = selectTvGenre($genreNo, $lastNo);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "장르 별 티비프로그램 리스트 조회";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

        case "contentsPopular" : {
            $lastNo = $_GET["lastNo"];
            if (!is_numeric($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "콘텐츠 항목 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            http_response_code(200);
            $res->result = contentsDefaultPopular($lastNo);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "인기 영화 조회";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        }

        case "tvPopular" : {
            $lastNo = $_GET["lastNo"];
            if (!is_numeric($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "콘텐츠 항목 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            http_response_code(200);
            $res->result = tvPopular($lastNo);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "인기 영화 조회";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        }

        case "contentsNewAdd" : {
            $lastNo = $_GET["lastNo"];
            if (!is_numeric($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "게시글 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            http_response_code(200);
            $res->result = contentsNewAdd($lastNo);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "새로 추가된 콘텐츠 리스트";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        }

        case "tvNewAdd" : {
            $lastNo = $_GET["lastNo"];
            if (!is_numeric($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "게시글 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            http_response_code(200);
            $res->result = tvNewAdd($lastNo);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->code = 100;
            $res->message = "새로 추가된 티비프로그램 리스트";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        }

        case "contentsSecondGenre":
        {
            $genre1 = $vars["genreNo1"];
            $genre2 = $vars["genreNo2"];
            $lastNo = $_GET["lastNo"];
            if (!is_numeric($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "게시글 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (!validGenreNo($genre1)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 장르번호 입니다. 정확히 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (!validGenreNo($genre2)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 장르번호 입니다. 정확히 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if(contentsSecondGenre($genre1, $genre2, $lastNo)==null){
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "아직 콘텐츠가 등록되지 않았습니다. 다른 장르를 선택해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }else{
                http_response_code(200);
                $res->result = contentsSecondGenre($genre1, $genre2, $lastNo);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "장르 별 콘텐츠 조회";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        }

        case "tvSecondGenre":
        {
            $genre1 = $vars["genreNo1"];
            $genre2 = $vars["genreNo2"];
            $lastNo = $_GET["lastNo"];
            if (!is_numeric($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "게시글 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (!validGenreNo($genre1)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 장르번호 입니다. 정확히 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (!validGenreNo($genre2)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 장르번호 입니다. 정확히 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if(tvSecondGenre($genre1, $genre2, $lastNo)==null){
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "아직 콘텐츠가 등록되지 않았습니다. 다른 장르를 선택해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }else{
                http_response_code(200);
                $res->result = tvSecondGenre($genre1, $genre2, $lastNo);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "장르 별 티비프로그램 조회";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        }

        case "contentsGenrePopular" :
        {
            $genreNo = $vars["genreNo"];
            $lastNo = $_GET["lastNo"];
            if (!is_numeric($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "게시글 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (!validGenreNo($genreNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 장르번호 입니다. 정확히 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if(contentsGenrePopular($genreNo, $lastNo) == null){
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "아직 콘텐츠가 등록되지 않았습니다. 다른 장르를 선택해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }else{
                http_response_code(200);
                $res->result = contentsGenrePopular($genreNo, $lastNo);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "장르 별 인기 영화 조회";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        }

        case "tvGenrePopular" :
        {
            $genreNo = $vars["genreNo"];
            $lastNo = $_GET["lastNo"];
            if (!is_numeric($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "게시글 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (!validGenreNo($genreNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 장르번호 입니다. 정확히 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if(tvGenrePopular($genreNo, $lastNo) == null){
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "아직 콘텐츠가 등록되지 않았습니다. 다른 장르를 선택해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }else{
                http_response_code(200);
                $res->result = tvGenrePopular($genreNo, $lastNo);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "장르 별 인기 영화 조회";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        }

        case "contentsGenreNewAdd" :
        {
            $genreNo = $vars["genreNo"];
            $lastNo = $_GET["lastNo"];
            if (!is_numeric($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "게시글 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (!validGenreNo($genreNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 장르번호 입니다. 정확히 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if(contentsGenreNewAdd($genreNo, $lastNo)==null){
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "아직 콘텐츠가 등록되지 않았습니다. 다른 장르를 선택해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }else{
                http_response_code(200);
                $res->result = contentsGenreNewAdd($genreNo, $lastNo);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "장르 별 영화 조회";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        }

        case "tvGenreNewAdd" :
        {
            $genreNo = $vars["genreNo"];
            $lastNo = $_GET["lastNo"];
            if (!is_numeric($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "게시글 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (!validGenreNo($genreNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 장르번호 입니다. 정확히 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if(tvGenreNewAdd($genreNo, $lastNo)==null){
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "아직 콘텐츠가 등록되지 않았습니다. 다른 장르를 선택해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }else{
                http_response_code(200);
                $res->result = tvGenreNewAdd($genreNo, $lastNo);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "장르 별 영화 조회";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        }

        case "selectTvGenre":
            $lastNo = $_GET["lastNo"];
            $genreNo = $vars["genreNo"];

            if (!is_numeric($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "게시글 번호는 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            else if(!validGenreNo($genreNo)){
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 장르번호 입니다. 정확히 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if(tvSecondGenre($genreNo, $lastNo) == null){
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "아직 등록되지 않은 콘텐츠 입니다 다른 장르를 선택해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                http_response_code(200);
                $res->result = tvSecondGenre($genreNo, $lastNo);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "장르 별 영화 리스트 조회";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

        case "searchContents":
            $keyword = $_GET["keyword"];
            if(empty($keyword)) {
                http_response_code(200);
                $res->result = contentsList();
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "영화 리스트 조회";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }else {
                if (contentsSearch($keyword) == null) {
                    http_response_code(200);
                    $res->isSuccess = FALSE;
                    $res->code = 400;
                    $res->message = "검색어와 일치하는 결과가 없습니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    return;
                } else {
                    http_response_code(200);
                    $res->result = contentsSearch($keyword);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "장르 별 영화 리스트 조회";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }





    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
