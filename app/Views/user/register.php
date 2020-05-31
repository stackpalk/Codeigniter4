<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $this->data['title']; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo site_url(); ?>AdminLTE/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo site_url(); ?>AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo site_url(); ?>AdminLTE/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition register-page">
  <div class="register-box">
    <div class="register-logo">
      <a href="#"><b><?php echo $this->data['title']; ?></b></a>
    </div>

    <?php

    if (session()->get('successmsg')) {
      // echo session()->get('successmsg');

      echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success</strong>' . session()->get('successmsg') . '
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
    }

    if (session()->get('failmsg')) {
      echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Error :-</strong>' . session()->get('failmsg') . '
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
    }

    //$validation=\Config\Services::validation();

    // if(isset($validation1)){
    //  // echo $validation->listErrors;
    //  print_r($validation1);
    // }

    ?>

    <div class="card">
      <div class="card-body register-card-body">
        <p class="login-box-msg"><?php echo $this->data['title']; ?></p>

        <?php echo form_open($this->data['action'], ['id' => 'registerFrom']) ?>
        <div class="input-group mb-3">
          <input type="text" name='fullname' value="<?= set_value('fullname') ?>" class="form-control" placeholder="Full name">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
          <span class="verror"><?php
                                if (isset($validation1['fullname'])) {
                                  echo $validation1['fullname'];
                                }
                                ?></span>
        </div>
        <div class="input-group mb-3">
          <input type="email" name='email' value="<?= set_value('email') ?>" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
          <span class="verror"><?php if (isset($validation1['email'])) {
                                  echo $validation1['email'];
                                } ?></span>
        </div>
        <div class="input-group mb-3">
          <input type="password" name='password' id='password' value="<?= set_value('password') ?>" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          <span class="verror"><?php if (isset($validation1['password'])) {
                                  echo $validation1['password'];
                                } ?></span>
        </div>
        <div class="input-group mb-3">
          <input type="password" name='password_again' value="" class="form-control" placeholder="Retype password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          <span class="verror"><?php if (isset($validation1['password_again'])) {
                                  echo $validation1['password_again'];
                                } ?></span>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary input-group">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
                I agree to the <a href="#">terms</a>
              </label>
            </div>
            <span class="verror"><?php if (isset($validation1['terms'])) {
                                    // echo $validation->listErrors;
                                    echo $validation1['terms'];
                                  } ?></span>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
          <!-- /.col -->
        </div>
        <?php echo form_close(); ?>



        <a href="<?php echo $this->data['loginlink']; ?>" class="text-center">I already have a membership</a>
      </div>
      <!-- /.form-box -->
    </div><!-- /.card -->
  </div>
  <!-- /.register-box -->

  <!-- jQuery -->
  <script src="<?php echo site_url(); ?>AdminLTE/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?php echo site_url(); ?>AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo site_url(); ?>AdminLTE/dist/js/adminlte.min.js"></script>

  <!-- jquery-validation -->
  <script src="<?php echo site_url(); ?>AdminLTE/plugins/jquery-validation/jquery.validate.min.js"></script>
  <script src="<?php echo site_url(); ?>AdminLTE/plugins/jquery-validation/additional-methods.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $.validator.setDefaults({
        submitHandler: function() {
          //alert( "Form successful submitted!" );
          return true;
        }
      });
      $('#registerFrom').validate({
        rules: {
          fullname: {
            required: true,
            minlength: 5,
          },
          email: {
            required: true,
            email: true,
          },
          password: {
            required: true,
            minlength: 5,
          },
          password_again: {
            equalTo: "#password"
          },
          terms: {
            required: true
          },
        },
        messages: {
          fullname: {
            required: "Please provide a full Name",
            minlength: "Your Full Name must be at least 5 characters long"
          },
          email: {
            required: "Please enter a email address",
            email: "Please enter a vaild email address"
          },
          password: {
            required: "Please provide a password",
            minlength: "Your password must be at least 5 characters long"
          },
          password_again: "Password must match confirm Password",
          terms: "Please accept our terms"
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
          error.addClass('invalid-feedback');
          element.closest('.input-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
      });
    });
  </script>

</body>

</html>