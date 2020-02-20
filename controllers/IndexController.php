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
            $contentsNo = $vars["movieNo"];
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


        case "myComment" :
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
            $res->result = myComment($id);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "내가 쓴 글 조회";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
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

        case "hotArticle":
            $thumbsUpCnt = $_GET["cnt"];
            if(!is_numeric($thumbsUpCnt)) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "형식에 맞지 않는 게시글 번호입니다. 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if(!thumbsUpCntExists($thumbsUpCnt)) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "조건에 해당하는 게시글이 존재하지 않습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            else{
                http_response_code(200);
                $res->result = hotArticle($thumbsUpCnt);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "hot 게시판 조회";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

        case "lookupFreeBoard":

            http_response_code(200);
            $res->result = lookupFreeBoard();
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "자유게시판 목록 조회";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "lookupSecretBoard":

            http_response_code(200);
            $res->result = lookupSecretBoard();
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "자유게시판 목록 조회";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "lookupFreeComment":

            http_response_code(200);
            $res->result = lookupFreeComment();
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "자유게시판 댓글 목록";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "writeFreeBoard":
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
            $writerId = $data->id;
            $title = $req->title;
            $content = $req->content;

            if (empty($title) || empty($content)) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "공백이 입력됐습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                $res->result = writeFreeBoard($writerId, $title, $content);//id따로 validation안해도 저장.
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "자유게시판에 글 작성 성공!";
                $res->result = successFreeBoard();
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

        case "writeSecretBoard":

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
            $writerId = $data->id;
            $title = $req->title;
            $content = $req->content;

            if (empty($title) || empty($content)) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "공백이 입력됐습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            $res->result = writeSecretBoard($writerId, $title, $content);//id따로 validation안해도 저장.
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "비밀게시판에 글 작성 성공!";
            $res->result = successSecretBoard();
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "articleThumbsUp":
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
            $articleNo = $req->articleNo;

            if(empty($articleNo)) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "공백이 입력되었습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if(!is_numeric($articleNo)) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "번호를 숫자로 입력해주세요.";
                $res->articleNoList = boardList();
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }  else if(validNo($articleNo)==0){
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "존재하지 않는 게시글 번호입니다.";
                $res->result = boardList();
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
                }
            else {
                if (alreadyThumbsUp($id, $articleNo) == 1) {
                    $res->isSuccess = FALSE;
                    $res->code = 201;
                    $res->message = "이미 공감하였습니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    return;
                } else {
                    http_response_code(200);
                    $res->result = thumbsUp($id, $articleNo);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "게시글 추천 수 증가 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }

        case "showArticleComment": //1. empty -> notfound 2.numeric 3. exists
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
            $articleNo = $_GET["no"];

            if(!is_numeric($articleNo)) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "형식에 맞지 않는 게시글 번호입니다. 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            } else if(!validNo($articleNo)){
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "존재하지 않는 게시글 번호입니다. 번호를 확인해주세요.";
                $res->articleList = boardList();
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            } else {
                http_response_code(200);
                $res->result = (Object)Array();
                $res->result->articleResult = showArticle($articleNo);
                $res->result->articleResult->commentResult = commentList($articleNo);//어떻게 해야지;;;
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "글과 댓글 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            http_response_code(200);
            $res->isSuccess = FALSE;
            $res->code = 100;
            $res->message = "게시글 조회에 실패하였습니다. 다시 확인해주세요.";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        }

        case "showArticle":
        {
            $articleNo = $_GET["no"];
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
            $articleNo = $_GET["no"];

            if(!is_numeric($articleNo)) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "형식에 맞지 않는 게시글 번호입니다. 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            } else if(!validNo($articleNo)){
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "존재하지 않는 게시글 번호입니다. 번호를 확인해주세요.";
                $res->result = boardList();
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else {
                http_response_code(200);
                $res->result = showArticle($articleNo);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "번호별 게시글 조회";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        }

        case "showComment":
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
            $articleNo = $_GET["no"];

            if(!is_numeric($articleNo)) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "형식에 맞지 않는 게시글 번호입니다. 숫자를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            } else if(!validNo($articleNo)) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "존재하지 않는 게시글 번호입니다. 번호를 확인해주세요.";
                $res->result = boardList();
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
                else {
                http_response_code(200);
                $res->result = commentList($articleNo);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "번호별 댓글 조회";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        }

        case "writeComment":
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
                $content = $req->content;
                $articleNo = $req->articleNo;
                $check_num = '/^[0-9]{10}$/';

                if (empty($articleNo) || empty($content)){
                    $res->isSucces = FALSE;
                    $res->code = 00;
                    $res->message = "내용에 공백이 입력되었습니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                } else {
                    if (!is_numeric($articleNo)) {
                        $res->isSucces = FALSE;
                        $res->code = 00;
                        $res->message = "형식에 맞지 않는 게시글 번호입니다. 숫자를 입력해주세요.";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        break;
                    } else if (validNo($articleNo) == 0){
                        $res->isSucces = FALSE;
                        $res->code = 00;
                        $res->message = "존재하지 않는 번호입니다.";
                        $res->result->articleList = boardList();
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        break;
                    }
                     else {
                        http_response_code(200);
                        $res->result = writeComment($id, $articleNo, $content);
                        $res->isSuccess = TRUE;
                        $res->code = 100;
                        $res->message = "자유게시판에 댓글 작성 성공!";
                        $res->result = (Object)Array();
                        $res->result->articleResult = showArticle($articleNo);
                        $res->result->commentResult = commentList($articleNo);
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        break;
                    }
                    $res->isSucces = FALSE;
                    $res->code = 00;
                    $res->message = "댓글 입력에 실패했습니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                }
            }

        case "writeReComment" :
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
            $content = $req->content;
            $articleNo = $req->articleNo;
            $parent = $req->parent;

            if (empty($articleNo) || empty($parent) || empty($content)) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "내용에 공백이 입력되었습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            } else {
                if (!is_numeric($articleNo)) {
                    $res->isSucces = FALSE;
                    $res->code = 00;
                    $res->message = "형식에 맞지 않는 게시글 번호입니다. 숫자를 입력해주세요.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                } else if (!is_numeric($parent)) {
                    $res->isSucces = FALSE;
                    $res->code = 00;
                    $res->message = "형식에 맞지 않는 게시글 번호입니다. 숫자를 입력해주세요.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                } else if (!validNo($articleNo)) {
                    $res->isSucces = FALSE;
                    $res->code = 00;
                    $res->message = "존재하지 않는 게시글 번호입니다.";
                    $res->result->boardList = boardList();
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                } else if (!validParent($articleNo, $parent)) {
                    $res->isSucces = FALSE;
                    $res->code = 00;
                    $res->message = "존재하지 않는 댓글 번호(parent)입니다.";
                    $res->result = (Object)Array();
                    $res->result->article = showArticle($articleNo);
                    $res->result->comment = commentList($articleNo);
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                else {
                    http_response_code(200);
                    $res->result = writeReComment($id, $content, $articleNo, $parent);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "게시판에 대댓글 작성 성공!";
                    $res->result = (Object)Array();
                    $res->result->article = showArticle($articleNo);
                    $res->result->comment = commentList($articleNo);
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }
            $res->isSuccess = false;
            $res->code = 200;
            $res->message = "댓글 등록에 실패하였습니다.";
            http_response_code(200);
        }


        case "articleScrap":
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
            $articleNo = $req->articleNo;
            if (empty($articleNo)) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "공백이 입력되었습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if(!is_numeric($articleNo)){
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "번호를 숫자로 입력해주세요.";
                $res->result->boardList = boardList();
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (validNo($articleNo) == 0) {
                $res->isSucces = FALSE;
                $res->code = 00;
                $res->message = "존재하지 않는 게시글 번호입니다.";
                $res->result->boardList = boardList();
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                if (alreadyScrap($id, $articleNo) == 1) {
                    $res->isSuccess = FALSE;
                    $res->code = 201;
                    $res->message = "이미 스크랩하였습니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    return;
                } else {
                    http_response_code(200);
                    $res->result = Scrap($id, $articleNo);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "스크랩 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }
        }
        case "freeListAll":
            {
//                $limit = $_GET["limit"];
                http_response_code(200);
                $res->result = freeListAll();
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "자유게시판 리스트 조회, 10개 항목 표시";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

        case "secretListAll":
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
            $res->result = secretListAll();
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "비밀게시판 리스트 조회, 10개 항목 표시";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        }

        case "infiniteScroll":
        {
            $lastNo = $_GET["lastNo"];
            if (!is_numeric($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 게시글 번호입니다. 다시 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (!validNo($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 게시글 번호입니다. 다시 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else {
                if (validScroll($lastNo) == 1) {
                    http_response_code(200);
                    $res->isSuccess = FALSE;
                    $res->code = 400;
                    $res->message = "더 이상 표시할 게시글이 없습니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    return;
                } else {
                    http_response_code(200);
                    $res->result = infiniteScroll($lastNo);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "게시글 스크롤";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }
        }

        case "infiniteScrollSecret":
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

            $lastNo = $_GET["lastNo"];
            if (!is_numeric($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 게시글 번호입니다. 다시 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if (!validNo($lastNo)) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 게시글 번호입니다. 다시 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            } else if ($lastNo == 4) {
                http_response_code(200);
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "더 이상 표시할 게시글이 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            } else {
                if (validSecretScroll($lastNo) == 1) {
                    http_response_code(200);
                    $res->isSuccess = FALSE;
                    $res->code = 400;
                    $res->message = "더 이상 표시할 게시글이 없습니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    return;
                } else {
                    http_response_code(200);
                    $res->result = infiniteScrollSecret($lastNo);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "게시글 스크롤";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }
        }


        case "pageScroll":
        {
            $last_no = $_GET["last_no"];

            $conn = mysqli_connect('127.0.0.1', 'root', 'Kjoy2357**', 'EveryTime');
            if (mysqli_connect_errno($conn)) {
                echo mysqli_connect_error();
            }
            mysqli_set_charset($conn, "utf8");

            $sql = 'SELECT no, title, content, id, thumbsUpCnt, commetCnt, createdAt
                FROM freeBoard
                     left outer join (select articleNo,
                          count(no) as commetCnt
                from freeComment group by articleNo) commentCntTB
                    on freeBoard.no = commentCntTB.articleNo
                     left outer join (select thumbsUpNo,
                          count(no) as thumbsUpCnt
                         from ThumbsUp group by thumbsUpNo)thumbsUpTB
                    on freeBoard.no = thumbsUpTB.thumbsUpNo
                    where category = 1 and freeBoard.no < ? ORDER BY freeBoard.no DESC limit 0, 8;';
            $res = mysqli_query($conn, $sql);
            $result = Array();

            while($row = mysqli_fetch_array($res))
            {
                array_push($result,
                    array('id'=>$row[0],
                          'pw'=>$row[1]));
            }
            echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);
            break;
            }

//        case "listUp":
//
//            http_response_code(200);
//            $res->result = listUp();
//            echo listUp();
//            $res->isSuccess = TRUE;
//            $res->code = 100;
//            $res->message = "리뷰 검색";
//            echo json_encode($res, JSON_NUMERIC_CHECK);
//            break;


       case "createReview" :
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
            $id = $data->id; //기존 토큰에 존재하는 id 가져오기. id값으로 작업을 수행해야하니까.
            $pNum = $req->pNum;
            $review = $req->review;
            $ig = str_replace(' ', '', trim($req->reviewImage));
            $title = $req->reviewTitle;


            if(empty($ig)) $ig = null;
            if (empty($pNum) || empty($review) || empty($title)) {
                $res->isSuccess = false;
                $res->code = 00;
                $res->message = "공백이 입력됐습니다";
                http_response_code(200);
            } else {
                $ck = checkPay($id, $pNum);
                if ($ck == 1) {
                    if($ig != null){
                        $imageValid = checkRemoteFile($ig);
                        if($imageValid == false){
                            $res->isSuccess = false;
                            $res->code = 4;
                            $res->message = "이미지 형식이 올바르지 않습니다";
                            http_response_code(200);
                            echo json_encode($res, JSON_NUMERIC_CHECK);
                            break;
                        }
                    }
                    $res->Date = putReview($id, $pNum, $review, $ig, $title);
                    $res->isSuccess = true;
                    $res->code = 700;
                    $res->message = "상품후기등록";
                    http_response_code(200);
                } else {
                    $res->isSuccess = false;
                    $res->code = 750;
                    $res->message = "이 상품에 대한 결제내역이 없거나 이미 등록된 리뷰가 있습니다";
                    http_response_code(200);
                }
            }
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
       }

    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
