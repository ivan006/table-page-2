<?php
class Record_c extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		// $this->load->model('trip');
		// $this->load->library('../modules/trips/controllers/table_page_lib');
		$this->load->library('table_page_lib');
		$this->load->library('erd_lib');
	}

	public function index($table, $record_id)
	{
		$overview_table_singular = $this->erd_lib->grammar_singular($table);


		$header["title"] = $overview_table_singular." ".$record_id;
		$body = array();


		$record = $this->table_page_lib->fetch_where($table, "id", $record_id)["posts"][0];

		$haystack = "id";
		$needle = $record_id;
		$body["data_endpoint"] = "fetch_where/h/$haystack/n/$needle";
		$body["overview"]["type"] = "overview";
		$body["overview"]["rel_name"] = "overview";
		$body["overview"]["rel_name_id"] = $body["overview"]["rel_name"];
		$body["overview"]["table"] = $table;
		$dont_scan = "";

		$erd_path = APPPATH.'modules/table_page/erd/erd/erd.json';
		$erd= file_get_contents($erd_path);
		$erd= json_decode($erd, true);

		$body["columns"]["all"] = $erd[$table]["fields"];
		$body["columns"]["visible"] = array();


		$body["items"] = $this->relations($erd, $body["overview"], $record, $dont_scan);


		// header('Content-Type: application/json');
		// echo json_encode($body, JSON_PRETTY_PRINT);
		// exit;





		$this->load->view('table_header_v', $header);
		$this->load->view('table_block_v', $body);

		foreach ($body["items"] as $key => $value) {
			if (!empty($value)) {
				// code...
				$this->load->view('table_block_v', $value);
			}
		}
		$this->load->view('table_footer_v');

	}


	public function relations($erd, $parent_overview, $parent_record, $dont_scan)
	{
		$result = array();
		// $columns = $erd[$parent_overview["table"]]["fields"];
		if (isset($erd[$parent_overview["table"]]["items"])) {
			$items = $erd[$parent_overview["table"]]["items"];
			foreach ($items as $key => $value) {
				if ($key !== $dont_scan) {


					$overview["rel_name"] = $key." (".$value." specialised)";
					$overview["rel_name_id"] = preg_replace('/\W+/','',strtolower(strip_tags($overview["rel_name"])));
					$overview["table"] = $key;
					$overview["foreign_key"] = $value;

					// var_dump($parent_overview);

					if (!empty($parent_record)) {

						$haystack = $value;
						$needle = $parent_record["id"];

						$data_endpoint = "fetch_where/h/$haystack/n/$needle";

					} else {
						$data_endpoint = "";
					}
					$overview["type"] = "dedicated_items";






					$sub_columns = $erd[$key]["fields"];


					$result[$key] = array(
						"overview" => $overview,
						"columns" => array(
							"all" => $sub_columns,
							"visible" => array(),
						),
						"data_endpoint" => $data_endpoint,
					);



				} else {
					$result[$key] = array();
				}
			}
		}



		return $result;

	}



	public function mergetest()
	{
		$result = $this->table_page_lib->mergetest();
		header('Content-Type: application/json');
		echo json_encode($result, JSON_PRETTY_PRINT);
	}


}
