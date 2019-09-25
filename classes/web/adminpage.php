<?php

Kernel::Import("system.page.Page");
Kernel::Import('system.response.*');
Kernel::Import('classes.data.intranet.UsersTable');
Kernel::Import('classes.data.intranet.DepartmentsTable');
Kernel::Import("classes.data.etasks.TasksTable");
Kernel::Import("classes.data.etasks.TaskTypesTable");
Kernel::Import('classes.unit.tasks.Task');

class AdminPage extends Page {

	public $tasksTable;
	protected $tasktypesTable;
	protected $usersTable;

	function __construct($Template) {
		parent::__construct($Template);
		$this->setResponse(new SmartyResponse($this, $this->document));
		$this->response->maintemplate = "layout.tpl";

		$this->tasksTable = new TasksTable($this->connection);
		$this->tasktypesTable = new TaskTypesTable($this->connection);
		$this->usersTable = new UsersTable($this->connectionIntranet);
	}

	function authenticate() {
		$intUserID = $this->getUserID();
		if (empty($intUserID)) {
			$this->OnLogout();
		}
	}

	// Произвести коррекцию цены с учетом скидки покупки для тех групп товаров, на которые она распространяется
	function CorrectPrice(&$arrGoods, $disc) {
		foreach ($arrGoods as $k => $v) {
			if ($v['Discount_forbidden'] !== 'Yes') {
				$cur_disc = max((float)$disc, (float)$v['discount']);
				$arrGoods[$k]['Price'] -= $arrGoods[$k]['Price']*$cur_disc/100;
			}
		}
	}

	public function OnAutologin(){
		/*$intUserID = $this->request->getNumber('intUserID');
		$isAdmin = $this->request->getString('isadmin');
		//$intUserID = 362;
		
		$hash = md5(SECRET_KEY.$intUserID.$isAdmin);
		if( $hash != $this->request->getString('hash') ){
			//$this->response->redirect(PROJECT_INTRANET_URL.'#hash');
		}*/
		var_dump($this->request->getString('varPassword', null, 1));
		$login = $this->request->getString('varLogin', null, 1);
                $password = $this->request->getString('varPassword', null, 1);
		if( $login === '1' || $password === '1') {
		    $this->response->redirect('login.php');
		    die();
		}
                $admin = $this->usersTable->GetByFields(array('varLogin'=>$login));
                if( $admin['isDisabled'] == '1' ){
                    $this->addMessage('Account is disabled.');
                    $this->response->redirect('login.php');
                    die();
                 };
                //     var_dump($admin);
		//exit;
                if (count($admin) && $password === $admin['varPassword']) {
                   $admin['intLastLoginTimestamp'] = time();
                   $this->usersTable->Update($admin);
                   //$this->session->Set('intUserID', $admin['intUserID']);
		   $intUserID =  $admin['intUserID'];
                };
                //$this->response->redirect('index.php');

		$usersTable = new UsersTable($this->connectionIntranet);

		$user = $usersTable->get(array('intUserID'=>$intUserID));

		if( !count($user) ){
		    $this->response->redirect('login.php');
		}
                //print_r($user);
		// get User deprtaments
		$departmentsTable = new DepartmentsTable($this->connectionIntranet);
		$departments = array($departmentsTable->getByFields(array('varValue'=>$user['varDepartment'])));
		$departmentIDs = array($departments[0]['intVarID']);
		$userDepartments = $departmentsTable->getUserDepartments($intUserID);
		if ( ! empty($userDepartments)) {
			foreach ($userDepartments as $k=>$v) {
				$departmentIDs[] = $v['intDepartmentID'];
			}
			$where = array('INintVarID'=>implode(",", $departmentIDs));
			$sort = array('varValue'=>'asc');
			$departments = $departmentsTable->getList($where, $sort);
		}

		//$user['isAdmin'] = ($isAdmin != 'user');
		//$user['isAdmin'] = false;
		if ($user['isAdmin']) {
			$departmentsAll = $departmentsTable->getList(null, array('varValue'=>'asc'));
			foreach ($departmentsAll as $k=>$v) {
				$departmentAllIDs[] = $v['intVarID'];
			}
			$user['departmentsAll'] = $departmentsAll;
			$user['departmentAllIDs'] = $departmentAllIDs;
		}else{
			$user['departmentsAll'] = $departments;
			$user['departmentAllIDs'] = $departmentIDs;
		}
		$user['departments'] = $departments;
		$user['departmentIDs'] = $departmentIDs;

		$users_list = $this->usersTable->getList(null, array('varFIO'=>'asc'));
		$user['users_list'] = $users_list;

		$this->session->set('USER_DATA', $user);

		$target = base64_decode($this->request->getString('target'));
		if (!empty($target)) {
			$this->response->redirect($target);
		} else {
			$this->response->redirect('/');
		}
	}

