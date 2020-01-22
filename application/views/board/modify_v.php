<script>
	$(document).ready(function(){
	    $("#write_btn").click(function(){
	        if($("#input01").val() == ''){
	            alert('제목을 입력해주세요.');
	            $("#input01").focus();
	            return false;
	        } else if($("#input02").val() == ''){
	            alert('내용을 입력해주세요.'); $("#input02").focus();
	            return false;
	        } else {
	            $("#write_action").submit();
	        }
	    });
	});
</script>

<article id="board_area">
	<header>
		<h1></h1>
	</header>
	<form class="form-horizontal" method="post" action="" id="write_action">
		<fieldset>
			<legend style="text-align: center">도서 정보 수정</legend>
			<div class="control-group">
				<table cellspacing="0" cellpadding="0" class="table table-striped"
					   style="max-width: 900px; margin-right: 10%; margin-left: 15%;">
					<thead>
					<tr>
						<th scope="col">
							도서명 : <input type="text" class="form-control col-md-12"
								   			name="subject" value="<?= $views->subject; ?>">
						</th>
						<th scope="col">글쓴이 : <input type="text" class="form-control col-md-9"
													 name="authors" value="<?= $views->authors; ?>"></th>
						<th scope="col">보유권수 : <input type="number" class="form-control col-md-4"
													  name="quantity" value="<?= $views->quantity; ?>"></th>
						<th scope="col"> 등록일 : <?php echo $views->reg_date; ?></th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<th colspan="4">
							<img src="<?= $views->thumbnail ?>" alt="<?= $views->subject; ?>"
								 style="float: left; margin-right: 25px">
							- 도서 내용 -
							<textarea name="contents" style="width: 80%;" rows="6"><?= $views->contents; ?></textarea>
						</th>
					</tr>
					<tr>
						<th colspan="4">가격 : <input type="number" class="form-control col-md-4"
													name="price" value="<?= $views->price; ?>"></th>
					</tr>
					<tr>
						<th colspan="4">출판날짜 : <input type="text" class="form-control col-md-4"
													  name="datetime" value="<?= substr($views->datetime,0,10); ?>"></th>
					</tr>
					</tbody>
					<tfoot>
					<tr>
						<th colspan="4" style="text-align: center">
							<button type="submit" class="btn btn-primary" id="write_btn">수정</button>
							<button type="button" class="btn btn-light"
									onclick="location.href='<?= base_url('/CIproject/index.php/board/lists/ci_board'); ?>'">
								취소</button>
						</th>
					</tr>
					</tfoot>
				</table>

			</div>
		</fieldset>
	</form>
</article>
