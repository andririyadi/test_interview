<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// memanggil REST Controller yang sudah mewariskan CI Controller
require APPPATH.'/libraries/REST_Controller.php';

class Api extends REST_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
	}

	public function package_get($id='')
	{
		if ($id != '') {
			$this->db->where('transaction_id', $id);
		}

		$query = $this->db->get('transaction');
		$result = $query->result();
		$this->response($result, 200);
	}

	public function package_post()
	{
		// mengambil raw data dan konversi menjadi array
		$rawdata = json_decode($this->input->raw_input_stream, true);

		// mendefisinikan data sesuai dengan kolom yang ada di table transaction
		$transaction_data = array(
			'transaction_id' => UUID::v4(),
			'customer_name' => $this->_ifempty($rawdata, 'customer_name', NULL),
			'customer_code' => $this->_ifempty($rawdata, 'customer_code', NULL),
			'transaction_amount' => $this->_ifempty($rawdata, 'transaction_amount', 0),
			'transaction_discount' => $this->_ifempty($rawdata, 'transaction_discount', 0),
			'transaction_additional_field' => $this->_ifempty($rawdata, 'transaction_additional_field', NULL),
			'transaction_payment_type' => $this->_ifempty($rawdata, 'transaction_payment_type', NULL),
			'transaction_state' => $this->_ifempty($rawdata, 'transaction_state', NULL),
			'transaction_code' => $this->_ifempty($rawdata, 'transaction_code', NULL),
			'transaction_order' => $this->_ifempty($rawdata, 'transaction_order', NULL),
			'location_id' => $this->_ifempty($rawdata, 'location_id', NULL),
			'organization_id' => $this->_ifempty($rawdata, 'organization_id', NULL),
			'created_at' => $this->_ifempty($rawdata, 'created_at', NULL),
			'updated_at' => $this->_ifempty($rawdata, 'updated_at', NULL),
			'transaction_payment_type_name' => $this->_ifempty($rawdata, 'transaction_payment_type_name', NULL),
			'transaction_cash_amount' => $this->_ifempty($rawdata, 'transaction_cash_amount', 0),
			'transaction_cash_change' => $this->_ifempty($rawdata, 'transaction_cash_change', 0),
			'customer_attribute' => json_encode($this->_ifempty($rawdata, 'customer_attribute', NULL)),
			'connote' => json_encode($this->_ifempty($rawdata, 'connote', NULL)),
			'connote_id' => $this->_ifempty($rawdata, 'connote_id', NULL),
			'origin_data' => json_encode($this->_ifempty($rawdata, 'origin_data', NULL)),
			'destination_data' => json_encode($this->_ifempty($rawdata, 'destination_data', NULL)),
			'koli_data' => json_encode($this->_ifempty($rawdata, 'koli_data', NULL)),
			'custom_field' => json_encode($this->_ifempty($rawdata, 'custom_field', NULL)),
			'currentLocation' => json_encode($this->_ifempty($rawdata, 'currentLocation', NULL))
		);

		// validasi data transaction
		$this->form_validation->set_data($transaction_data);
		$this->form_validation->set_rules('transaction_id', 'transaction_id', 'trim|max_length[36]|required');
		$this->form_validation->set_rules('customer_name', 'customer_name', 'trim|max_length[255]|required');
		$this->form_validation->set_rules('customer_code', 'customer_code', 'trim|max_length[50]|required');
		$this->form_validation->set_rules('transaction_amount', 'transaction_amount', 'trim|max_length[20]');
		$this->form_validation->set_rules('transaction_discount', 'transaction_discount', 'trim|max_length[20]');
		$this->form_validation->set_rules('transaction_additional_field', 'transaction_additional_field', 'trim');
		$this->form_validation->set_rules('transaction_payment_type', 'transaction_payment_type', 'trim|max_length[20]');
		$this->form_validation->set_rules('transaction_state', 'transaction_state', 'trim|max_length[100]|required');
		$this->form_validation->set_rules('transaction_code', 'transaction_code', 'trim|max_length[50]|required');
		$this->form_validation->set_rules('transaction_order', 'transaction_order', 'trim|max_length[20]|required');
		$this->form_validation->set_rules('location_id', 'location_id', 'trim|max_length[36]|required');
		$this->form_validation->set_rules('organization_id', 'organization_id', 'trim|max_length[20]|required');
		$this->form_validation->set_rules('created_at', 'created_at', 'trim');
		$this->form_validation->set_rules('updated_at', 'updated_at', 'trim');
		$this->form_validation->set_rules('transaction_payment_type_name', 'transaction_payment_type_name', 'trim|max_length[100]|required');
		$this->form_validation->set_rules('transaction_cash_amount', 'transaction_cash_amount', 'trim|max_length[20]');
		$this->form_validation->set_rules('transaction_cash_change', 'transaction_cash_change', 'trim|max_length[20]');
		$this->form_validation->set_rules('customer_attribute', 'customer_attribute', 'trim');
		$this->form_validation->set_rules('connote', 'connote', 'trim');
		$this->form_validation->set_rules('connote_id', 'connote_id', 'trim|max_length[36]|required');
		$this->form_validation->set_rules('origin_data', 'origin_data', 'trim');
		$this->form_validation->set_rules('destination_data', 'destination_data', 'trim');
		$this->form_validation->set_rules('koli_data', 'koli_data', 'trim');
		$this->form_validation->set_rules('custom_field', 'custom_field', 'trim');
		$this->form_validation->set_rules('currentLocation', 'currentLocation', 'trim');
		$transaction_valid = $this->form_validation->run();
		$this->form_validation->reset_validation();

		// mendefisinikan data sesuai dengan kolom yang ada di table connote
		$connote_data = array(
			'connote_id' => UUID::v4(),
			'connote_number' => $this->_ifempty($rawdata['connote'], 'connote_number', NULL),
			'connote_service' => $this->_ifempty($rawdata['connote'], 'connote_service', NULL),
			'connote_service_price' => $this->_ifempty($rawdata['connote'], 'connote_service_price', 0),
			'connote_amount' => $this->_ifempty($rawdata['connote'], 'connote_amount', 0),
			'connote_code' => $this->_ifempty($rawdata['connote'], 'connote_code', NULL),
			'connote_booking_code' => $this->_ifempty($rawdata['connote'], 'connote_booking_code', NULL),
			'connote_order' => $this->_ifempty($rawdata['connote'], 'connote_order', 0),
			'connote_state' => $this->_ifempty($rawdata['connote'], 'connote_state', NULL),
			'connote_state_id' => $this->_ifempty($rawdata['connote'], 'connote_state_id', 0),
			'zone_code_from' => $this->_ifempty($rawdata['connote'], 'zone_code_from', NULL),
			'zone_code_to' => $this->_ifempty($rawdata['connote'], 'zone_code_to', NULL),
			'surcharge_amount' => $this->_ifempty($rawdata['connote'], 'surcharge_amount', NULL),
			'transaction_id' => $this->_ifempty($transaction_data, 'transaction_id', NULL),
			'actual_weight' => $this->_ifempty($rawdata['connote'], 'actual_weight', 0),
			'volume_weight' => $this->_ifempty($rawdata['connote'], 'volume_weight', 0),
			'chargeable_weight' => $this->_ifempty($rawdata['connote'], 'chargeable_weight', 0),
			'created_at' => $this->_ifempty($rawdata['connote'], 'created_at', NULL),
			'updated_at' => $this->_ifempty($rawdata['connote'], 'updated_at', NULL),
			'organization_id' => $this->_ifempty($rawdata['connote'], 'organization_id', 0),
			'location_id' => $this->_ifempty($rawdata['connote'], 'location_id', NULL),
			'connote_total_package' => $this->_ifempty($rawdata['connote'], 'connote_total_package', 0),
			'connote_surcharge_amount' => $this->_ifempty($rawdata['connote'], 'connote_surcharge_amount', 0),
			'connote_sla_day' => $this->_ifempty($rawdata['connote'], 'connote_sla_day', 0),
			'location_name' => $this->_ifempty($rawdata['connote'], 'location_name', NULL),
			'location_type' => $this->_ifempty($rawdata['connote'], 'location_type', NULL),
			'source_tariff_db' => $this->_ifempty($rawdata['connote'], 'source_tariff_db', NULL),
			'id_source_tariff' => $this->_ifempty($rawdata['connote'], 'id_source_tariff', NULL),
			'pod' => $this->_ifempty($rawdata['connote'], 'pod', NULL),
			'history' => $this->_ifempty($rawdata['connote'], 'history', NULL),
		);

		// validasi data connote
		$this->form_validation->set_data($connote_data);
		$this->form_validation->set_rules('connote_id', 'connote_id', 'trim|max_length[36]');
		$this->form_validation->set_rules('connote_number', 'connote_number', 'trim|max_length[11]');
		$this->form_validation->set_rules('connote_service', 'connote_service', 'trim|max_length[100]');
		$this->form_validation->set_rules('connote_service_price', 'connote_service_price', 'trim|max_length[20]');
		$this->form_validation->set_rules('connote_amount', 'connote_amount', 'trim|max_length[20]');
		$this->form_validation->set_rules('connote_code', 'connote_code', 'trim|max_length[50]');
		$this->form_validation->set_rules('connote_booking_code', 'connote_booking_code', 'trim|max_length[50]');
		$this->form_validation->set_rules('connote_order', 'connote_order', 'trim|max_length[11]');
		$this->form_validation->set_rules('connote_state', 'connote_state', 'trim|max_length[50]');
		$this->form_validation->set_rules('connote_state_id', 'connote_state_id', 'trim|max_length[11]');
		$this->form_validation->set_rules('zone_code_from', 'zone_code_from', 'trim|max_length[50]');
		$this->form_validation->set_rules('zone_code_to', 'zone_code_to', 'trim|max_length[50]');
		$this->form_validation->set_rules('surcharge_amount', 'surcharge_amount', 'trim|max_length[20]');
		$this->form_validation->set_rules('transaction_id', 'transaction_id', 'trim|max_length[36]');
		$this->form_validation->set_rules('actual_weight', 'actual_weight', 'trim|max_length[10]');
		$this->form_validation->set_rules('volume_weight', 'volume_weight', 'trim|max_length[10]');
		$this->form_validation->set_rules('chargeable_weight', 'chargeable_weight', 'trim|max_length[10]');
		$this->form_validation->set_rules('created_at', 'created_at', 'trim');
		$this->form_validation->set_rules('updated_at', 'updated_at', 'trim');
		$this->form_validation->set_rules('organization_id', 'organization_id', 'trim|max_length[11]');
		$this->form_validation->set_rules('location_id', 'location_id', 'trim|max_length[36]');
		$this->form_validation->set_rules('connote_total_package', 'connote_total_package', 'trim|max_length[20]');
		$this->form_validation->set_rules('connote_surcharge_amount', 'connote_surcharge_amount', 'trim|max_length[20]');
		$this->form_validation->set_rules('connote_sla_day', 'connote_sla_day', 'trim|max_length[20]');
		$this->form_validation->set_rules('location_name', 'location_name', 'trim|max_length[100]');
		$this->form_validation->set_rules('location_type', 'location_type', 'trim|max_length[100]');
		$this->form_validation->set_rules('source_tariff_db', 'source_tariff_db', 'trim|max_length[100]');
		$this->form_validation->set_rules('id_source_tariff', 'id_source_tariff', 'trim|max_length[50]');
		$this->form_validation->set_rules('pod', 'pod', 'trim');
		$this->form_validation->set_rules('history', 'history', 'trim');
		$connote_valid = $this->form_validation->run();
		$this->form_validation->reset_validation();

		if ($transaction_valid && $connote_valid) {
			$this->db->trans_begin();
			$this->db->insert('transaction', $transaction_data);
			$this->db->insert('connote', $connote_data);

			if ($this->db->trans_status()) {
				$this->db->trans_commit();
				$this->response(array('status' => 'success'), 200);
	        } else {
				$this->db->trans_rollback();
	            $this->response(array('status' => 'fail'), 502);
	        }
		} else {
			echo validation_errors();
		}

	}

	public function package_put($id='')
	{
		// mengambil raw data dan konversi menjadi array
		$rawdata = json_decode($this->input->raw_input_stream, true);

		// mendefisinikan data sesuai dengan kolom yang ada di table transaction
		$transaction_data = array(
			'customer_name' => $this->_ifempty($rawdata, 'customer_name', NULL),
			'customer_code' => $this->_ifempty($rawdata, 'customer_code', NULL),
			'transaction_amount' => $this->_ifempty($rawdata, 'transaction_amount', 0),
			'transaction_discount' => $this->_ifempty($rawdata, 'transaction_discount', 0),
			'transaction_additional_field' => $this->_ifempty($rawdata, 'transaction_additional_field', NULL),
			'transaction_payment_type' => $this->_ifempty($rawdata, 'transaction_payment_type', NULL),
			'transaction_state' => $this->_ifempty($rawdata, 'transaction_state', NULL),
			'transaction_code' => $this->_ifempty($rawdata, 'transaction_code', NULL),
			'transaction_order' => $this->_ifempty($rawdata, 'transaction_order', NULL),
			'location_id' => $this->_ifempty($rawdata, 'location_id', NULL),
			'organization_id' => $this->_ifempty($rawdata, 'organization_id', NULL),
			'created_at' => $this->_ifempty($rawdata, 'created_at', NULL),
			'updated_at' => $this->_ifempty($rawdata, 'updated_at', NULL),
			'transaction_payment_type_name' => $this->_ifempty($rawdata, 'transaction_payment_type_name', NULL),
			'transaction_cash_amount' => $this->_ifempty($rawdata, 'transaction_cash_amount', 0),
			'transaction_cash_change' => $this->_ifempty($rawdata, 'transaction_cash_change', 0),
			'customer_attribute' => json_encode($this->_ifempty($rawdata, 'customer_attribute', NULL)),
			'connote' => json_encode($this->_ifempty($rawdata, 'connote', NULL)),
			'connote_id' => $this->_ifempty($rawdata, 'connote_id', NULL),
			'origin_data' => json_encode($this->_ifempty($rawdata, 'origin_data', NULL)),
			'destination_data' => json_encode($this->_ifempty($rawdata, 'destination_data', NULL)),
			'koli_data' => json_encode($this->_ifempty($rawdata, 'koli_data', NULL)),
			'custom_field' => json_encode($this->_ifempty($rawdata, 'custom_field', NULL)),
			'currentLocation' => json_encode($this->_ifempty($rawdata, 'currentLocation', NULL))
		);

		// validasi data transaction
		$this->form_validation->set_data($transaction_data);
		$this->form_validation->set_rules('customer_name', 'customer_name', 'trim|max_length[255]|required');
		$this->form_validation->set_rules('customer_code', 'customer_code', 'trim|max_length[50]|required');
		$this->form_validation->set_rules('transaction_amount', 'transaction_amount', 'trim|max_length[20]');
		$this->form_validation->set_rules('transaction_discount', 'transaction_discount', 'trim|max_length[20]');
		$this->form_validation->set_rules('transaction_additional_field', 'transaction_additional_field', 'trim');
		$this->form_validation->set_rules('transaction_payment_type', 'transaction_payment_type', 'trim|max_length[20]');
		$this->form_validation->set_rules('transaction_state', 'transaction_state', 'trim|max_length[100]|required');
		$this->form_validation->set_rules('transaction_code', 'transaction_code', 'trim|max_length[50]|required');
		$this->form_validation->set_rules('transaction_order', 'transaction_order', 'trim|max_length[20]|required');
		$this->form_validation->set_rules('location_id', 'location_id', 'trim|max_length[36]|required');
		$this->form_validation->set_rules('organization_id', 'organization_id', 'trim|max_length[20]|required');
		$this->form_validation->set_rules('created_at', 'created_at', 'trim');
		$this->form_validation->set_rules('updated_at', 'updated_at', 'trim');
		$this->form_validation->set_rules('transaction_payment_type_name', 'transaction_payment_type_name', 'trim|max_length[100]|required');
		$this->form_validation->set_rules('transaction_cash_amount', 'transaction_cash_amount', 'trim|max_length[20]');
		$this->form_validation->set_rules('transaction_cash_change', 'transaction_cash_change', 'trim|max_length[20]');
		$this->form_validation->set_rules('customer_attribute', 'customer_attribute', 'trim');
		$this->form_validation->set_rules('connote', 'connote', 'trim');
		$this->form_validation->set_rules('connote_id', 'connote_id', 'trim|max_length[36]|required');
		$this->form_validation->set_rules('origin_data', 'origin_data', 'trim');
		$this->form_validation->set_rules('destination_data', 'destination_data', 'trim');
		$this->form_validation->set_rules('koli_data', 'koli_data', 'trim');
		$this->form_validation->set_rules('custom_field', 'custom_field', 'trim');
		$this->form_validation->set_rules('currentLocation', 'currentLocation', 'trim');
		$transaction_valid = $this->form_validation->run();

		if ($transaction_valid) {
			$this->db->where('transaction_id', $id);
			$query = $this->db->update('transaction', $transaction_data);

			if ($query) {
				$this->response($transaction_data, 200);
	        } else {
	            $this->response(array('status' => 'fail'), 502);
	        }
		} else {
			echo validation_errors();
		}
	}

	public function package_patch($id='')
	{
		// mengambil raw data dan konversi menjadi array
		$rawdata = json_decode($this->input->raw_input_stream, true);
		$transaction_data = $rawdata;

		$this->db->where('transaction_id', $id);
		$query = $this->db->update('transaction', $transaction_data);

		if ($query) {
			$this->response($transaction_data, 200);
        } else {
            $this->response(array('status' => 'fail'), 502);
        }
	}

	public function package_delete($id='')
	{
		$this->db->where('transaction_id', $id);
		$query = $this->db->delete('transaction');
		
		if ($query) {
            $this->response(array('status' => 'success'), 201);
        } else {
            $this->response(array('status' => 'fail'), 502);
        }
	}

	private function _ifempty($arr, $key, $default)
	{
		return !isset($arr[$key]) || empty($arr[$key]) || $arr[$key] == 'undefined' ? $default : $arr[$key];
	}

}

/* End of file Package.php */
/* Location: ./application/controllers/Package.php */