	public function OnLogout() {
		$this->session->set('USER_DATA', null);
		$this->response->redirect("login.php");
	}

	function getAllUsers () {
		$user = $this->session->get('USER_DATA');
		return $user['users_list'];
	}

	function getAllUsersFio() {
		$user = $this->session->get('USER_DATA');
		$users_list = $user['users_list'];
		$ulist = array();
		foreach ($users_list as $k=>$v) {
			$ulist[$v['intUserID']] = $v['varFIO'];
		}
		return $ulist;
	}

	function getUserID () {
		$user = $this->session->get('USER_DATA');
		return $user['intUserID'];
	}

	function getUserDepartments () {
		$user = $this->session->get('USER_DATA');
		return $user['departments'];
	}

	function getUserDepartmentIDs () {
		$user = $this->session->get('USER_DATA');
		return $user['departmentIDs'];
	}

	function getUserAllDepartments () {
		$user = $this->session->get('USER_DATA');
		return $user['departmentsAll'];
	}

	function getUserAllDepartmentIDs () {
		$user = $this->session->get('USER_DATA');
		return $user['departmentAllIDs'];
	}

	function getUserDepartmentsIDs () {
		return $this->isUserAdmin()
				? $this->getUserAllDepartmentIDs()
				: $this->getUserDepartmentIDs();
	}

	function isUserMultiDepartament () {
		$md = $this->getUserDepartmentIDs();
		return (count($md)>1);
	}

	function isUserAdmin () {
		$user = $this->session->get('USER_DATA');
		return $user['isAdmin'];
	}

	function getUserName () {
		$user = $this->session->get('USER_DATA');
		return $user['varFIO'];
	}

	function setBoldMenu ($menu) {
		$this->document->addValue('boldMenu', $menu);
	}

	function addErrorMessage ($message) {
		$this->addMessage($message, true);
	}

	function addMessage ($message, $error = false) {
		$messages = $this->session->Get('messages');
		$messages = is_null($messages)? array() : $messages;
		$messages[] = array('msg' => $message, 'error' => $error);
		$this->session->Set('messages', $messages);
	}

	function hasErrorMessages () {
		$messages = $this->session->Get('messages');
		$messages = is_null($messages)? array() : $messages;
		foreach ($messages as $msg) {
			if ($msg['error']) return true;
		}
		return false;
	}

	function writeMessages () {
		$messages = $this->session->Get('messages');
		if( is_array($messages) && count($messages) ) {
			$this->document->addValue('messages', $messages);
			$this->session->Set('messages', array());
		}
	}

	function setPageTitle ($title) {
		$this->document->addValue('pagetitle', $title);
	}

	function render() {
		// render messages
		$this->writeMessages();
		// render formerrors
		$this->document->addValue('hilightFormElements', $this->request->getErrors());
		if ( in_array( DEPARTMENT_CALLCENTRE_ID, $this->getUserDepartmentsIDs() )) $this->document->addValue('showCCTask', '1');
	}

	function getTemplatesRoot() {
		return "admin/";
	}

	function getSessionID()	{
		return PROJECT_SESSION_NAME . 'admin';
	}

	function getTaskInstance($id){
		//Get task
		$task = $this->tasksTable->Get(array('intID' => $id));
		$type = null;
		$instance = null;
		//Get it's type
		if (!empty($task)) {
			$type = $this->tasktypesTable->Get(array('intID' => $task['intType']));
		}
		//Create new task instance
		if (!empty($type)) {
			echo 'classes.unit.tasks.'.$type['varController'];
			Kernel::Import('classes.unit.tasks.'.$type['varController']);
			if (class_exists($type['varController'])) $instance = new $type['varController']($this, $task);
		}
	        if (!is_object($instance)) {
			$instance = new Task($this, $task);
		}
		return $instance;
	}

}