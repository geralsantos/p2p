<template id="equipos-administrar">
    <div class="container-fluid" v-if="mostrar">
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
                        <h3>Lista de equipos</h3>
                    </div>
                    <div class="row" v-if="loader">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="loader"></div>
                            <br>
                            <div class="text-loader"><span class="blanco">Procesando...</span></div>
                            <br>
                            <div class="text-loader"><span class="blanco"> Por favor, no recargar la página hasta que se termine el proceso.</span></div>
                        </div>
                    </div>
                    <div v-else>
                        <table>
                            <thead>
                                <tr class="text-center">
                                    <th>Avatar</th>
                                    <th>Nombre</th>
                                    <th>Liga</th>
                                    <th>N° de Jugadores</th>
                                    <th>Estado</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr v-for="equipo in equipos">
                                    <th><img :src="'../app/cargas/equipos_avatar/'+equipo.avatar"></th>
                                    <td>{{equipo.nombre}}</td>
                                    <td>{{equipo.nombre_liga}}</td>
                                    <td>{{equipo.jugadores_num}} jugadores</td>
                                    <td>{{equipo.estado_equipo}}</td>
                                    <td ><button title="eliminar" class="btn btn-danger" @click="eliminar(equipo)" v-if="equipo.eliminado==0" >Eliminar</button></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class=""><span class="blanco"> Una vez que elimines al equipo este no podrá regresar a la liga.</span></div>
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
                        <h3>Lista de equipos</h3>
                        <p class="blanco">{{mensaje}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>