<template id="compras-otro-equipo">
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
                        <h3>PROCESO DE COMPRAS</h3>
                        <span class="blanco">Escoge un equipo de tu liga y selecciona los jugadores que desees comprar. Solo puedes realizar 5 compras</span>
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
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"></div>
                            <div class="form-group col-lg-4  col-md-4 col-sm-12 col-xs-12 text-center formulario">
                                <label style="" class="">Selecciona un equipo</label>
                                <select  class="form-control" v-model="equipo" @change="cargar_plantilla_otro_equipo()" >
                                    <option v-for="equipo in equipos" :value="equipo" >{{equipo.nombre}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" v-if="equipos">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="product-status-wrap tablas">

                                    <h4 style="float:left;">Lista de Jugadores</h4><h4 style="float:right;"  class="blanco">Cantidas compras: {{contador_compras}}</h4>
                                    <table >
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>Nombre</th>
                                                <th>Posición</th>
                                                <th>Altura</th>
                                                <th>Overall</th>
                                                <th>PES DB 2019</th>
                                                <th>Precio de compra</th>
                                                <th>Seleccionar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="jugador, index in plantilla">
                                                <td>{{index+1}}</td>
                                                <td>{{jugador.nombre}}</td>
                                                <td>{{jugador.posicion}}</td>
                                                <td>{{jugador.altura}}</td>
                                                <td>{{jugador.overall}}</td>
                                                <td><a href="http://pesdb.net/pes2019/" target="_blank" class="pd-setting-ed">Ver</a></td>
                                                <td>{{jugador.valor_compra}}</td>
                                                <td>
                                                    <div class="group">
                                                        <button class="btn btn-success loginbtn" v-on:click="comprar(jugador)">Comprar</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                        </table>
                                    <div><span class="blanco">Una vez realizada la solicitud de compra, el usuario debe aceptar la solicitud para que se concrete la transacción. Si el usuario no aprueba tu solicitud dentro del período de negociaciones, la misma se eliminará y podrás realizar otra compra.</span></div>

                                </div>
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
                        <h3>Proceso de compras</h3>
                        <p class="blanco">{{mensaje}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>