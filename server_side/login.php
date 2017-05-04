<?php
include("header.php");
?>

<body>

	<div class="top-content">
	        	
	            <div class="inner-bg">
	                <div class="container">
	                    
	                    <div class="row">
	                        <div class="col-sm-5">
	                        	
	                        	<div class="form-box">
		                        	<div class="form-top">
		                        		<div class="form-top-left">
		                        			<h3>Login to our site</h3>
		                            		<p>Enter username and password to log on:</p>
		                        		</div>
		                        		<div class="form-top-right">
		                        			<i class="fa fa-lock"></i>
		                        		</div>
		                            </div>
		                            <div class="form-bottom">
					                    <form role="form" action="" method="post" id="login-form" class="login-form">
					                    	<div style="display:none" id="wrong_credentials" class="alert-warning form-group">
                    							<h3>Wrong Credentials!</h3>
                							</div>
					                    	<div class="form-group">
					                    		<label class="sr-only" for="email">Email</label>
					                        	<input type="email" name="email" placeholder="Email..." class="form-email form-control" id="email">
					                        </div>
					                        <div class="form-group">
					                        	<label class="sr-only" for="password">Password</label>
					                        	<input type="password" name="password" placeholder="Password..." class="form-password form-control" id="password">
					                        </div>
					                        <button type="submit" class="btn">Sign in!</button>
					                    </form>
				                    </div>
			                    </div>
		                        
	                        </div>
	                        
	                        <div class="col-sm-1 middle-border"></div>
	                        <div class="col-sm-1"></div>
	                        	
	                        <div class="col-sm-5">
	                        	
	                        	<div class="form-box">
	                        		<div class="form-top">
		                        		<div class="form-top-left">
		                        			<h3>Sign up now</h3>
		                            		<p>Fill in the form below to get instant access:</p>
		                        		</div>
		                        		<div class="form-top-right">
		                        			<i class="fa fa-pencil"></i>
		                        		</div>
		                            </div>
		                            <div class="form-bottom">
					                    <form role="form" action="" method="post" id="reg-form" class="reg-form">
					                    	<div class="form-group">
					                        	<label class="sr-only" for="email">Email</label>
					                        	<input type="email" name="email" placeholder="Email..." class="form-email form-control" id="email">
					                        </div>
					                        <div class="form-group">
					                        	<label class="sr-only" for="password">Password</label>
					                        	<input type="password" name="reg-password" placeholder="Password..." class="form-password form-control" id="reg-password">
					                        </div>
					                        <div class="form-group">
					                        	<label class="sr-only" for="confirm_password">Password</label>
					                        	<input type="password" name="confirm_password" placeholder="Password..." class="form-password form-control" data-rule-equalTo="#reg-password" id="confirm_password">
					                        </div>				                        
					                        <div class="form-group">
					                        	<label class="sr-only" for="user_bio">About yourself</label>
					                        	<textarea name="user_bio" placeholder="About yourself..." 
					                        				class="form-about-yourself form-control" id="user_bio"></textarea>
					                        </div>
					                        <button type="submit" class="btn">Sign me up!</button>
					                    </form>
				                    </div>
	                        	</div>
	                        	
	                        </div>
	                    </div>
	                    
	                </div>
	            </div>
	            
	        </div>

</body>


    <script type="text/javascript">
    //---------------------------------------------Send the ajax request
    	$('#login-form').validate();
    	$('#login-form').submit( function (e){

			e.preventDefault();    		
    		$info = $(this).serializeArray();

			var request = $.ajax({
			  url: "../ajax/attemptLogin.php",
			  method: "POST",
			  data: $info,
			  dataType: "html"
			});
			 
			request.done(function( msg ) {
			  if(msg == 'true') {
					window.location = "index.php";
				} else if(msg == 'false') {
					$('#wrong_credentials').show();
				}
			});

			request.fail(function( jqXHR, textStatus ) {
			  alert( "Request failed: " + textStatus );
			});
    	});

    	$('#reg-form').validate();
    	$('#reg-form').submit( function (e){

			e.preventDefault();    		
    		$info = $(this).serializeArray();

			var request = $.ajax({
			  url: "../ajax/attemptRegister.php",
			  method: "POST",
			  data: $info,
			  dataType: "html"
			});
			 
			request.done(function( msg ) {
				if(msg == 'true') {
					window.location = "index.php";
				} else if(msg == 'false') {
					alert('Ooops! Something went wrong!');
				}
			});
			 
			request.fail(function( jqXHR, textStatus ) {
			  alert( "Request failed: " + textStatus );
			});
    	});


    </script>

