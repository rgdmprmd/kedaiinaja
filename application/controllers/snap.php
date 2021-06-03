<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");

if ( "OPTIONS" === $_SERVER['REQUEST_METHOD'] ) {
    die();
}

class Snap extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */


	public function __construct()
    {
        parent::__construct();
        $params = array('server_key' => 'SB-Mid-server-rOppPix03bf6s1J_kFywI9C9', 'production' => false);
		$this->load->library('midtrans');
		$this->midtrans->config($params);
		$this->load->helper('url');	
		$this->load->model('Snap_model', 'model');
    }

    public function index()
    {
    	$this->load->view('checkout_snap');
    }

    public function token()
    {
		$pesanan = $this->model->getPesanan();
		
		// Required
		$transaction_details = array(
		  'order_id' => rand(),
		  'gross_amount' => 94000, // no decimal allowed for creditcard
		);

		// Optional
		$item1_details = array(
		  'id' => 'a1',
		  'price' => 18000,
		  'quantity' => 3,
		  'name' => "Apple"
		);

		// Optional
		$item2_details = array(
		  'id' => 'a2',
		  'price' => 20000,
		  'quantity' => 2,
		  'name' => "Orange"
		);

		// Optional
		$item_details = array ($item1_details, $item2_details);

		// Optional
		$billing_address = array(
		  'first_name'    => "Andri",
		  'last_name'     => "Litani",
		  'address'       => "Mangga 20",
		  'city'          => "Jakarta",
		  'postal_code'   => "16602",
		  'phone'         => "081122334455",
		  'country_code'  => 'IDN'
		);

		// Optional
		$shipping_address = array(
		  'first_name'    => "Obet",
		  'last_name'     => "Supriadi",
		  'address'       => "Manggis 90",
		  'city'          => "Jakarta",
		  'postal_code'   => "16601",
		  'phone'         => "08113366345",
		  'country_code'  => 'IDN'
		);

		// Optional
		$customer_details = array(
		  'first_name'    => "Andri",
		  'last_name'     => "Litani",
		  'email'         => "andri@litani.com",
		  'phone'         => "081122334455",
		  'billing_address'  => $billing_address,
		  'shipping_address' => $shipping_address
		);

		// Data yang akan dikirim untuk request redirect_url.
        $credit_card['secure'] = true;
        //ser save_card true to enable oneclick or 2click
        //$credit_card['save_card'] = true;

        $time = time();
        $custom_expiry = array(
            'start_time' => date("Y-m-d H:i:s O",$time),
            'unit' => 'minute', 
            'duration'  => 15
        );
        
        $transaction_data = array(
            'transaction_details'=> $transaction_details,
            'item_details'       => $item_details,
            'customer_details'   => $customer_details,
            'credit_card'        => $credit_card,
            'expiry'             => $custom_expiry
        );

		error_log(json_encode($transaction_data));
		$snapToken = $this->midtrans->getSnapToken($transaction_data);
		error_log($snapToken);
		echo $snapToken;
    }

    public function finish()
    {
    	$result = json_decode($this->input->post('result_data'));

		if(isset($result->va_numbers[0]->bank)) {
			$bank = $result->va_numbers[0]->bank;
		} else if(isset($result->permata_va_number)) {
			$bank = "permata";
		} else if(isset($result->bill_key) || isset($result->biller_code)) {
			$bank = "mandiri";
		} else {
			$bank = null;
		}

		$data = [
			"status_code" => $result->status_code,
			"status_message" => $result->status_message,
			"transaction_id" => $result->transaction_id,
			"order_id" => $result->order_id,
			"gross_amount" => $result->gross_amount,
			"payment_type" => $result->payment_type,
			"transaction_time" => Date("Y-m-d H:i:s", strtotime($result->transaction_time)),
			"transaction_status" => $result->transaction_status,
			"pdf_url" => (isset($result->pdf_url)) ? $result->pdf_url : null,
			"finish_redirect_url" => $result->finish_redirect_url,
			"fraud_status" => $result->fraud_status,
			"bank" => $bank,
			"va_number" => (isset($result->va_numbers[0]->va_number)) ? $result->va_numbers[0]->va_number : null,
			"bca_va_number" => (isset($result->bca_va_number)) ? $result->bca_va_number : null,
			"permata_va_number" => (isset($result->permata_va_number)) ? $result->permata_va_number : null,
			"bill_key" => (isset($result->bill_key)) ? $result->bill_key : null,
			"biller_code" => (isset($result->biller_code)) ? $result->biller_code : null,
			"dt_update" => Date("Y-m-d H:i:s"),
		];

		print_r($result); exit();
		// $insertTrans = $this->model->insert($data);

		if($insertTrans) {
			echo "Request pembayaran berhasil dilakukan silahkan segera melakukan pembayaran";
		} else {
			echo "Request pembayaran gagal dilakukan.";
		}
    }
}