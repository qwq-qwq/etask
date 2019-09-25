<?

include_once(realpath(dirname(__FILE__)."/classes/variables.php"));

Kernel::Import("classes.web.AjaxPage");
Kernel::Import("classes.data.empik.AccountsTable");

class User_Search extends AjaxPage
{
	function __construct($Template) {
		parent::__construct($Template);
	}

	function index() {
		parent::index();
		    $term = $this->request->getString('term');
		 //if (!empty($id) && is_numeric($id)) {
		 	//echo $term;
		 	$accountsTable = new AccountsTable($this->connectionEmpik);
		 	$data = array('LIKEsurname'=>$term);
		 	$account = $accountsTable->getList($data, array('surname, name'=>asc), 150);
		 	
		 	$i = 0;
			foreach ($account as $row){
    			$s .= '"'.$row['id']. ' ' .$row['name'].' '.$row['surname'].' ('. $row['login'] .')"'; 
   				if (($i < 149)&&($i < count($account)-1)) {
    				$s .= ', ';
    			}else{
     				 break;
    			};
    			$i++;
  			};
			//$s = substr($s, 0, strlen($s)-2);
			$s = "[".$s."]";
			echo $s;

		 	//echo var_dump($account);
		 	//if (!empty($account)) {
		 	//	$account_json = json_encode($this->prepareAccountInfo($account));
		 	//	$this->document->addValue('output', $account_json);
		 	//}
		 //}
        
	}

	
	function render() {
		parent::render();
	}
}

Kernel::ProcessPage(new User_Search("void.tpl"));

?>