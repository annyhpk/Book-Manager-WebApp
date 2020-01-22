<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Board extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('Board_m');
		$this->load->helper(array('url', 'date'));
	}

	public function index()
	{
		$this->lists();
	}
	public function _remap($method) {
		$this->load->view('board/header_v');
		if(method_exists($this, $method)) {
			$this->{"{$method}"}();
		}
		$this->load->view('board/footer_v');
	}
	public function lists(){
		if($_POST AND count($_POST) > 2) {
			$this->load->helper('alert');

			if (!$this->input->post('subject', TRUE) AND !$this->input->post('contents', TRUE)) {
				// 글 제목과 내용이 없을 경우 , 프로그램 단에서 한번 더 체크
				alert("비정상적인접근입니다. ", base_url('/CIproject/index.php/board/lists/') . $this->uri->segment(3));
				exit;
			}
			$write_data = array( //DB 에 넣을 값을 배열로 저장( DB 필드명 => 넣을 값)
				'table' => $this->uri->segment(3), // 게시판 테이블명
				'subject' => $this->input->post('subject', TRUE),
				'contents' => $this->input->post('contents', TRUE),
				'authors' => $this->input->post('authors', TRUE),
				'thumbnail' => $this->input->post('thumbnail', TRUE),
				'datetime' => $this->input->post('datetime', TRUE),
				'price' => $this->input->post('price', TRUE),
				'url' => $this->input->post('url', TRUE),
			);
			$result = $this->Board_m->insert_board($write_data); // board_m 모델의 insert_board() 함수에 위에서 만든 배열을 전달하여 데이터베이스에 입력하고 그 결과를 돌려받음 .

			if ($result) { // 모델에서 INSERT 가 성공하면 TRUE 가 반환됨
				// 글 작성 성공시 게시판 목록으로
				alert('입력되었습니다.', base_url('/CIproject/index.php/board/lists/') . $this->uri->segment(3));
				exit;
			} else { // 글 실패 시 게시판 목록으로
				alert('다시입력해 주세요.', base_url('/CIproject/index.php/board/lists/') . $this->uri->segment(3));
				exit;
			}
		}
		//$this->output->enable_profiler(TRUE);

		//검색어 초기화
		$search_word = $page_url = '';
		$uri_segment = 5;

		//주소중에서 q(검색어) 세그먼트가 있는지 검사하기 위해 주소를 배열로 변환
		$uri_array = $this->segment_explode($this->uri->uri_string());

		if( in_array('q', $uri_array) ) {
			//주소에 검색어가 있을 경우의 처리. 즉 검색시
			$search_word = urldecode($this->url_explode($uri_array, 'q'));
			//페이지네이션용 주소
			$page_url = '/q/'.$search_word;
			$uri_segment = 7;
		}

		//페이지네이션 라이브러리 로딩 추가
		$this->load->library('pagination');
		//페이지네이션 설정
		$config['base_url'] = '/CIproject/index.php/board/lists/ci_board/'.$page_url.'/page/'; //페이징 주소
		$config['total_rows'] = $this->Board_m->get_list($this->uri->segment(3), 'count', '', '', $search_word); //게시물의 전체 갯수
		$config['per_page'] = 5; //한 페이지에 표시할 게시물 수
		$config['uri_segment'] = $uri_segment; //페이지 번호가 위치한 세그먼트

		//원하는 설정값을 선언하고 페이지네이션 초기화
		$this->pagination->initialize($config);

		//페이징 링크를 생성하여 view에서 사용할 변수에 할당
		$data['pagination'] = $this->pagination->create_links();

		/*$page = $this->uri->segment(5, 1); // 5번째 세그먼트가 없으면, $page=1이 됨 */
		$page = $this->uri->segment($uri_segment, 1);

		if ( $page > 1 ) {
			$start = (($page/$config['per_page'])) * $config['per_page'];
		} else {
			$start = ($page-1) * $config['per_page'];
		}

		$limit = $config['per_page'];

		$data['list'] = $this->Board_m->get_list($this->uri->segment(3), '', $start, $limit, $search_word);
		$this->load->view('board/list_v', $data);
	}

	function view() { // 게시판 이름과 게시물 번호에 해당하는 게시물 가져오기
		$data['views'] = $this->Board_m->get_view($this->uri->segment(3), $this->uri->segment(4));
		//view 호출
		$this->load->view('board/view_v', $data);
	}

	function modify() {
		echo '<meta charset=utf-8" />';
		if ($_POST) { // 경고창 헬퍼 로딩
			$this->load->helper('alert');

			 // 주소중에서 page 세그먼트가 있는지 검사하기 위해 주소를 배열로 변환
			$uri_array = $this->segment_explode($this->uri->uri_string());

			if( in_array('page', $uri_array) ) {
				$pages = urldecode($this->url_explode($uri_array, 'page'));
			} else {
				$pages = 1;
			}

			if (!$this->input->post('subject', TRUE) AND !$this->input->post('contents', TRUE)) {
				// 글 내용이 없을 경우 , 프로그램단에서 한번 더 체크
				alert('비정상적인 접근입니다.', base_url('/CIproject/index.php/board/lists/') . $this->uri->segment(3));
				exit;
			}


			//var_dump($_POST);
			$modify_data = array( 'table' => $this->uri->segment(3), // 게시판 테이블명
				'board_id' => $this->uri->segment(4), // 게시물번호
				'subject' => $this->input->post('subject', TRUE),
				'contents' => $this->input->post('contents', TRUE),
				'authors' => $this->input->post('authors', TRUE),
				'quantity' => $this->input->post('quantity', TRUE),
				'datetime' => $this->input->post('datetime', TRUE),
				'price' => $this->input->post('price', TRUE)
			);

			$result = $this->Board_m->modify_board($modify_data);

			if ( $result ) {   // 글 작성 성공시 게시판 목록으로
				alert('수정되었습니다.', base_url('/CIproject/index.php/board/lists/').$this->uri->segment(3));
				exit;
			} else {  // 글 수정 실패시 글 내용으로
				alert('다시 수정해 주세요.', base_url('/CIproject/index.php/board/view/').$this->uri->segment(3).$this->uri->segment(4));
				exit;
			}
		} else { // 게시물 내용 가져오기
			$data['views'] = $this->Board_m->get_view($this->uri->segment(3), $this->uri->segment(4));
			// 쓰기폼 view 호출
			$this->load->view('board/modify_v', $data);
		}
	}

	function delete() {
		echo '<meta charset=utf-8" />';
		// 경고창 헬퍼 로딩
		$this->load->helper('alert');
		// 게시물 번호에 해당하는 게시물 삭제
		$return = $this->Board_m->delete_content($this->uri->segment(3), $this->uri->segment(4));
		// 게시물 목록으로 돌아가기
		if ( $return ) { // 삭제가 성공한 경우
			alert('삭제되었습니다.', base_url('/CIproject/index.php/board/lists/').$this->uri->segment(3));
		} else { // 삭제가 실패한 경우
			alert('삭제 실패하였습니다.', base_url('/CIproject/index.php/board/view/').$this->uri->segment(3).$this->uri->segment(4));
		}
	}

	/** url중 키값을 구분하여 값을 가져오도록.
	 * @param Array $url : segment_explode한 url값
	 * @param String $key : 가져오려는 값의 key
	 * @return String $url[$k] : 리턴값 */
	function url_explode($url, $key)
	{
		$cnt = count($url);
		for($i=0; $cnt>$i; $i++ )
		{
			if($url[$i] == $key)
			{
				$k = $i+1;
				return $url[$k];
			}
		}
	}
	/*HTTP의 URL을 "/"를 Delimiter로 사용하여 배열로 바꾸어 리턴한다.
	* @param string 대상이 되는 문자열
	* @return string[] */
	function segment_explode($seg)
	{
		//세크먼트 앞뒤 '/' 제거후 uri를 배열로 반환
		$len = strlen($seg);
		if (substr($seg, 0, 1) == '/') {
			$seg = substr($seg, 1, $len);
		}
		$len = strlen($seg);
		if (substr($seg, -1) == '/') {
			$seg = substr($seg, 0, $len - 1);
		}
		$seg_exp = explode("/", $seg);
		return $seg_exp;
	}
}
