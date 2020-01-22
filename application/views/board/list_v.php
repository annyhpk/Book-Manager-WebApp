<script>
    $(document).ready(function(){
        $("#search_btn").click(function(){
            if($("#q").val() == ''){
                alert('검색어를 입력해주세요.');
                return false;
            } else { // 검색어를 포함한 주소를 만들어서 POST 전송
                var act = '/CIproject/index.php/board/lists/ci_board/q/'+$("#q").val()+'/page/1';
                $("#bd_search").attr('action', act).submit();
            }
        });
    });
    //검색어 입력후 엔터키를 입력하면 검색 버튼을 누른 것과 동일한 효과를 내도록 함
    function board_search_enter(form) {
        var keycode = window.event.keyCode;
        if(keycode == 13) $("#search_btn").click();
    }
</script>

<article id="board_area" style="padding-left: 12%; padding-right: 12%;">
	<header>
		<h1></h1>
	</header>
	<table cellspacing="0" cellpadding="0" class="table table-striped">
	<thead>
	<tr>
		<th scope="col">번호</th>
		<th scope="col">책제목</th>
		<th scope="col">글쓴이</th>
		<th scope="col">보유권수</th>
		<th scope="col">등록일</th>
	</tr>
	</thead>
		<tbody>
		<?php
		date_default_timezone_set('Asia/Seoul');  //또는php.ini에서 date.timezone=Asia/Seoul 로 설정
		foreach($list as $lt) {
		?>
		<tr> <th scope="row">
				<?php echo $lt->board_id; ?>
			</th>
			<td><a rel="external" href="<?php echo base_url('/CIproject/index.php/');?><?php echo $this->uri->segment(1); ?>/view/<?php echo $this->uri->segment(3); ?>/<?php echo $lt->board_id; ?>">
					<?php echo $lt->subject; ?>
				</a>
			</td>
			<td><?php echo $lt->authors; ?></td>
			<td><?php echo $lt->quantity; ?></td>
			<td>
				<time datetime="<?php echo mdate("%Y-%M-%j", human_to_unix($lt->reg_date)); ?>">
					<?php echo mdate("%M. %j, %Y", human_to_unix($lt->reg_date)); ?>
				</time>
			</td>
		</tr> <?php } ?>
		</tbody>
		<tfoot>
		<tr>
			<td colspan="5" align="center"><?php echo $pagination;?></td>
		</tr>
		</tfoot>
	</table>

	<div style="display: flex; justify-content: center; align-content: center;">
		<form id="bd_search" method="post" style="margin-right: 10px; margin-top: 5px; display: flex;">
			<input type="text" name="search_word" id="q" class="form-control col-md-12"
				   onkeypress="board_search_enter(document.q);" style="max-width: 300px"
				   placeholder="검색어를 입력하세요"/>
			<input type="button" value="검색" id="search_btn" class="btn btn-primary" />
		</form>

		<div class="search-box col-md-7" style="position: absolute; right: 100px; top: 35px;">
			<div class="input-group mb-6" style="padding-left: 70px">
				<div class="input-group-append">
					<button class="btn btn-outline-secondary" type="button" id="button-search"
							data-toggle="modal" style="color: #1a1a1a"
							data-target=".bd-example-modal-xl" disabled>도서추가</button>
				</div>
				<form method="get" action="<?= $_SERVER['PHP_SELF'] ?>">
					<input type="text" id="book-search-input" class="form-control col-md-4" placeholder="도서명"
						   aria-label="book-title"
						   aria-describedby="button-addon2"
						   style="max-width: 300px" name="book-search-input">
					<input type="submit" id="submit-search" style="visibility: hidden; display: none;">
				</form>
			</div>
		</div>
	</div>

	<?php
	function request($path, $query, $content_type = 'json')
	{
		$api_server = 'https://dapi.kakao.com';
		$headers = array('Authorization: KakaoAK YOUR KAKAO API KEY'); // API KEY
		$opts = array(
			CURLOPT_URL => $api_server . $path . '.' . $content_type . '?' . $query,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSLVERSION => 1,
			CURLOPT_HEADER => false,
			CURLOPT_HTTPHEADER => $headers
		);

		$curl_session = curl_init();
		curl_setopt_array($curl_session, $opts);
		$return_data = curl_exec($curl_session);

		if (curl_errno($curl_session)) {
			throw new Exception(curl_error($curl_session));
		} else {
			curl_close($curl_session);
			return $return_data;
		}
	}
	if($_GET) {
		$search_word = $_GET['book-search-input'];
	} else {
		$search_word = ' ';
	}
	$path = '/v3/search/book';
	$content_type = 'json'; // json or xml
	$params = http_build_query(array(
		'page' => 1,
		'size' => 20,
		'query' => $search_word
	));


	$res = request($path, $params, $content_type);

	$dt = json_decode($res); //json data type => php object로 변환
	//json_encode : php object/array => json data type으로 변환

	$tCount = $dt->meta->total_count;
	$iCnt = count($dt->documents);
	?>

	<div class="modal fade bd-example-modal-xl" id="search-modal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalScrollableTitle">도서 검색</h5>
					<div style="margin-top: 6px; margin-bottom: 0; margin-left: 20px;"><?php
						echo "총 검색수:".$tCount . " / " ."표기 권수:".$iCnt;
						?></div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<?php

				for ($i=0; $i < $iCnt; $i++) {
				if ($dt->documents[$i]->contents) {
				?>
				<div class="card mb-3" style="max-width: 740px; max-height: 200px; margin-right: 10%; margin-left: 20%;"
					 ondblclick="bookinfo_submit(<?=$i?>);">
					<form style="display: none;" method="post" action="" >
						<input type="text" name="subject" value="<?= $dt->documents[$i]->title; ?>" >
						<textarea name="contents"><?= $dt->documents[$i]->contents; ?></textarea>
						<input type="text" name="authors" value="<?= $dt->documents[$i]->authors[0]; ?>" >
						<input type="text" name="thumbnail" value="<?= $dt->documents[$i]->thumbnail; ?>" >
						<input type="text" name="datetime" value="<?= $dt->documents[$i]->datetime; ?>" >
						<input type="text" name="price" value="<?= $dt->documents[$i]->price; ?>" >
						<input type="text" name="url" value="<?= $dt->documents[$i]->url; ?>" >
						<button type="submit" id="<?="book".$i;?>"></button>
					</form>
					<div class="row no-gutters">
						<div class="col-md-2">
							<img src='<?= $dt->documents[$i]->thumbnail; ?>' class="card-img"
								 alt='<?= $dt->documents[$i]->title; ?>'>
						</div>
						<div class="col-md-8">
							<div class="card-body">
								<h5 class="card-title"><?= $dt->documents[$i]->title; ?></h5>
								<div style="max-height: 45px; overflow: hidden"><p class="card-text">내용
										: <?= $dt->documents[$i]->contents; ?></p></div>
								<p class="card-text"><small class="text-muted">isbn
										: <?= $dt->documents[$i]->isbn; ?></small></p>
								<p>
									<?php
									$iCntAuthor = 0;
									$iCntAuthor = count($dt->documents[$i]->authors);

									for ($z = 0; $z < $iCntAuthor; $z++) {
										if ($z == 0) {
											echo "글쓴이 : ";
										}
										echo $dt->documents[$i]->authors[$z];
										if ($z <> ($iCntAuthor - 1)) {
											echo ",";
										};
									}

									echo "</p>";
									echo "</div>";
									echo "</div>";
									echo "</div>";
									echo "</div>";
									}
									}
									?>

								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
								</div>
							</div>
						</div>
					</div>
</article>

<script>
    <?php if($_GET) { ?>
		$('#search-modal').modal();
	<?php } ?>

	function bookinfo_submit(i) {
		document.querySelector('#book'+i).click();
    }
</script>

