<?php
    include "./inc/phpinclude.php";

    // $result = $mysqli->query("select * from board") or die("query error => ".$mysqli->error);
    // $result = $mysqli->query("select * from board where status=1 order by bid desc") or die("query error => ".$mysqli->error);
    $search_where = "";
    $search_keyword = "";
    
    if(isset($_GET['search_keyword'])){
        $search_keyword = $_GET['search_keyword'];
        $search_where = " and (subject like '%".$search_keyword."%' or content like '%".$search_keyword."%')";
    }

    $pageNumber  = $_GET['pageNumber']??1;//현재 페이지, 없으면 1
    if($pageNumber < 1) $pageNumber = 1;
    $pageCount  = $_GET['pageCount']??10;//페이지당 몇개씩 보여줄지, 없으면 10
    $startLimit = ($pageNumber-1)*$pageCount;//쿼리의 limit 시작 부분
    $firstPageNumber  = $_GET['firstPageNumber']??1;

    // $sql = "select * from board where 1=1";
    $sql = "select b.*, if((now() - regdate)<=86400,1,0) as newid from board b where 1=1";
    $sql .= " and status=1";
    $sql .= $search_where;
    $order = " order by ifnull(parent_id, bid) desc, bid asc";
    $limit = " limit $startLimit, $pageCount";
    $query = $sql.$order.$limit;
    //echo "query=>".$query."<br>";
    $result = $mysqli->query($query) or die("query error => ".$mysqli->error);
    $rsc = null;

    // echo "<pre>";
    while($rs = $result->fetch_object()){
        $rsc[]=$rs;
        // print_r($rsc);
    }

    // echo "<pre>";
    // print_r($rsc);

    //전체게시물 수 구하기
    $sqlcnt = "select count(*) as cnt from board where 1=1";
    $sqlcnt .= " and status=1";
    $sqlcnt .= $search_where;
    $countresult = $mysqli->query($sqlcnt) or die("query error => ".$mysqli->error);
    $rscnt = $countresult->fetch_object();
    $totalCount = $rscnt->cnt;//전체 게시물 갯수를 구한다.
    $totalPage = ceil($totalCount/$pageCount);//전체 페이지를 구한다.

    if($firstPageNumber < 1) $firstPageNumber = 1;
    $lastPageNumber = $firstPageNumber + $pageCount - 1;//페이징 나오는 부분에서 레인지를 정한다.
    if($lastPageNumber > $totalPage) $lastPageNumber = $totalPage;

    if($firstPageNumber > $totalPage) {
        echo "<script>alert('더 이상 페이지가 없습니다.');history.back();</script>";
        exit;
    }

