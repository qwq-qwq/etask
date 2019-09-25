<?php
include_once("classes/variables.php");

Kernel::Import("classes.web.PublicPage");

class IndexPage extends PublicPage {

        function Authenticate() {}

        function loadComponent() {
                parent::loadComponent();
                $this->response = new SmartyResponse($this, $this->document, 'void.tpl');
        }

        function OnLogon() {
                $login = $this->request->getString('varLogin', null, 1);
                $password = $this->request->getString('varPassword', null, 1);
                $admin = $this->usersTable->GetByFields(array('varLogin'=>$login));
                if( $admin['isDisabled'] == '1' ){
                        $this->addMessage('Account is disabled.');
                        $this->response->redirect('logon.php');
                        die();
                }
                var_dump($admin);
		exit;
                if (count($admin) && $password === $admin['varPassword']) {
                        $admin['intLastLoginTimestamp'] = time();
                        $this->usersTable->Update($admin);
                        $this->session->Set('intUserID', $admin['intUserID']);
                        // set for e-training
                        $this->session->Set('CurStudentID', $admin['intUserID']);
                        $this->session->Set("CurStudent", $admin['varLogin']);
                }
                $this->response->redirect('index.php');
        }
}

Kernel::ProcessPage(new IndexPage("logon.tpl"));
?>