<div id="commentSection">
	<?php
		//TODO test if $type is user or session, if not dont print comment section
		
		$dbConnection = new mysqli('localhost', 'chronoWrite', 'password', 'chronosynk');
		
		//if needed, insert comment
		if (isset($_GET['commenting']))
		{
			$stmt = $dbConnection->prepare('insert into comment (parentID, text, type, userID) values (?, ?, ?, ?)');
			$stmt->bind_param('issi', $_GET[$type], $_GET['comment'], $type, $_SESSION['userID']);
			$stmt->execute();
			$result = $stmt->get_result();
			header('Location: ./?'.$type.'='.$_GET[$type]);
		}
		//if delete comment
		if (isset($_GET['deleteComment']))
		{
			//TODO
			/*
			select * from comment where commentID = ?
			if ($row[userID] != $_SESSION['userID'])//or admin/mod
			{
				echo 'You cannot delete this comment';
			}
			*/
			$stmt = $dbConnection->prepare('
				delete from comment where commentID = ? and type = ?');
			$stmt->bind_param('is', $_GET['deleteComment'], $type);
			$stmt->execute();
			header('Location: ./?'.$type.'='.$_GET[$type]);
		}
		
		//INSERT INTO session (title, description, start, end, password, visibility) VALUES (?, ?, ?, ?, ?, ?)'
		
		//get comments
		//$stmt = $dbConnection->prepare('select * from comment where parentID = ? and type = ?');
		$stmt = $dbConnection->prepare('
			select distinct(user.username), user.userid, text, comment.timestamp, commentid, parentid, leader
			from comment
			inner join user
			on comment.userID = user.userID
            inner join session
            on comment.parentID = session.sessionID
			where comment.parentID = ? and type = ?
			order by timestamp desc');
		$stmt->bind_param('is', $_GET[$type], $type);
		$stmt->execute();
		$result = $stmt->get_result();
		
		echo '<h3>'.$result->num_rows.' Comments</h3>';
		//only show comment box if user is logged in
		if (isset($_SESSION['userID']))
		{
			echo '
				<form id="comment">
					<textarea name="comment"></textarea><br>
					<input name="'.$type.'" type="hidden" value="'.$_GET[$type].'">
					<input name="commenting" type="hidden" value="1">
					<button type="submit">Comment</button>
				</form>';
		}
		
		//loop for each comment selected
		while($row = $result->fetch_assoc())
		{
			//print comment
			echo '
					<div class=comment>
						<a href="/chronosynk/user/?user='.$row['userid'].'">'.$row['username'].'</a> - '.$row['timestamp'].'<br/>
						<p>'.htmlspecialchars($row['text']).'</p>';
			//print link to be able to delete (hide) comment if user is logged in and either made the comment or is the owner of the profile
			if (empty($_GET['user']))
			{
				$_GET['user'] = 0;
			}
			//if user is logged in and...
			if (isset($_SESSION['userID']))
			{
				//if mod/admin
				$admin = $_SESSION['permission'] > 0;
				//if user made comment
				$user = $row['userid'] == $_SESSION['userID'];
				//if comment is on users profile
				$profile = isset($_GET['user']) && $_GET['user'] == $_SESSION['userID'];
				//if comment was made on session controlled by user
				$session = isset($_GET['session']) && $_GET['user'] == $_SESSION['userID'];
				if ($admin || $user || $profile || $session)
				{
					echo '<a href="./?'.$type.'='.$_GET[$type].'&deleteComment='.$row['commentid'].'&type='.$type	.'">delete</a>';
				}	
			}
			echo '
					</div>';
		}
		
	?>
</div>