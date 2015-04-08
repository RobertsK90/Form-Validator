<?php

require_once('vendor/autoload.php');

use FormValidator\ErrorHandler\ErrorHandler;
use FormValidator\Validator\Validator;
use FormValidator\DatabaseWrapper\Database;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = new Database;
    $errorHandler = new ErrorHandler;
    $validator = new Validator($db, $errorHandler);

    $validation = $validator->validate($_POST, [
        'username' => 'required|maxlength:25|minlength:3|unique:users',
        'email' => 'email|minlength:7|required',
        'password' => 'required|minlength:5|confirmed'
    ]);
}

?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Validation Example</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
        <style>form {padding-top:20px;} button {margin-left:15px} .error {color:red}</style>
    </head>
    <body>
        <div class="container">
            <form action="Example.php" method="POST" role="form" novalidate>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="form-group">

                            <label for="username">Username</label>
                            <?php if (isset($validation) && ($validation->errors()->hasErrors('username'))): ?>
                                <p class="error"><?php echo $validation->errors()->first('username'); ?></p>
                            <?php endif; ?>
                            <input type="text" class="form-control username" name="username" placeholder="Enter Username">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <?php if (isset($validation) && ($validation->errors()->hasErrors('email'))): ?>
                                <p class="error"><?php echo $validation->errors()->first('email'); ?></p>
                            <?php endif; ?>
                            <input type="email" class="form-control" name="email" placeholder="Enter Email">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <?php if (isset($validation) && ($validation->errors()->hasErrors('password'))): ?>
                                <p class="error"><?php echo $validation->errors()->first('password'); ?></p>
                            <?php endif; ?>
                            <input type="password" class="form-control" name="password" placeholder="Enter Password">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="form-group">
                            <label for="password_confirmation">Repeat Password</label>
                            <?php if (isset($validation) && ($validation->errors()->hasErrors('password_confirmation'))): ?>
                                <p class="error"><?php echo $validation->errors()->first('password_confirmation'); ?></p>
                            <?php endif; ?>
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Repeat Password" autocomplete="off">
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-offset-3">
                        <div class="input-group">
                            <button type="submit" class="btn btn-primary">Validate The Form</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>

    </body>
</html>
