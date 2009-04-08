[[
if (!function_exists('helperBtnAction')) {
   function helperBtnAction($action, $id, $icon) {
      echo('<a href="?action='.$action.'&id='.$id.'">');
      echo('<img src="ico/'.$icon.'.png" alt="'.$icon.'" border="0" />');
      echo('</a>');
   };
};
]]
<h1>User list</h1>

<p>Hello {{$username}}, here's a list of all the users:</p>

<div id="acocunts">
   @if (count($accounts) <= 0):
      No accounts found.
   @else:
      <table>
         <tr>
            <th>&nbsp;</th>
            <th>Username</th>
            <th>Full naam</th>
         </tr>
         @foreach ($accounts as $account):
            <tr>
               <td>[[helperBtnAction('account.edit', $account['id'], 'edit')]]</td>
               <td>{{$account['username']}}</td>
               <td>{{$account['realname']}}</td>
            </tr>
         @endforeach
         <tr>
            <td>{{helperBtnAction('account.add', '', 'add')}}</td>
            <td colspan="4">&nbsp;</td>
         </tr>
      </table>
   @endif
</div>
