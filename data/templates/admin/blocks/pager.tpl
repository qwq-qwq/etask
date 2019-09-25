{php}
global $app;
$pager = $app->component->view->getData('_pager');

$pages_count = count($pager);

foreach($pager as $pg){
	if ($pg['current'] == 1) $page = $pg['number'];
}

$js_func_name = 'onPage';

$lbreak = 0;
$rbreak = 0;
$chain = array();
if ($pages_count > 13){
	for($cc = 1; $cc < 4; $cc++) $chain[$cc] = $cc;
	if ($page > 8){
		$chain[4] = '...';
		$lbreak++;
	}
	else $chain[4] = 4;

	for($cc = 13; $cc > 10; $cc--) $chain[$cc] = $pages_count - 13 + $cc;
	if ($pages_count - $page > 7){
		$chain[10] = '...';
		$rbreak++;
	}
	else $chain[10] = 10;

	$offset = 2;
	if ($page < 9) $offset = $page - 1;
	if ($pages_count - $page < 8) $offset =  - $pages_count + $page + 4;

	for($cc = 1 + $lbreak*4; $cc <= 13 - $rbreak*4; $cc++){
		$chain[$cc] = $page - 5 -$offset - $lbreak*4 + $rbreak*4 + $cc;
	}
	ksort($chain);

	if ($pages_count > 1)
	{
		echo '<table><tr>';

		foreach ($chain as $ring){
			
			if ($ring == $page){
				 $style = 'style="background-color: #AAAAAA; width: 15px; text-align: center"';
				 $a = $ring;
			}
			elseif ($ring == '...') {
				$style = 'style="background-color: #FFFFFF; width: 15px; text-align: center"';
				$a = $ring;
			}
			else {
				$style = 'style="background-color: #FFFFFF; width: 15px; text-align: center"';
				$a = '<a href="javascript:'.$js_func_name.'('.$ring.')">'.$ring.'</a>';
			}
			echo '<td '.$style.'>'.$a.'</td>';
		}
		
		echo '</tr></table>';
	}
}else{
	if ($pages_count > 1)
	{
		echo '<table><tr>';

		for ($cc = 1; $cc <= $pages_count; $cc++){
			if ($cc == $page) {
				$style = 'style="background-color: #AAAAAA; width: 15px; text-align: center; font-size: 14px;"';
				$a = $cc;
			}
			else {
				$style = 'style="background-color: #FFFFFF; width: 15px; text-align: center; font-size: 12px;"';
				$a = '<a href="javascript:'.$js_func_name.'('.$cc.')">'.$cc.'</a>';
			}
			echo '<td '.$style.'>'.$a.'</td>';
		}
		
		echo '</tr></table>';
	}
}

{/php}
{if $_pager}
	<!-- page browse OLD
	<table>
	<tr>
	{foreach from=$_pager item=item}
		{if $item.current==1}
			<td style="background-color: #AAAAAA; width: 15px; text-align: center">
				{$item.number}
			</td>
		{else}
			<td style="background-color: #FFFFFF; width: 15px; text-align: center">
				<a href="javascript:onPage({$item.number})">{$item.number}</a>
			</td>
		{/if}
	{/foreach}
	</tr>
	</table> !-->
{/if}