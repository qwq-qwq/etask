<input type="hidden" name="comments_sortOrder" id="comments_sortOrder" value="{$comments_sortOrder}"/>
<input type="hidden" name="comments_sortBy" id="comments_sortBy" value="{$comments_sortBy}"/>
<input type="file" id="btnAddDocument" name="btnAddDocument" style="display:none;" />
<table class="comments a">
	<tr><td><h3>Комментарии задачи</h3></td></tr>
	<tr>
		<th width="600px"><span style="font-weight: normal;">Сортировать: </span>
			{include file='blocks/sortlink.tpl' prefix=comments_ field='comments_varCreated' text='Дата и время' sortorder=$comments_sortOrder sortby=$comments_sortBy script=true}
			{include file='blocks/sortlink.tpl' prefix=comments_ field='comments_intUserID' text='Автор' sortorder=$comments_sortOrder sortby=$comments_sortBy script=true}
			{include file='blocks/sortlink.tpl' prefix=comments_ field='comments_varText' text='Комментарий' sortorder=$comments_sortOrder sortby=$comments_sortBy script=true}
			<br />
			{include file="blocks/scroller.tpl" pager=$comments.pager prefix=comments_ script=1}
		</th>
	</tr>
	{foreach from=$comments item=comment key=key}
		{if is_integer($key)}
		<tr>
			<td>
				<br />
				<div style="border-bottom: 1px solid #999;"><i style="padding-right: 10px;">{$comment.varCreated}</i> <strong>{$comment.userName}</strong></div>
				<p style="font-size: 14px;">{$comment.varText}</p>
				{if $comment.documents}
				{foreach from=$comment.documents item=document}
					<img src="img/{$document.varType|regex_replace:"/\/.*/":"-"}icon.png" align="left" style="padding-right: 5px;" /> <a href="task.php?ID={$task.intID}&event=documentDownload&documents_intDocumentID={$document.intDocumentID}">{$document.varFilename}</a>
					<br /><i>{math equation="x/y" x=$document.intSize y=1024 format="%.2f"} Кб</i><br /><br />
				{/foreach}{/if}
			</td>
		</tr>
		{/if}
	{/foreach}
</table>
<br />
<div id="textareaComment" style="display:block;">
	<a name="comments"></a>
	<label for="comments_textComment">Введите комментарий</label><br />
	<textarea name="comments_varText" id="comments_textComment" style="width:600px; margin-bottom:5px; height: 100px;"></textarea><br>
	<input type="file" name="fileComment[]" class="multi" maxlength="5"/>
	<br />
	<input type="button" id="btnAddComment" value="Добавить комментарий" />
</div>