<?php
class Table_c extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		// $this->load->model('trip');
		// $this->load->library('../modules/trips/controllers/table_page_lib');
		$this->load->library('table_page_lib');
	}

	public function index($table)
	{


		$erd_path = APPPATH.'modules/table_page/erd/erd/erd.json';
		$erd= file_get_contents($erd_path);
		$erd= json_decode($erd, true);

		$data["columns"]["all"] = $erd[$table]["fields"];
		$data["columns"]["visible"] = array();


		$data["overview"]["table"] = $table;
		$data["overview"]["rel_name"] = $table;
		$data["overview"]["rel_name_id"] = $data["overview"]["rel_name"];
		$data["data_endpoint"] = $table."/fetch";
		$data['title'] = $table;
		$this->load->view('table_header_v', $data);
		$this->load->view('table_block_v', $data);
		$this->load->view('table_footer_v');

	}

	public function insert($table)
	{
		$result = $this->table_page_lib->insert($table);
		header('Content-Type: application/json');
		echo json_encode($result, JSON_PRETTY_PRINT);
	}

	public function fetch($table)
	{
		$result = $this->table_page_lib->fetch($table);
		header('Content-Type: application/json');
		echo json_encode($result, JSON_PRETTY_PRINT);
	}

	public function fetch_where($table, $haystack, $needle)
	{
		$result = $this->table_page_lib->fetch_where($table, $haystack, $needle);
		header('Content-Type: application/json');
		echo json_encode($result, JSON_PRETTY_PRINT);
	}

	public function fetch_join_where($table_1, $table_2, $haystack, $needle)
	{
		$result = $this->table_page_lib->fetch_join_where($table_1, $table_2, $haystack, $needle);
		header('Content-Type: application/json');
		echo json_encode($result, JSON_PRETTY_PRINT);
	}

	public function delete($table)
	{
		$result = $this->table_page_lib->delete($table);
		header('Content-Type: application/json');
		echo json_encode($result, JSON_PRETTY_PRINT);
	}

	public function edit($table)
	{
		$result = $this->table_page_lib->edit($table);
		header('Content-Type: application/json');
		echo json_encode($result, JSON_PRETTY_PRINT);
	}

	public function update($table)
	{
		$result = $this->table_page_lib->update($table);
		header('Content-Type: application/json');
		echo json_encode($result, JSON_PRETTY_PRINT);
	}
}
