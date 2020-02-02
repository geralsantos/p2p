<template id="equipos-plantilla">
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
                        <h3>{{nombre_equipo}}</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Nombre</th>
                                <th>Posición</th>
                                <th>Altura</th>
                                <th>Overall</th>
                                <th>PES DB 2019</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr v-for="jugador, index in plantilla">
                                <td>{{index+1}}</td>
                                <td>{{jugador.nombre}}</td>
                                <td>{{jugador.posicion}}</td>
                                <td>{{jugador.altura}}</td>
                                <td>{{jugador.overall}}</td>
                                <td><a href="http://pesdb.net/pes2019/" target="_blank" class="pd-setting-ed">Ver</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>