<template id="portada-index">

    <div class="container-fluid" v-if="mostrar">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap tablas">
                    <div class="tabla-cabecera">
                        <h3>PES MASTER LIGA</h3>

                        <h4>Equipo: {{datos_liga.nombre}} </h4>
                    </div>

                    <div v-if="datos_liga.estado==2" class="tabla-cabecera text-center">
                        <p class="blanco">Falta seleccionar plantilla</p>
                    </div>
                    <div v-else>
                        <table>
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Nombre</th>
                                    <th>Posición</th>
                                    <th>Altura</th>
                                    <th>Overall</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr v-for="jugador, index in plantilla">
                                    <td>{{index+1}}</td>
                                    <td>{{jugador.nombre}}</td>
                                    <td>{{jugador.posicion}}</td>
                                    <td>{{jugador.altura}}</td>
                                    <td>{{jugador.overall}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="container-fluid"  v-else>
        <div class="row" v-if="usuario_activo==1" >
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
            <div class="col-md-6 col-md-6 col-sm-6 col-xs-12">
                <div class="text-center custom-login blanco" v-if="imagen">
                    <h3>No perteneces a ninguna Liga</h3>
                    <p>¡Únete o crea una liga para empezar!</p>
                   <!--  <div id="ball"></div>-->
                    <img v-if="imagen" class="img-carita-triste" src="<?php echo ASSETS ?>/img/logo/logo.png" alt="">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
        </div>
        <div class="row" v-else>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
            <div class="col-md-6 col-md-6 col-sm-6 col-xs-12">
                <div class="text-center custom-login blanco" v-if="usuario_activo==0">
                    <h3>No perteneces a ninguna Liga</h3>
                    <p>Renueva tu plan para seguir navegando </p>
                    <div class="text-center">
                        <a @click="openCulqi()" class="btn btn-success" style="color:white;">Renovar Plan</a>
                    </div>
                    <br>
                    <img  class="img-carita-triste" src="<?php echo ASSETS ?>/img/logo/logo.png" alt="">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
        </div>
    </div>

</template>
