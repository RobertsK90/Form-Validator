<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Fill out the form and validate without errors');

$I->amOnPage('/Example.php');
$I->fillField('username', 'myusername');
$I->fillField('email', 'generic@email.com');
$I->fillField('password', '12345');
$I->fillField('password_confirmation', '12345');
$I->click('.btn-primary');

$I->dontSee('p.error');
