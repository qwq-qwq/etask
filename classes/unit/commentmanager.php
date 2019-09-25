<?php

Kernel::Import("classes.web.PublicPage");
Kernel::Import("classes.data.CommentsTable");

class CommentManager extends PublicPage {

	var $commentstable;
	var $itemsPerPage;
		
	var $tablename;
	var $ident;
	var $fields;
	
	function index() {
		parent::index();
		$this->itemsPerPage = 5;
		$this->commentstable = new CommentsTable($this->connection);
	}
	
	function OnCommentDelete() {
		$data['intСommentID'] = $this->request->getNumber('comments_intCommentID', 'NotEmpty');
		if ($this->request->getErrors()) {
			$this->addErrorMessage('Комментарий не удалось удалить');
		}else{
			$this->commentstable->Delete($data);
			$this->addErrorMessage('Комментарий успешно удален');
		}				
		$this->response->redirect($_SERVER["SCRIPT_NAME"].$this->getQueryUri());
	}

	function OnCommentUpdate() {
		$data['intcommentID'] = $this->request->getNumber('comments_intCommentID', 'NotEmpty');
		$data['varText'] = trim($this->request->getString('comments_varText', 'NotEmpty'));
		if ($this->request->getErrors()) {
			$this->addErrorMessage('Комментарий спустой, пожалуйста введите текст');
		}else{
			$this->commentstable->Update($data);
			$this->addMessage('Комментарий успешно сохранен');	
		}		
		$this->response->redirect($_SERVER["SCRIPT_NAME"].$this->getQueryUri());
	}

	function OnCommentCreate() {
		$data['varTableName'] = $this->tablename;
		$data['intIdentID'] = $this->ident;
		$data['varCreated'] = date("Y-m-d H:i:s");
		$data['intUserID'] = $this->getUserID();
		$data['varText'] = trim($this->request->getString('comments_varText', 'NotEmpty'));
		if ($this->request->getErrors()) {
			$this->addErrorMessage('Комментарий спустой, пожалуйста введите текст');
		}else{
			$this->commentstable->Insert($data);
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
	
	function init($table, $ident, $fields = NULL){
		$this->setTablename($table);
		$this->setIdent($ident);
		$this->session->set('table',$table);
		$this->session->set('ident',$ident);		
		if (!empty($fields) && is_array($fields)) {
			foreach ($fields as $k => $v) {
				$this->setFields($k, $v);
			}	
		}
		
		
	}
	
	function render() {
		parent::render();
		$data = array('varTableName'=>$this->tablename, 'intIdentID'=>$this->ident);
		$sortBy = $this->request->getString('comments_sortBy', null, 'varCreated');
		$sortOrder = $this->request->getNumber('comments_sortOrder', null, 1);
		$field = str_replace('comments_','',$sortBy);
		$orders[$field] = empty($sortOrder) ? 'ASC' : 'DESC';
		$page = $this->request->getNumber('comments_page', NULL, 1);
		$comments = $this->commentstable->GetList($data, $orders, null, null, null, true, $page, $this->itemsPerPage);
		$this->document->addValue('comments_sortBy', $sortBy);
		$this->document->addValue('comments_sortOrder', $sortOrder);		
		$this->document->addValue('comments', $comments);		
	}
}