<template id="ligas-lista">
    <div class="container-fluid" v-if="mostrar">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap tablas">
                    <div class="tabla-cabecera">
                    <h3>Ligas en las que has participado</h3>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Avatar</th>
                                <th>Nombre</th>
                                <th>Temporada</th>
                                <th>Participantes</th>
                                <th>Fecha de Creación</th>
                                <th>Estado</th>

                            </tr>
                        </thead>
                        <tbody class="center">

                            <tr v-for="liga in ligas">
                                <th><img src="../images/logo-liga.png" alt=""></th>
                                <td>{{liga.nombre}}</td>
                                <td>{{liga.temporada}}</td>
                                <td>{{liga.nro_participantes}}</td>
                                <td>{{liga.fecha_creacion}}</td>
                                <td>{{liga.estado_liga}}</td>

                            </tr>
                        </tbody>
                    </table>
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
                        <h3>Ligas en las que has participado</h3>
                        <p class="blanco">{{mensaje}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>