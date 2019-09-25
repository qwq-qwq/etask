<?php
Kernel::Import('classes.unit.tasks.Task');
Kernel::Import('classes.data.etasks.CollectArticlesTable');
Kernel::Import('classes.data.etasks.TasksTable');

class CollectTask extends Task {

	/**
	 * @var collectArticlesTable
	 * @see classes/data/collectArticlesTable.php
	 */
	protected $collectArticlesTable;
	/**
	 * @var tasksTable
	 * @see classes/data/taskstable.php
	 */
	protected $tasksTable;

	function __construct(&$page, $data) {
		parent::__construct($page, $data);
		$this->template = 'CollectTask.tpl';
		$this->collectArticlesTable = new CollectArticlesTable($this->page->getConnection());
		$this->tasksTable = new TasksTable($this->page->getConnection());
		$this->salesTable = new SalesTable($this->page->getConnectionEmpik());
	}

	function RegisterNedostach($prods) {

		$departmentsTable = new DepartmentsTable($this->page->getConnectionIntranet());
		$dep = $departmentsTable->Get(array('intVarID' => $this->task_data["intDepartmentID"]));

		$res = $this->sprutModel->query("select MZ.GetCodeWarehouseLoses(".$dep['intCodeShopSprut'].") as WH_TO from dual");
		$TradeHallToShop = $res[0]['WH_TO'];
		$res = $this->sprutModel->query("select MZ.GetTradeHallFromShop(".$dep['intCodeShopSprut'].") as WH_FROM from dual");
		$TradeHallFromShop = $res[0]['WH_FROM'];

		$comment = iconv('UTF-8', 'Windows-1251', 'Передаточная создана по заказу bukva.ua №'.$this->order_data['Ord_id'].' при закрытии задачи сбора товаров №'.$this->task_data['intID']);

		$SQL = "declare
				code_woi# integer;
				num_woi# integer;
				qty# integer;
				res# integer;
				begin

				code_woi#:=mz.GetCodeWriteOffInvoice;

				num_woi#:=mz.GetNUmberWriteOffInvoice;

				insert into MZ.WRITE_OFF_INVOICE
				 (CODE_WRITE_OFF_INVOICE, CODE_SHOP, CODE_SUBGROUP, NUMBER_WRITE_OFF_INVOICE,
				DATE_WRITE_OFF_INVOICE, CODE_ADDITION_SIGN, CODE_PATTERN,
				CODE_FIRM, CODE_WAREHOUSE, VARIETY_WRITE_OFF_INVOICE, TYPE_WRITE_OFF_INVOICE, CHANGE_STATE, TYPE_PRICE,
				TYPE_ADDRESSEE, CODE_ADDRESSEE, CODE_WAREHOUSE_ADDRESSEE, CODE_SHOP_ADDRESSEE,DESCRIPTION,CODE_DEALER)
				values (code_woi#,".$dep['intCodeShopSprut'].",1,num_woi#,trunc(sysdate),-4,1902, ".$this->sprutModel->getFirmByShop($dep['intCodeShopSprut']).",".$TradeHallFromShop.",'T','O','02','PD','F',".$this->sprutModel->getFirmByShop($dep['intCodeShopSprut']).",".$TradeHallToShop.",".$dep['intCodeShopSprut'].",'".$comment."' ,MZ.GETCODEDEALERFROMSHOP(".$dep['intCodeShopSprut']."));
				\n";

		if (is_array($prods)) {
			foreach ($prods as $cw => $qty) {
				// те позиции и в том количестве в котором недостача
				$SQL .= "res#:=MZ.SPR\$_WRITE_OFF_INVOICE.INSWARESTOWRITEOFFINVOICE(code_woi#,".$cw.",19,".$qty.",0,-1,0,'',0,'',0,0,0,1,'C',0);\n";
			}
		}

		$SQL .= "mz.spr\$_write_off_invoice.WRITEOFFINVOICETONEEDSTATE(code_woi#,trunc(sysdate),'E',0,0);\n
				end;\n";

		$res = $this->sprutModel->query($SQL);
		if ($res === FALSE){
			die('ERROR! '.$SQL);
		}
	}

	function render(){
		parent::render();

		// TODO можно вынести в task.php
		$this->page->GetDocument()->addValue('usefile', TEMPLATES_TASKS_PATH.$this->template);

		$goods = $this->collectArticlesTable->getList(array('intTaskID' => $this->task_data['intID']));
		$o_goods = $this->salesTable->GetByFields(array('Ord_id' => $this->task_data['intOrderID']), null, false);
		$this->page->CorrectPrice($o_goods, $this->order_data['discount']);
//		$task = $this->tasksTable->getList(array('intID' => $this->task_data['intID']));
		$order_goods = array();
		foreach ($o_goods as $k=>$v) {
			$order_goods[$v['Wares_id']] = $v['Price'];
		}
		foreach ($goods as $k=>$v) {
			$goods[$k]['Price'] = $order_goods[$v['intArticleID']];
		}
		$this->page->getDocument()->addValue('goods', $goods);

		// get task comments
		$this->prepareComments();
	}

}