<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Board_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	function get_list($table='ci_board', $type='', $offset='', $limit='', $search_word='')
	{
		$sword='';
		if(empty($table)){
			$table = 'ci_board';
		}

		if ($search_word != ''){
			// 검색어가 있을 경우의 처리
			$sword = ' WHERE subject like "%'.$search_word.'%" or contents like "%'.$search_word.'%"';
		}

		$limit_query = '';

		if ( $limit != '' OR $offset != '' ) {
		//페이징이 있을 경우의 처리
			$limit_query = ' LIMIT '.$offset.', '.$limit;
		}

		$sql = "SELECT * FROM ".$table.$sword." ORDER BY board_id DESC".$limit_query;
		$query = $this->db->query($sql);

		if ( $type == 'count' ) {
			//리스트를 반환하는 것이 아니라 전체 게시물의 갯수를 반환
			$result = $query->num_rows();
			//$this->db->count_all($table);
		} else {
			//게시물 리스트 반환
			$result = $query->result();
		}

		return $result;
	}

	function get_view($table, $id) {

		$sql = "SELECT * FROM {$table} WHERE board_id='{$id}'";
		$query = $this->db->query($sql);

		// 게시물 내용 반환
		$result = $query->row(); // 한 개의 결과물만 가져옴 // mysqli_fetch_rows()와 동일한 실행결과

		return $result;
	}

	function insert_board($arrays) {
		$insert_array = array(
			'board_pid' => 0, // 원글이라 0 을 입력 , 댓글일 경우 원글번호 입력
			'admin_id' => 'admin',  // 로그인처리한 후에는 로그인한 아이디
			'authors' => $arrays['authors'],
			'subject' => $arrays['subject'],
			'contents' => $arrays['contents'],
			'thumbnail' => $arrays['thumbnail'],
			'datetime' => $arrays['datetime'],
			'price' => $arrays['price'],
			'url' => $arrays['url'],
			'quantity' => 0,
			'reg_date' => date("Y-m-d H:i:s")
		);

		$result = $this->db->insert($arrays['table'], $insert_array);

		// 결과 반환
		return $result;
	}

	function modify_board($arrays) {
		$modify_array = array(
			'subject' => $arrays['subject'],
			'contents' => $arrays['contents'],
			'authors' => $arrays['authors'],
			'datetime' => $arrays['datetime'],
			'price' => $arrays['price'],
			'quantity' => $arrays['quantity'],
		);

		$where = array( 'board_id' => $arrays['board_id'] );

		$result = $this->db->update($arrays['table'], $modify_array, $where);
		// 결과 반환
		return $result;
	}

	function delete_content($table, $no) {
		$delete_array = array( 'board_id' => $no );

		$result = $this->db->delete($table, $delete_array);
		// 결과 반환
		return $result;
	}
}
