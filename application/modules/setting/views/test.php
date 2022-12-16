<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>
                Mengelola Data User
            </h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <button class="btn btn-rounded btn-primary btn-sm">Data User</button>
                        <button class="btn btn-rounded btn-danger btn-sm" onclick="add_user(this)">Add User</button>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                          <form id="form_test" autocomplete="off">
                            <table class="table table-striped table-hover" id="user" style="table-layout: auto;">
                              <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Qty</th> 
                                </tr>
                              </thead>
                              <tbody>
                                <?php for ($i=1; $i <=5 ; $i++) { ?>
                                  <tr>
                                    <td>
                                      <input type="text" name="no_urut[]" value="<?php echo $i ?>">
                                    </td>
                                    <td><input type="text" name="qty[]" value="<?php echo $i+$i ?>"></td>
                                  </tr>
                                <?php } ?>
                              </tbody>
                            </table>
                            </form>
                            <div>
                              <button type="button" class="btn btn-danger btn-xs" onclick="test_data(this)">Test</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Basic Examples -->
        <!-- Exportable Table -->
      
        <!-- #END# Exportable Table -->
    </div>
</section>

<script type="text/javascript">
  function test_data()
  {
    $.ajax({
      url   : "<?php echo base_url('user/c_user/test_data')?>",
      type  : "POST",
      data  : $("#form_test").serialize(),
      dataType : "json",
      success : function(data)
      {
        
      }
    })
  }
</script>