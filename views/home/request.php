
<div class="request">
<?php

echo "<h3>You can request a demo here! </h3>";

echo Form::open(array('action' => 'index/home/request', 'method' => 'post'));

	echo Form::label('First Name:', 'first') . ' ';
	echo Form::input('first', '', array('class' => 'form-control'));
	echo '<br><br>';
	
	echo Form::label('Last Name:', 'last') . ' ';
	echo Form::input('last', '', array('class' => 'form-control'));
	echo '<br><br>';

	echo Form::label('Your Email:', 'email') . ' ';
	echo Form::input('email', '', array('class' => 'form-control'));
	echo '<br><br>';
	
	echo Form::label('Your Request:', 'request') . ' ';
	echo Form::input('request', '', array('class' => 'form-control'));
	echo '<br><br>';

	echo Form::button('frmbutton', 'Submit', array('class' => 'btn btn-default'));


echo Form::close();
?>
<p>Once you hit the "Submit" button, your request will automaticly send to us.</p>
<p>We will reach out to you as soon as possible.</p>
</div>
