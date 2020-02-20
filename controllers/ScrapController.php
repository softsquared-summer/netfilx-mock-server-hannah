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
            } else if(!is_numeric($contentsNo)){
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "번호를 숫자로 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (validNo($contentsNo) == 0) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "존재하지 않는 게시글 번호입니다.";
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
                }else {
                    $res->result = Scrap($id, $contentsNo);
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
            } else if(!is_numeric($contentsNo)){
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "번호를 숫자로 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (validNo($contentsNo) == 0) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "존재하지 않는 게시글 번호입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                if(existsDislikes($id, $contentsNo)){
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
            } else if(!is_numeric($contentsNo)){
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "번호를 숫자로 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (validNo($contentsNo) == 0) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "존재하지 않는 게시글 번호입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                if(existsLikes($id, $contentsNo)){
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




    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}