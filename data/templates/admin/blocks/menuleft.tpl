		{if $mode eq '1'}
			<div class="graytop"><a href="#" onclick="$('#dep').toggle();setmenu('dep');"><div class="sectionstoptext" style="float: left; width: 160px;">Задачи департамента</div></a></div>
			<div class="graymid" id="dep" style="display:{if $menudep eq '1'}block{else}none{/if};">
				<ul class="navlist">
					<li><a class="catpos2" onclick="setfilter('dep','','{$useralldepartments}','');" href="#" title="Все задачи">
					<span style='color: #000000;font-weight: bold;'>Все задачи</span>
					</a></li>
					<li><a class="catpos2" onclick="setfilter('dep',1,'{$useralldepartments}','');" href="#" title="Новые заявки">
					Все
					{if $counter_department_all_state.state1>0}
						<span style='color: #FF0000;font-weight: bold;'>"Новые" ({$counter_department_all_state.state1})</span>
						{else}
						<span style='color: #000000'>"Новые"</span>
					{/if}
					</a></li>
					<li><a class="catpos2" onclick="setfilter('dep',10,'{$useralldepartments}','');" href="#" title="Просроченные {$current_date}">
					Все
					{if $counter_department_all_state.state10>0}
						<span style='color: #AA0000;font-weight: bold;'>"Просроченные" ({$counter_department_all_state.state10})</span>
						{else}
						<span style='color: #000000'>"Просроченные"</span>
					{/if}
					</a></li>
					<li><a class="catpos2" onclick="setfilter('dep',2,'{$useralldepartments}','');" href="#" title="В работе">
					Все
						{if $counter_department_all_state.state2>0}
							<span style='color: #FF0000;font-weight: bold;'>"В работе" ({$counter_department_all_state.state2})</span>
							{else}
							<span style='color: #000000'>"В работе"</span>
						{/if}
					</a></li>
					<li><a class="catpos2" onclick="setfilter('dep',3,'{$useralldepartments}','');" href="#" title="Выполненные">
					Все
						{if $counter_department_all_state.state3>0}
							<span style='color: #00CC00;font-weight: bold;'>"Выполненные" ({$counter_department_all_state.state3})</span>
							{else}
							<span style='color: #000000'>"Выполненные"</span>
						{/if}
					</a></li>
					<li><a class="catpos2" onclick="setfilter('dep',4,'{$useralldepartments}','');" href="#" title="Отказ">
					Все
						{if $counter_department_all_state.state4>0}
							<span style='color: #0000CC;font-weight: bold;'>"Отказ" ({$counter_department_all_state.state4})</span>
							{else}
							<span style='color: #000000'>"Отказ"</span>
						{/if}
					</a></li>
					<li><a class="catpos2" onclick="setfilter('mydep',5,'{$useralldepartments}','');" href="#" title="Заблокированые">
					Все
						{if $counter_department_all_state.state5>0}
							<span style='color: #0000CC;font-weight: bold;'>"Заблокированые" ({$counter_department_all_state.state5})</span>
							{else}
							<span style='color: #000000'>"Заблокированые"</span>
						{/if}
					</a></li>
					<li><a class="catpos2" onclick="setfilter('mydep',6,'{$useralldepartments}','');" href="#" title="Выполненые частично">
					Все
						{if $counter_department_all_state.state6>0}
							<span style='color: #0000CC;font-weight: bold;'>"Выполненые частично" ({$counter_department_all_state.state6})</span>
							{else}
							<span style='color: #000000'>"Выполненые частично"</span>
						{/if}
					</a></li>
				</ul>
			</div>
			<div class="graybot"></div>
			{/if}
			<div class="sectionstop"><a href="#" onclick="$('#mydep').toggle();setmenu('mydep');"><div class="sectionstoptext" style="float: left; width: 160px;">{if $multidepartment eq 1}Мои департаменты{else}Мой департамент{/if}</div></a></div>
			<div class="sectionsmid" id="mydep" style="display:{if $mode == '1' && $menumydep != '1'}none{else}block{/if};">
				<ul class="navlist">
					<li><a class="catpos2" onclick="setfilter('mydep','','{$userdepartments}','');" href="#" title="Все задачи">
					<span style='color: #000000;font-weight: bold;'>Все задачи</span>
					</a></li>
					<li><a class="catpos2" onclick="setfilter('mydep',1,'{$userdepartments}','');" href="#" title="Новые заявки">
					Все
					{if $counter_department_state.state1>0}
						<span style='color: #FF0000;font-weight: bold;'>"Новые" ({$counter_department_state.state1})</span>
						{else}
						<span style='color: #000000'>"Новые"</span>
					{/if}
					</a></li>
					<li><a class="catpos2" onclick="setfilter('mydep',10,'{$userdepartments}','');" href="#" title="Просроченные {$current_date}">
					Все
					{if $counter_department_state.state10>0}
						<span style='color: #AA0000;font-weight: bold;'>"Просроченные" ({$counter_department_state.state10})</span>
						{else}
						<span style='color: #000000'>"Просроченные"</span>
					{/if}
					</a></li>
					<li><a class="catpos2" onclick="setfilter('mydep',2,'{$userdepartments}','');" href="#" title="В работе">
					Все
						{if $counter_department_state.state2>0}
							<span style='color: #FF0000;font-weight: bold;'>"В работе" ({$counter_department_state.state2})</span>
							{else}
							<span style='color: #000000'>"В работе"</span>
						{/if}
					</a></li>
					<li><a class="catpos2" onclick="setfilter('mydep',3,'{$userdepartments}','');" href="#" title="Выполненные">
					Все
						{if $counter_department_state.state3>0}
							<span style='color: #00CC00;font-weight: bold;'>"Выполненные" ({$counter_department_state.state3})</span>
							{else}
							<span style='color: #000000'>"Выполненные"</span>
						{/if}
					</a></li>
					<li><a class="catpos2" onclick="setfilter('mydep',4,'{$userdepartments}','');" href="#" title="Отказ">
					Все
						{if $counter_department_state.state4>0}
							<span style='color: #0000CC;font-weight: bold;'>"Отказ" ({$counter_department_state.state4})</span>
							{else}
							<span style='color: #000000'>"Отказ"</span>
						{/if}
					</a></li>
					<li><a class="catpos2" onclick="setfilter('mydep',5,'{$userdepartments}','');" href="#" title="Заблокированые">
					Все
						{if $counter_department_state.state5>0}
							<span style='color: #0000CC;font-weight: bold;'>"Заблокированые" ({$counter_department_state.state5})</span>
							{else}
							<span style='color: #000000'>"Заблокированые"</span>
						{/if}
					</a></li>
					<li><a class="catpos2" onclick="setfilter('mydep',6,'{$userdepartments}','');" href="#" title="Выполненые частично">
					Все
						{if $counter_department_state.state6>0}
							<span style='color: #0000CC;font-weight: bold;'>"Выполненые частично" ({$counter_department_state.state6})</span>
							{else}
							<span style='color: #000000'>"Выполненые частично"</span>
						{/if}
					</a></li>

				</ul>
			</div>
			<div class="sectionsbot"></div>
			<div class="sectionstop"><a href="#" onclick="$('#my').toggle();setmenu('my');"><div class="sectionstoptext" style="float: left; width: 160px;">Мои задачи</div></a></div>
			<div class="sectionsmid" id="my" style="display:{if $menumy eq '1'}block{else}none{/if};">
				<ul class="navlist">
					<li><a class="catpos2" onclick="setfilter('my','','','{$intExecutorID}');" href="#" title="Все задачи">
					<span style='color: #000000;font-weight: bold;'>Все мои задачи</span>
					</a></li>
					<!--<li><a class="catpos2" onclick="setfilter('my',1,'','{$intExecutorID}');" href="#" title="Новые заявки">
					Мои
					{if $counter_state.state1>0}
						<span style='color: #FF0000;font-weight: bold;'>"Новые" ({$counter_state.state1})</span>
						{else}
						<span style='color: #000000'>"Новые"</span>
					{/if}
					</a></li>-->
					<li><a class="catpos2" onclick="setfilter('my',10,'','{$intExecutorID}');" href="#" title="Просроченные {$current_date}">
					Мои
					{if $counter_state.state10>0}
						<span style='color: #AA0000;font-weight: bold;'>"Просроченные" ({$counter_state.state10})</span>
						{else}
						<span style='color: #000000'>"Просроченные"</span>
					{/if}
					</a></li>
					<li><a class="catpos2" onclick="setfilter('my',2,'','{$intExecutorID}');" href="#" title="В работе">
					Мои
						{if $counter_state.state2>0}
							<span style='color: #FF0000;font-weight: bold;'>"В работе" ({$counter_state.state2})</span>
							{else}
							<span style='color: #000000'>"В работе"</span>
						{/if}
					</a></li>
					<li><a class="catpos2" onclick="setfilter('my',3,'','{$intExecutorID}');" href="#" title="Выполненные">
					Мои
						{if $counter_state.state3>0}
							<span style='color: #00CC00;font-weight: bold;'>"Выполненные" ({$counter_state.state3})</span>
							{else}
							<span style='color: #000000'>"Выполненные"</span>
						{/if}
					</a></li>
					<li><a class="catpos2" onclick="setfilter('my',4,'','{$intExecutorID}');" href="#" title="Отказ">
					Мои
						{if $counter_state.state4>0}
							<span style='color: #0000CC;font-weight: bold;'>"Отказ" ({$counter_state.state4})</span>
							{else}
							<span style='color: #000000'>"Отказ"</span>
						{/if}
					</a></li>
					<li><a class="catpos2" onclick="setfilter('my',5,'','{$intExecutorID}');" href="#" title="Заблокированые">
					Мои
						{if $counter_state.state5>0}
							<span style='color: #0000CC;font-weight: bold;'>"Заблокированые" ({$counter_state.state5})</span>
							{else}
							<span style='color: #000000'>"Заблокированые"</span>
						{/if}
					</a></li>
					<li><a class="catpos2" onclick="setfilter('my',6,'','{$intExecutorID}');" href="#" title="Выполненые частично">
					Мои
						{if $counter_state.state6>0}
							<span style='color: #0000CC;font-weight: bold;'>"Выполненые частично" ({$counter_state.state6})</span>
							{else}
							<span style='color: #000000'>"Выполненые частично"</span>
						{/if}
					</a></li>
					<li><a class="catpos2" href="index.php?event=logout" title="Выход">
						<span style='color: #000;font-weight: bold;'>Выход</span>
					</a></li>
				</ul>
				{if $showCCTask}<div style="margin-left: 12px;">
				<form action="create.php" method="POST">
				<input type="text" style="width: 60px" name="intOrderID">
				<input type="submit" value="Создать КЦ">
				</form>
				</div>{/if}
			</div>
			<div class="sectionsbot"></div>
