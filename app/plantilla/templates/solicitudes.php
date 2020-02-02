<template id="solicitudes">
    <div class="container-fluid" v-if="mostrar">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap tablas">

                    <div class="tabla-cabecera">
                        <h3>Solicitudes de compra</h3>
                    </div>
                    <div class="row" v-if="loader">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="loader"></div>
                            <br>
                            <div class="text-loader"><span class="blanco">Procesando...</span></div>
                            <br>
                            <div class="text-loader"><span class="blanco"> Este proceso demorará un poco, por favor no recargar la página hasta que se termine el proceso.</span></div>
                        </div>
                    </div>
                    <div v-else>
                    <table>
                        <thead>
                            <tr class="text-center">
                                <th>Avatar</th>
                                <th>Solicitante</th>
                                <th>Jugador</th>
                                <th>Precio del jugador</th>
                                <th>PES DB 2019</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr v-for="solicitud in solicitudes">
                                <th><img :src="'../app/cargas/equipos_avatar/'+solicitud.solicitante_avatar"></th>
                                <td>{{solicitud.solicitante_nombre}}</td>
                                <td>{{solicitud.jugador_nombre}}</td>
                                <td>{{solicitud.jugador_valor}}</td>
                                <td><a href="http://pesdb.net/pes2019/" target="_blank" class="pd-setting-ed">Ver</a></td>
                                <td ><button title="Vender" class="btn btn-primary" @click="vender(solicitud)">Vender</button> <button title="Vender" class="btn btn-danger" @click="rechazar(solicitud)">Rechazar</button></td>
                            </tr>
                        </tbody>
                    </table>
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
                        <h3>Solicitudes de compra</h3>
                        <p class="blanco">{{mensaje}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
