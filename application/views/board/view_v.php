<article id="board_area">
	<header>
		<h1></h1>
	</header>
	<table cellspacing="0" cellpadding="0" class="table table-striped"
		   style="max-width: 900px; margin-right: 10%; margin-left: 15%;">
		<thead>
		<tr>
			<th scope="col" style="font-size: 20px"> <?php echo $views->subject; ?></th>
			<th scope="col">글쓴이 : <?php echo $views->authors; ?></th>
			<th scope="col">보유권수 : <?php echo $views->quantity; ?></th>
			<th scope="col"> 등록일 : <?php echo $views->reg_date; ?></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<th colspan="4">
				<img src="<?= $views->thumbnail ?>" alt="<?php echo $views->subject; ?>"
					 style="float: left; margin-right: 25px"><?php echo $views->contents; ?>
				<a href="<?= $views->url ?>" style="position: relative; bottom: -50px; right: 5px;">자세히보기</a>
			</th>
		</tr>
		<tr>
			<th colspan="4">가격 : <?= $views->price ?></th>
		</tr>
		<tr>
			<th colspan="4">출판날짜 : <?= substr($views->datetime,0,10) ?></th>
		</tr>
		</tbody>
		<tfoot>
		<tr>
			<th colspan="4">
				<a href="<?= base_url('/CIproject/index.php/board/lists/');?><?php echo $this->uri->segment(3);?>"
				   class="btn btn-primary">목록</a>
				<a href="<?= base_url('/CIproject/index.php/board/modify/');?><?php echo $this->uri->segment(3);?>/<?php echo $this->uri->segment(4);?>"
				   class="btn btn-warning">수정</a>
				<a href="<?= base_url('/CIproject/index.php/board/delete/');?><?php echo $this->uri->segment(3);?>/<?php echo $this->uri->segment(4);?>"
				   class="btn btn-danger">삭제</a>
			</th>
		</tr>
		</tfoot>
	</table>
</article>
