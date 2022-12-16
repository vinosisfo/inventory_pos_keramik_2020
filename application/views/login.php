<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Rohadi</title>
    <!-- Favicon-->
    <link rel="icon" href="<?php echo base_url('assets/adminbsb/images/animation-bg.jpg') ?>" type="image/x-icon">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminbsb/node_modules/material-design-icons-iconfont/dist/material-design-icons.css') ?>">
    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url('assets/adminbsb/plugins/bootstrap/css/bootstrap.css')?>" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?php echo base_url('assets/adminbsb/plugins/node-waves/waves.css')?>" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?php echo base_url('assets/adminbsb/plugins/animate-css/animate.css')?>" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?php echo base_url('assets/adminbsb/css/style.css')?>" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo base_url('assets/izitoast/dist/css/iziToast.min.css')?>">
</head>

<body class="login-page" style="background:url(assets/adminbsb/images/animation-bg.jpg);
no-repeat fixed; background-size: cover;
 -webkit-background-size: cover; 
 -moz-background-size: cover; -o-background-size: cover;">
    <div class="login-box">
        <div class="logo">
            <!-- <a href="javascript:void(0);"><font color="blue"><h4>PROGRAM KELUARGA HARAPAN</font></h4></a> -->
        </div>
        <div class="card">
            <div class="body">
                <form id="f_login" autocomplete="off">
                    <div class="msg">Sign in</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="username" id="username" placeholder="Kode Pegawai" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <button class="btn btn-block bg-red waves-effect" type="button" id="login">SIGN IN</button>
                        </div>
                        <div class="col-xs-6">
                            <button class="btn btn-block bg-blue waves-effect" type="button" id="lupa" onclick="get_form_lupa(this)">Lupa Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_master"> 
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title judul"></h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">person</i>
                    </span>
                    <div class="form-line">
                        <input type="text" class="form-control" name="username_lupa" id="username_lupa" placeholder="Kode Pegawai" required autofocus>
                    </div>
                </div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">lock</i>
                    </span>
                    <div class="form-line">
                        <input type="password" class="form-control" name="password_lupa" id="password_lupa" placeholder="Password Baru" required>
                    </div>
                </div>
              </div>
              <div class="modal-footer">
                <div class="col-xs-4">
                    <button class="btn btn-block bg-red waves-effect" type="button" id="update_lupa" onclick="update_lupa(this)">Update</button>
                </div>
              </div>
          </div>
      </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/adminbsb/plugins/jquery/jquery.min.js')?>"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url('assets/adminbsb/plugins/bootstrap/js/bootstrap.js')?>"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url('assets/adminbsb/plugins/node-waves/waves.js')?>"></script>

    <!-- Validation Plugin Js -->
    <script src="<?php echo base_url('assets/adminbsb/plugins/jquery-validation/jquery.validate.js')?>"></script>

    <!-- Custom Js -->
    <script src="<?php echo base_url('assets/adminbsb/js/admin.js')?>"></script>
    <script src="<?php echo base_url('assets/adminbsb/js/pages/examples/sign-in.js')?>"></script>
    <script src="<?php echo base_url('assets/izitoast/dist/js/iziToast.min.js')?>"></script>
  <!-- endinject -->

    <script type="text/javascript">

    function update_lupa()
    {
      username = $("#username_lupa").val();
      password = $("#password_lupa").val();

      if((username=="") || (password==""))
      {
        error_msg()
        return false;
      }

      $.post('<?php echo base_url('Auth/update_password')?>',{username : username, password : password},function(data){
        if(data.pesan=="kosong"){
          alert("Kode Pegawai Tidak Ditemukan!!");
          $("#username_lupa").focus()
          return false;
        } else if(data.pesan=="ok"){
          alert("Password Berhasil Diupdate");
          $("#modal_master").modal("hide")
        }
      },"json")
      .fail(function(data){
        alert("Error!!")
      });
    }


    $("#login").click(function(){
      username = $("#username").val();
      password = $("#password").val();
      if(username=="" || password=="")
      {
        error_msg();
      }
      else
      {
        $.ajax({
          url     : "<?php echo base_url('Auth/login')?>",
          type    : "POST",
          data    : $("#f_login").serialize(),
          dataType: "json",
          success : function(data)
          {
            if(data.pesan=="ok"){
              location.replace('<?php echo base_url('home')?>')
            } else {
              error_msg();
            }
          }
        });
      }
    });

    function error_msg(){
      iziToast.error({
        title   : 'Error !!',
        message : 'Username Atau Password Salah',
        position: 'topCenter'
      });
    }

    function get_form_lupa()
    {
      $(".judul").html("Lupa Password")
      $("#modal_master").modal("show")
    }
  </script>
</body>

</html>