<?php

Kernel::Import("classes.web.PublicPage");
Kernel::Import("classes.data.DocumentsTable");

class DocumentManager extends PublicPage {

	var $table;
	var $itemsPerPage;
		
	var $tablename;
	var $ident;
	var $fields;
	
	function index() {
		parent::index();
		$this->itemsPerPage = 5;
		$this->table = new DocumentsTable($this->connection);
	}
	
	function OnDocumentDelete() {
		$this->response->redirect($_SERVER["SCRIPT_NAME"].$this->getQueryUri());
	}

	function OnDocumentUpdate() {
		$this->response->redirect($_SERVER["SCRIPT_NAME"].$this->getQueryUri());
	}

	function OnDocumentUpload() {
		$data['varTableName'] = $this->tablename;
		$data['intIdentID'] = $this->ident;
		$data['varCreated'] = date("Y-m-d H:i:s");
		$data['intUserID'] = $this->getUserID();
		$data['varText'] = trim($this->request->getString('documents_varText', 'NotEmpty'));
		if ($this->request->getErrors()) {
			$this->addErrorMessage('Комментарий спустой, пожалуйста введите текст');
		}else{
			$this->table->Insert($data);
			$this->addMessage('Комментарий успешно добавлен');		
		}	
		$this->response->redirect($_SERVER["SCRIPT_NAME"].$this->getQueryUri());
	}

	function setTableName($tablename){
		$this->tablename = 	$tablename;
	}
	
	function setIdent($ident){
		$this->ident = $ident;
	}
	
	function setFields($name, $value){
		$this->fields[] = array('name'=>$name, 'value'=>$value);
		$this->document->addValue('fields', $this->fields);
	}
	
	function getQueryUri(){
		$str = "";
		if (!empty($this->fields) && is_array($this->fields)) {
			foreach ($this->fields as $field) {
				$n = $field['name'];
				$v = $field['value']; 
				if (empty($str))	$str .= "?$n=$v";
				else $str .= "&$n=$v";
			}	
		}
		return $str;
	}
	
	function initDocument ($table, $ident, $fields = NULL){
		$this->setTablename($table);
		$this->setIdent($ident);
		if (!empty($fields) && is_array($fields)) {
			foreach ($fields as $k => $v) {
				$this->setFields($k, $v);
			}	
		}		
	}
	
	function render() {
		parent::render();
		$data = array('varTableName'=>$this->tablename, 'intIdentID'=>$this->ident);
		$sortBy = $this->request->getString('documents_sortBy', null, 'varCreated');
		$sortOrder = $this->request->getNumber('documents_sortOrder', null, 1);
		$field = str_replace('documents_','',$sortBy);
		$orders[$field] = empty($sortOrder) ? 'ASC' : 'DESC';
		$page = $this->request->getNumber('documents_page', NULL, 1);
		$documents = $this->table->GetList($data, $orders, null, null, null, true, $page, $this->itemsPerPage);
		$this->document->addValue('documents_sortBy', $sortBy);
		$this->document->addValue('documents_sortOrder', $sortOrder);
		$this->document->addValue('documents', $documents);		
	}
}