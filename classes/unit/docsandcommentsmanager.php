<?php

Kernel::Import("classes.unit.commentmanager");
Kernel::Import("classes.data.DocumentsTable");

class DocsAndCommentsManager extends CommentManager  {
	
	var $documentstable;
	
	function index(){
		parent::index();
		$this->documentstable = new DocumentsTable($this->connection);
	}
	
	function OnDocumentDelete() {
		$this->response->redirect($_SERVER["SCRIPT_NAME"].$this->getQueryUri());
	}

	function OnDocumentUpdate() {
		$this->response->redirect($_SERVER["SCRIPT_NAME"].$this->getQueryUri());
	}

	function OnDocumentUpload() {
		$source = $this->request->getFiles('documents_varFile');
		$file = md5(time().rand(1000,9999));
		$dir = FILESTORAGE. "docs/" .substr($file, 0, 3)."/";
		if ($source["size"]) {
			if ( ! is_dir($dir)){
				if ( ! mkdir($dir, 0777)){
					$this->addErrorMessage('Не удалось создать директорию для загрузки файла');
				}	
			}
			$filepath = $dir.$file;
			if ( ! copy($source['tmp_name'], $filepath)){
				$this->addErrorMessage('Не удалось создать директорию для загрузки файла');
			}
			$data['varTableName'] = $this->tablename;
			$data['intIdentID'] = $this->ident;
			$data['varCreated'] = date("Y-m-d H:i:s");
			$data['intUserID'] = $this->getUserID();
			$data['varFilename'] = $source['name'];
			$data['varFile'] = $file;
			if ($this->request->getErrors()) {
				$this->addErrorMessage('Ошибка загрузки документа');
			}else{
				$this->documentstable->Insert($data);
				$this->addMessage('Документ успешно загружен');		
			}	
		}
		$this->response->redirect($_SERVER["SCRIPT_NAME"].$this->getQueryUri());
	}
	
	function OnDocumentDownload() {
		$data['intDocumentID'] = $this->request->getNumber('documents_intDocumentID');		
		$data = $this->documentstable->Get($data);
		if (!empty($data)) {
			$filename = $data['varFilename'];
			$filepath = FILESTORAGE. "docs/" .substr($data['varFile'],0,3)."/".$data['varFile'];
			$size = filesize($filepath);
			if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE"))
			{
				header('Content-Type: "application/octet-stream"');
				header('Content-Disposition: attachment; filename="'.$filename.'"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header("Content-Transfer-Encoding: binary");
				header('Pragma: public');
				header("Content-Length: ".$size);
			}
			else
			{
				header('Content-Type: "application/octet-stream"');
				header('Content-Disposition: attachment; filename="'.$filename.'"');
				header("Content-Transfer-Encoding: binary");
				header('Expires: 0');
				header('Pragma: no-cache');
				header("Content-Length: ".$size);
			}
			readfile($filepath);
			die();
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
		$documents = $this->documentstable->GetList($data, $orders, null, null, null, true, $page, $this->itemsPerPage);
		$this->document->addValue('documents_sortBy', $sortBy);
		$this->document->addValue('documents_sortOrder', $sortOrder);		
		$this->document->addValue('documents', $documents);	
		$browser = $this->browser_info($_SERVER['HTTP_USER_AGENT']);
		$browsers = array(
					'firefox' => 3.6,
					'safari' => 0
					);
		$compatibility = FALSE;
		foreach($browsers as $n=>$v) {
			if (isset($browser[$n]) && $browser[$n] >= $v) {
				if($v == 'safari' && strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') === FALSE) continue;
				$compatibility = TRUE;
				break;
			}
		}
		$this->document->addValue('compability', $compatibility);
		
	}
	
	function browser_info($agent=null) {
		  // Declare known browsers to look for
		  $known = array('msie', 'firefox', 'safari', 'webkit', 'opera', 'netscape',
		    'konqueror', 'gecko', 'chrome');
		
		  // Clean up agent and build regex that matches phrases for known browsers
		  // (e.g. "Firefox/2.0" or "MSIE 6.0" (This only matches the major and minor
		  // version numbers.  E.g. "2.0.0.6" is parsed as simply "2.0"
		  $agent = strtolower($agent ? $agent : $_SERVER['HTTP_USER_AGENT']);
		  $pattern = '#(?<browser>' . join('|', $known) .
		    ')[/ ]+(?<version>[0-9]+(?:\.[0-9]+)?)#';
		
		  // Find all phrases (or return empty array if none found)
		  if (!preg_match_all($pattern, $agent, $matches)) return array();
		
		  // Since some UAs have more than one phrase (e.g Firefox has a Gecko phrase,
		  // Opera 7,8 have a MSIE phrase), use the last one found (the right-most one
		  // in the UA).  That's usually the most correct.
		  $i = count($matches['browser'])-1;
		  return array($matches['browser'][$i] => $matches['version'][$i]);
	}
}