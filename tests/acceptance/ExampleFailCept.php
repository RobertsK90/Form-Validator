<?php 
$I = new AcceptanceTester($scenario);

$I->wantTo('Fill out the form and see validation errors');

$I->amOnPage('/Example.php');
$I->fillField('.username', 'myusername');
$I->fillField('email', 'notanemail');
$I->fillField('password', '12345');
$I->fillField('password_confirmation', '12345');
$I->click('.btn-primary');

$I->See('Valid email address must be provided');
