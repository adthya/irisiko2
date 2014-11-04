<?php require('includes/config.php'); 
require('menuPlain.php'); 


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Welcome...</title>
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/main.css">
	<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
	<script type="text/javascript" src="js/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script> 
    <script type="text/javascript"> 
      $(document).ready(function(){
				
				name: {
					required: true,
					},
					
				comment: {
					required: true,
					},
				email: {
					required: true,
					}
				
			},
			messages: {
				comment: {
                   	required: 'field harus diisi',
					
                },
				email: {
                   	required: 'field harus diisi',
					
                },
				name: {
                    	required:'field harus diisi'
                    }
			
			},
			success: function(label) {
            label.text('OK!').addClass('valid');
			
         	}
		
		});
      });
    </script>
</head>
<body>


	<div id="wrapper">
		<?php

	//if form has been submitted 
	
	if (isset($_POST['submit'])){

		$_POST = array_map( 'stripslashes', $_POST );

		//collect form data
		extract($_POST);
		
		
		//very basic validation

		if($name ==''){
			$error[] = 'Please enter the your name.';
		}
		if($email ==''){
			$error[] = 'Please enter your email.';
		}
		if($comment ==''){
			$error[] = 'Please enter the coment.';
		}

		if(!isset($error)){
		
			try {

				//insert into database
				$stmt = $db->prepare('INSERT INTO comment_field (name,email,comment,submission_date) VALUES ( :name, :email, :comment, :submission_date)') ;
				$stmt->execute(array(
					
					':name' => $name,
					':email' => $email,
					':comment' => $comment,
					':submission_date' => date('Y-m-d H:i:s')
				));

				//redirect to index page
				header('Location: index.php?action=added');
				exit;

			} catch(PDOException $e) {
			    echo $e->getMessage();
			}

		}
//check for any errors
	if(isset($error)){
		foreach($error as $error){
			echo '<p class="error">'.$error.'</p>';
		}
	}
	
	}

	?>
	
	
	<form action='' method='post'>
        <p><label>Name</label></br><input type="text" name="name" placeholder="Name"  id="name"/></p>
		<p><label>Email Address</label></br><input type="text" name="email" placeholder="Email Adress"  /></p>
		<p><label>Comment</label></br><textarea cols="100" rows="8" type="text" name="comment" placeholder="Comment"></textarea>	
		
		<p><input type='submit' name='submit' value='Submit'></p>

	</form>
			<?php
	function time2str($ts)
{
    if(!ctype_digit($ts))
        $ts = strtotime($ts);

    $diff = time() - $ts;
    if($diff == 0)
        return 'now';
    elseif($diff > 0)
    {
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 60) return 'just now';
            if($diff < 120) return '1 minute ago';
            if($diff < 3600) return floor($diff / 60) . ' minutes ago';
            if($diff < 7200) return '1 hour ago';
            if($diff < 86400) return floor($diff / 3600) . ' hours ago';
        }
        if($day_diff == 1) return 'Yesterday';
        if($day_diff < 7) return $day_diff . ' days ago';
        if($day_diff < 31) return ceil($day_diff / 7) . ' weeks ago';
        if($day_diff < 60) return 'last month';
        return date('F Y', $ts);
    }
    else
    {
        $diff = abs($diff);
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 120) return 'in a minute';
            if($diff < 3600) return 'in ' . floor($diff / 60) . ' minutes';
            if($diff < 7200) return 'in an hour';
            if($diff < 86400) return 'in ' . floor($diff / 3600) . ' hours';
        }
        if($day_diff == 1) return 'Tomorrow';
        if($day_diff < 4) return date('l', $ts);
        if($day_diff < 7 + (7 - date('w'))) return 'next week';
        if(ceil($day_diff / 7) < 4) return 'in ' . ceil($day_diff / 7) . ' weeks';
        if(date('n', $ts) == date('n') + 1) return 'next month';
        return date('F Y', $ts);
    }
}
			$stmt3 = $db->prepare('SELECT count(*) from comment_field');
			$stmt3->execute();
			$count=$stmt3->fetch();
			$total = $count[0];
			echo '<p><b>('.$count[0].') Guests</b></p></br>';
			echo '=====================================================================================';
			$stmt2 = $db->prepare('SELECT * FROM  `comment_field` ORDER BY commentID DESC LIMIT 0 , 10');
			$stmt2->execute();
			while($row2 = $stmt2->fetch()){
			$jo = time2str($row2['submission_date']);
			echo '<div>';
				echo '<p>'.$jo.'</p>';
				echo '<p>'.$row2['name'].'</p>';				
				echo '<p>'.$row2['comment'].'</p>';				
			echo '</div>';
			echo '<label>++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++';
			}
			
		?>
		
	
	</div>
</body>
</html>