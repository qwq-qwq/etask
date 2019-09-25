<?php

if (!defined('EVENT_FUNCTION_TAG')) {
	define('EVENT_FUNCTION_TAG', 'event');
}

Kernel::Import('system.page.component');
Kernel::Import('system.db.mysql.*');
Kernel::Import('system.http.session');
Kernel::Import('system.http.request');
Kernel::Import('system.data.datalayer');
Kernel::Import('system.response.nullresponse');

class Page extends Component {

	/**
	 *
	 * @var Request
	 */
	protected $request;
	/**
	 *
	 * @var Response
	 */
	protected $response;
	/**
	 *
	 * @var Session
	 */
	protected $session;
	/**
	 *
	 * @var MySqlConnection
	 */
	protected $connection;
	protected $connectionIntranet;
	protected $connectionEmpik;
	/**
	 *
	 * @var DataLayer
	 */
	protected $document;
	public $Template;
	public $current_event;

	function __construct($Template) {
		$this->Template = $Template;
		$this->document = new DataLayer('page');
		$this->request = new Request();
		$this->session = new Session($this->getSessionID());
		$this->response = new NullResponse($this, $this->document);
		$this->openConnection();
	}

	function openConnection() {
		$this->connection = new MySQLConnection( MySQLConnectionProperties::createByURI(DB_URI) );
		$this->connection->properties->setEncoding( DB_CHARSET_UTF8 );
		$this->connection->Open();

		$this->connectionIntranet = new MySQLConnection( MySQLConnectionProperties::createByURI(DB_INTRANET_URI) );
		$this->connectionIntranet->properties->setEncoding( DB_CHARSET_UTF8 );
		$this->connectionIntranet->Open();

		$this->connectionEmpik = new MySQLConnection( MySQLConnectionProperties::createByURI(DB_EMPIK_URI) );
		$this->connectionEmpik->properties->setEncoding( DB_CHARSET_UTF8 );
		$this->connectionEmpik->Open();
	}

	function getConnectionEmpik() {
		return $this->connectionEmpik;
	}

	function getConnectionIntranet() {
		return $this->connectionIntranet;
	}

	function getConnection() {
		return $this->connection;
	}

	function authenticate() {
	}

	function index() {
	}

	function __destruct() {
		if (is_object($this->session)) $this->session->Close();
		if (is_object($this->connection)) $this->connection->Close();
		if (is_object($this->connectionIntranet)) $this->connectionIntranet->Close();
		if (is_object($this->connectionEmpik)) $this->connectionEmpik->Close();
	}

	function getCurrentEvent() {
		return $this->current_event;
	}

	function processEvents() {
		$this->current_event = $this->request->Value(EVENT_FUNCTION_TAG);
		$this->processEvent($this->current_event);
	}

	function render() {}

	function terminatePage($die = true){
		if ($die) exit();
	}

	function getSessionID() {
		return null;
	}

	function getTemplatesRoot() {
		return "";
	}

	function getTemplate() {
		return $this->Template;
	}

	function setTemplate($template) {
		$this->Template = $template;
	}

	public function setResponse(AbstractResponse $responce) {
		$this->response = $responce;
	}

	public function getSession() {
		return $this->session;
	}

	public function getResponse() {
		return $this->response;
	}

	public function getRequest() {
		return $this->request;
	}


	public function getDocument() {
		return $this->document;
	}


	function setPage($templ) {
		$this->Template = $templ;
	}
}
