<?php
require 'function.php';

const JWT_SECRET_KEY = "TEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEY";

$res = (Object)Array();
header('Content-Type: json');
$req = json_decode(file_get_contents("php://input"));

try {
    addAccessLogs($accessLogs, $req);
    switch ($handler) {
        case "list":
            echo "API ContentsServer";
            break;

        case "contentsScrap":
        {
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];//사용자가 가지고 있는 토큰이 유효한지 확인하고
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $id = $data->id;
            $contentsNo = $req->contentsNo;

            if (empty($contentsNo)) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "공백이 입력되었습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (!is_numeric($contentsNo)) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "번호를 숫자로 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (validNo($contentsNo) == 0) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "존재하지 않는 콘텐츠 번호입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                if (alreadyScrap($id, $contentsNo)) {
                    $res->result = deleteScrap($id, $contentsNo);
                    $res->isSuccess = FALSE;
                    $res->code = 201;
                    $res->message = "스크랩 목록 삭제";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    return;
                } else {
                    Scrap($id, $contentsNo);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "스크랩 목록 추가";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }
        }

        case "myScrap":
        {
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];//사용자가 가지고 있는 토큰이 유효한지 확인하고
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $id = $data->id;
            http_response_code(200);
            $res->result = myScrap($id);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "내 스크랩 조회";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        }

        case "contentsLike":
        {
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];//사용자가 가지고 있는 토큰이 유효한지 확인하고
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $id = $data->id;
            $contentsNo = $req->contentsNo;

            if (empty($contentsNo)) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "공백이 입력되었습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (!is_numeric($contentsNo)) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "번호를 숫자로 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (validNo($contentsNo) == 0) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "존재하지 않는 콘텐츠 번호입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                if (existsDislikes($id, $contentsNo)) {
                    $res->isSucces = FALSE;
                    $res->code = 00;
                    $res->message = "존재하는 싫어요를 삭제 해주세요";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    return;
                } else {
                    if (existsLikes($id, $contentsNo)) {
                        $res->result = deleteLikes($id, $contentsNo);
                        $res->isSuccess = TRUE;
                        $res->code = 201;
                        $res->message = "컨텐츠 좋아요 삭제";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        return;
                    } else {
                        $res->result = likes($id, $contentsNo);
                        $res->isSuccess = TRUE;
                        $res->code = 100;
                        $res->message = "컨텐츠 좋아요 추가";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        break;
                    }
                }
            }
        }

        case "contentsDislikes":
        {
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];//사용자가 가지고 있는 토큰이 유효한지 확인하고
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $id = $data->id;
            $contentsNo = $req->contentsNo;

            if (empty($contentsNo)) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "공백이 입력되었습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (!is_numeric($contentsNo)) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "번호를 숫자로 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (validNo($contentsNo) == 0) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "존재하지 않는 콘텐츠 번호입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                if (existsLikes($id, $contentsNo)) {
                    $res->isSucces = FALSE;
                    $res->code = 00;
                    $res->message = "존재하는 좋아요를 삭제 해주세요";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    return;
                } else {
                    if (existsDislikes($id, $contentsNo)) {
                        $res->result = deleteDislikes($id, $contentsNo);
                        $res->isSuccess = TRUE;
                        $res->code = 201;
                        $res->message = "컨텐츠 싫어요 삭제";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        return;
                    } else {
                        $res->result = dislikes($id, $contentsNo);
                        $res->isSuccess = TRUE;
                        $res->code = 100;
                        $res->message = "컨텐츠 싫어요 추가";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        break;
                    }
                }
            }
        }
        case "watchingVideo":
        {
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $id = $data->id;
            $contentsNo = $req->contentsNo;
            $type = $req->type;
            $check_type = '/^[1-2]{1}$/';

            if (empty($contentsNo) || empty($type)) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "공백이 입력되었습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            else if(!preg_match($check_type, "$type")) {
                $res->isSucces = FALSE;
                $res->code = 100;
                $res->message = "콘텐츠의 타입을 1(Movie), 2(TV Show) 중 숫자로 선택해주세요";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            else if (!is_numeric($contentsNo)) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "콘텐츠 번호를 숫자로 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (!validNo($contentsNo) && (!validSeriesNo($contentsNo))) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "존재하지 않는 콘텐츠 번호입니다. 다시 확인해주세요";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                if (alreadyWatching($id, $contentsNo)) {
                    $res->result = countPlay($id, $contentsNo);
                    $res->isSuccess = TRUE;
                    $res->code = 201;
                    $res->message = "재생 기록 카운트";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    return;
                } else {
                    $res->result = watchingVideo($id, $type, $contentsNo);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "시청 중인 재생 목록에 추가";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }
        }

        case "getUrl":
        {
            $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }

        case "tvWatchingList":
        {
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];//사용자가 가지고 있는 토큰이 유효한지 확인하고
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $id = $data->id;
            if (tvWatchingList($id) == null) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "사용자가 시청중인 티비 프로그램이 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                http_response_code(200);
                $res->result = tvWatchingList($id);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "시청 중인 티비 목록 조회";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        }

        case "movieWatchingList":
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];//사용자가 가지고 있는 토큰이 유효한지 확인하고
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $id = $data->id;
            if (movieWatchingList($id) == null) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "사용자가 시청중인 영화가 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                http_response_code(200);
                $res->result = movieWatchingList($id);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "시청중인 영화 목록 조회";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

//        case "contentsWatchingList":
//            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];//사용자가 가지고 있는 토큰이 유효한지 확인하고
//            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
//                $res->isSuccess = FALSE;
//                $res->code = 201;
//                $res->message = "유효하지 않은 토큰입니다";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                addErrorLogs($errorLogs, $res, $req);
//                return;
//            }
//            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
//            $id = $data->id;
//            if ((movieWatchingList($id) == null) && (tvWatchingList($id) == null)) {
//                $res->isSucces = FALSE;
//                $res->code = 00;
//                $res->message = "사용자가 시청중인 콘텐츠가 없습니다.";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                return;
//            } else {
//                http_response_code(200);
//                $res->result->tv = (Object) Array();
//                $res->result->movie = (Object) Array();
//                $res->result->tv = tvWatchingList($id);
//                $res->result->movie = movieWatchingList($id);
//                $res->isSuccess = TRUE;
//                $res->code = 100;
//                $res->message = "시청중인 영화 목록 조회";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }

        case "contentsHistory":
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];//사용자가 가지고 있는 토큰이 유효한지 확인하고
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $id = $data->id;
            $id2 = $data->id;
            if (contentsHistory($id, $id2) == null) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "사용자가 시청중인 콘텐츠가 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                http_response_code(200);
                $res->result = contentsHistory($id, $id2);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "시청중인 콘텐츠 목록 조회";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}