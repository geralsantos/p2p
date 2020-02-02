<template id="ventas">
    <div class="container-fluid" v-if="mostrar">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap tablas">
                    <div class="tabla-cabecera">
                        <h3>PROCESO DE VENTA</h3>
                        <span class="blanco">Selecciona los jugadores que quieras vender. Solo puedes realizar 5 ventas.</span>
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
                    <div class="row" v-else>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="product-status-wrap tablas">
                                <h4 style="float:left;">Lista de Jugadores</h4><h4 style="float:right;"  class="blanco">Cantidad de ventas: {{contador_ventas}}</h4>
                                <table>
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Nombre</th>
                                    <th>Posición</th>
                                    <th>Altura</th>
                                    <th>Overall</th>
                                    <th>PES DB 2019</th>
                                    <th>Precio de venta</th>

                                    <th>Seleccionar</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr v-for="jugador, index in jugadores">
                                    <td>{{index+1}}</td>
                                    <td>{{jugador.nombre}}</td>
                                    <td>{{jugador.posicion}}</td>
                                    <td>{{jugador.altura}}</td>
                                    <td>{{jugador.overall}}</td>
                                    <td><a href="http://pesdb.net/pes2019/" target="_blank" class="pd-setting-ed">Ver</a></td>
                                    <td>{{jugador.valor_venta}}</td>

                                    <td>
                                        <div class="group">
                                            <button class="btn btn-primary" v-on:click="vender(jugador)">Vender</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>


                            </div>
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
                        <h3>Proceso de ventas</h3>
                        <p class="blanco">{{mensaje}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>