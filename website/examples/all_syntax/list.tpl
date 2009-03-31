<pre class="code"><font color="#D0D0D0"><font color="#FFFF00">[[</font>
if (!function_exists('helperBtnAction')) {
	function helperBtnAction($action, $id, $icon) {
		echo('&lt;a href=&quot;?action='.$action.'&amp;id='.$id.'&quot;&gt;');
		echo('&lt;img src=&quot;ico/'.$icon.'.png&quot; alt=&quot;'.$icon.'&quot; /&gt;');
		echo('&lt;/a&gt;');
	};
};
<font color="#FFFF00">]]</font></font>
&lt;h1&gt;Gebruikerslijst&lt;/h1&gt;

&lt;p&gt;Hello <font color="#FFFF00">{{$username}}</font>, here's a list of all the users:&lt;/p&gt;

&lt;div id=&quot;acocunts&quot;&gt;
<font color="#FFFF00">   @if (count($accounts) &lt;= 0):</font>
      No accounts found.
<font color="#FFFF00">   @else:</font>
      &lt;table&gt;
         &lt;tr&gt;
            &lt;th&gt;&amp;nbsp;&lt;/th&gt;
            &lt;th&gt;Gebruikersnaam&lt;/th&gt;
            &lt;th&gt;Volledige naam&lt;/th&gt;
         &lt;/tr&gt;
<font color="#FFFF00">         @foreach ($accounts as $account):</font>
            &lt;tr&gt;
               &lt;td&gt;<font color="#FFFF00">{{helperBtnAction('account.edit', $account['id'], 'edit')}}</font>&lt;/td&gt;
               &lt;td&gt;<font color="#FFFF00">{{$account['username']}}</font>&lt;/td&gt;
               &lt;td&gt;<font color="#FFFF00">{{$account['realname']}}</font>&lt;/td&gt;
            &lt;/tr&gt;
<font color="#FFFF00">         @endforeach</font>
         &lt;tr&gt;
            &lt;td&gt;<font color="#FFFF00">{{helperBtnAction('account.add', '', 'add')}}</font>&lt;/td&gt;
            &lt;td colspan=&quot;4&quot;&gt;&amp;nbsp;&lt;/td&gt;
         &lt;/tr&gt;
      &lt;/table&gt;
<font color="#FFFF00">   @endif</font>
&lt;/div&gt;
</pre>
