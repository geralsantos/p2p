<?php

 ?>
 <div class="container-fluid" style="overflow:auto;height:100%;">
     <div class="row">
     <br>
         <br>

         <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12"></div>
         <div class="col-md-4 col-md-4 col-sm-4 col-xs-12">
             <div class="text-center custom-login blanco">
             <img class="" src="<?php echo ASSETS ?>/img/logo/logo.png" alt="">
                 <h3>PULPO MASTERS</h3>
                 <p>Ingresa y crea tu propia liga de fútbol</p>
             </div>
             <div class="hpanel">
                 <div class="panel-body">
                     <form action="<?php $this->url('index') ?>" id="loginForm" method="post">
                         <div class="form-group">
                             <label class="control-label" for="username" style="color:#737373">Usuario</label>
                             <input type="text" placeholder="" title="" required="" value="" name="usuario" id="usuario" class="form-control">
                             <span class="help-block small" >Por favor, Ingrese su usuario</span>
                         </div>
                         <div class="form-group">
                             <label class="control-label" for="password" style="color:#737373">Contraseña</label>
                             <input type="password" title="La clave debe ser mayor a 5 caracteres" placeholder="" required="" value="" name="contrasena" id="contrasena" class="form-control">
                             <span class="help-block small">Por favor, ingrese su contaseña</span>
                         </div>
                         <!-- <div class="checkbox login-checkbox">
                             <label> <input type="checkbox" class="i-checks">  </label>
                             <p class="help-block small">(if this is a private computer)</p>
                         </div> -->
                         <button  class="btn btn-success btn-block loginbtn">Ingresa</button>
                         <a class="btn btn-success btn-block" href="<?php $this->url('registro') ?>" >Regístrate</a>
                         <div class="social-button">
                           <input type="hidden" name="tipo_acceso" value="login_facebook">

                        <a href="<?php echo $loginUrl; ?>"><button type="button" class="btn social facebook btn-flat btn-addon mb-3">
                                 <i class="fa fa-facebook"></i> Ingresa con facebook</button></a>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
         <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
     </div>
     <br>
       <br>
       <br>
     <div class="" >

         <br>
         <br>
         <br>
     </div>

 </div>
