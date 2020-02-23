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
            else if(!validGenreNo($genreNo)){
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 장르번호 입니다. 정확히 입력해주세요.";
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

        case "movieDetail" :
            $contentsNo = $vars["contentsNo"];
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


    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
