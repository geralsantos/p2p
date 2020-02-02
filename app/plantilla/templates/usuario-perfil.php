<template id="usuario-perfil">

    <div class="container-fluid">
        <div class="row" v-if="mascara">
            <div class="product-status-wrap tablas">
                <div class="tabla-cabecera">
                    <div class="text-loader"><span class="blanco">CARGANDO...</span></div>
                    <div class="loader"></div>
                </div>
            </div>
        </div>
        <div class="row" v-else>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap tablas">
                    <div class="tabla-cabecera">
                        <h3>Perfil de Usuario</h3>
                    </div>

                    <div class="row" >
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 formulario">
                            <form  v-on:submit.prevent="actualizar">
                                <input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"][0]["id"] ?>">
                                <div class="row">
                                    <div class="form-group col-lg-12">
                                        <label for="text-input" class="form-control-label">Nombre</label>
                                        <input type="text" class="form-control" v-model="nombre" required>
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <label for="text-input" class="form-control-label">Apellido</label>
                                        <input type="text"  name="text-input" v-model="apellido"   class="form-control" required>
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <label for="email-input" class="form-control-label">Correo</label>
                                        <input type="email" class="form-control" v-model="correo" required>
                                    </div>

                                    <div class="form-group col-lg-12" v-if="mostrar">
                                        <label for="text-input" class="form-control-label">Usuario</label>
                                        <input type="text"  v-model="usuario" class="form-control"  >
                                        <small class="blanco">Este será su usuario para ingresar al portal.</small>
                                    </div>

                                    <div class="form-group col-lg-12" v-if="mostrar">
                                        <label for="password-input" class="form-control-label">Contraseña</label>
                                        <input type="password" v-model="clave"  class="form-control"  >
                                    </div>

                                    <div class="form-group col-lg-12" style="text-align: center">
                                        <button type="submit" class="btn btn-success ">
                                            <i class="fa fa-send"></i> Actualizar
                                        </button>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</template>