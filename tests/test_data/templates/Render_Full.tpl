@function showUser($username, $userinfo) {
	<tr>
		<td>{{$username}}</td>
		<td>{{$userinfo['realname']}}</td>
		<td>{{$userinfo['age']}}</td>
	</tr>
@}
<html>
	<body>
		<h1>User list</h1>
		<p>Welcome! You are logged in as <i>{{$username}}</i>.</p>

		<h2>User list</h2>
		<table>
			<tr>
				<th>Username</th><th>Realname</th><th>Age</th>
			</tr>

@foreach($users as $user=>$userinfo):
@	showUser($user, $userinfo);		
@endforeach

		</table>
	</body>
</html>