?>
<!doctype html>
<html lang="ko">
  <head>
    <?php
        include "./inc/scriptinclude.php";
    ?>

    <title>PHP-BBS-EXAMPLE EX1</title>
  </head>
  <body>
        <input type="hidden" name="nextPageNumber" id="nextPageNumber" value="<?php echo $pageNumber+1;?>">
        <table class="table" style="width:70%;margin:auto;">
        <thead>
            <tr>
            <th scope="col">번호</th>
            <th scope="col">글쓴이</th>
            <th scope="col">제목</th>
            <th scope="col">등록일</th>
            </tr>
        </thead>
        <tbody id="board_list">
            <?php 
                if(!empty($rsc)){
                    $idNumber = $totalCount - ($pageNumber-1)*$pageCount;
                    foreach($rsc as $r){
                        //검색어만 하이라이트 해준다.
                        $subject = str_replace($search_keyword,"<span style='color:red;'>".$search_keyword."</span>",$r->subject);
            ?>
            <tr>
            <th scope="row"><?php echo $idNumber--;?></th>
            <td><?php echo $r->userid?></td>
            <td>
                <?php
                    if($r->parent_id){
                    echo "&nbsp;&nbsp;";
                    }
                ?>
                <a href="./view.php?bid=<?php echo $r->bid;?>"><?php echo $r->subject;?></a>
                <?php if($r->newid){?>
                    <span class="badge bg-danger">New</span>
                <?php }?>
            </td>
            <td><?php echo $r->regdate;?></td>
            </tr>
            <?php }
                }
            ?>
        </tbody>
        </table>
        <div class="d-grid gap-2" style="margin:20px;">
            <button class="btn btn-secondary" type="button" id="more_button">더보기</button>
        </div>
        <form method="get" action="<?php echo $_SERVER["PHP_SELF"]?>">
            <div class="input-group mb-12" style="margin:auto;width:50%;">
                <input type="text" class="form-control" name="search_keyword" id="search_keyword" placeholder="제목과 내용에서 검색합니다." value="<?php echo $search_keyword;?>" aria-label="Recipient's username" aria-describedby="button-addon2">
                <button class="btn btn-outline-secondary" type="button" id="button-addon2">검색</button>
            </div>
        </form>

        <!-- <p>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item">
                        <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']?>?pageNumber=<?php echo $firstPageNumber-$pageCount;?>&firstPageNumber=<?php echo $firstPageNumber-$pageCount;?>&search_keyword=<?php echo $search_keyword;?>">Previous</a>
                    </li>
                    <?php
                        for($i=$firstPageNumber;$i<=$lastPageNumber;$i++){
                    ?>
                        <li class="page-item <?php if($pageNumber==$i){echo "active";}?>"><a class="page-link" href="<?php echo $_SERVER['PHP_SELF']?>?pageNumber=<?php echo $i;?>&firstPageNumber=<?php echo $firstPageNumber;?>&search_keyword=<?php echo $search_keyword;?>"><?php echo $i;?></a></li>
                    <?php
                        }
                    ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']?>?pageNumber=<?php echo $firstPageNumber+$pageCount;?>&firstPageNumber=<?php echo $firstPageNumber+$pageCount;?>&search_keyword=<?php echo $search_keyword;?>">Next</a>
                    </li>
                </ul>
            </nav>
        </p> -->

        <p style="text-align:right;">
            <?php
                if(isset($_SESSION['UID'])){
            ?>
                <a href="write.php"><button type="button" class="btn btn-primary">등록</button><a>
                <a href="./member/logout.php"><button type="button" class="btn btn-primary">로그아웃</button><a>
            <?php
                }else{
            ?>
                <a href="./member/login.php"><button type="button" class="btn btn-primary">로그인</button><a>
                <a href="./member/signup.php"><button type="button" class="btn btn-primary">회원가입</button><a>
            <?php
                }
            ?>
        </p>

        <script>
            $("#more_button").click(function () {
            
                var data = { //more_list_page.php에 넘겨주는 파라미터 값이다.
                    pageNumber : $('#nextPageNumber').val() ,
                    pageCount : <?php echo $pageCount;?>,
                    totalCount : <?php echo $totalCount;?>,
                    search_keyword : '<?php echo $search_keyword;?>'
                };
                    $.ajax({
                        async : false ,
                        type : 'post' ,//post방식으로 넘겨준다. ajax는 반드시 post로 해준다.
                        url : 'more_list_page.php' ,
                        data  : data ,//위에서 만든 파라미터들을 넘겨준다.
                        dataType : 'html' ,//리턴받을 형식이다. html말고 text난 json도 있다. json을 가장 많이 쓴다.
                        error : function() {console.log("error");} ,
                        success : function(return_data) {
                            console.log("success");
                            if(return_data==false){
                                alert('마지막 페이지입니다.');
                                return;
                            }else{
                                $("#board_list").append(return_data);//table 마지막에 붙여준다. 반대는 prepend가 있다.
                                $("#nextPageNumber").val(parseInt($('#nextPageNumber').val())+1);//다음페이지를 위해 1씩 증가해준다.
                            }
                        }
                });
                return false;
            });
        </script>

  </body>
</html>