<template id="equipos-configuracion">
    <div class="container-fluid" v-if="mostrar">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap tablas">
                    <div class="tabla-cabecera">
                        <h3>Mi Equipo</h3>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 formulario">
                        <form v-on:submit.prevent="guardar" id="formuploadajax">
                                <div class="row">

                                    <div class="form-group col-lg-12 text-center">
                                        <label >Avatar</label>
                                        <div class="file-button" class="form-control" >

                                            <img :src="'../app/cargas/equipos_avatar/'+avatar" alt="avatar" id="imagen_equipo">

                                            <input   type="file" id="archivo" name="archivo" value="archivo" class="form-control-file" accept="image/*">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12 blanco text-center">
                                        <label>Nombre del Equipo</label>
                                        <input class="form-control" type="text"   :readonly ="mostrar_nombre" v-model="nombre_equipo">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-12 text-center">
                                        <button class="btn btn-success" type="submit">Actualizar</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-12 text-center blanco">
                                       <span>Solo puedes modificar tu equipo antes del kickoff</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid"  v-else>
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
                        <h3>CONFIGURACIÓN EQUIPO</h3>
                        <p class="blanco">{{mensaje}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>