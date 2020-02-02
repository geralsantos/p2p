<template id="negociaciones">
    <div class="container-fluid" v-if="mostrar">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap tablas">
                    <div class="tabla-cabecera">
                        <h3>Reporte de compras y ventas</h3>
                        <h4> Liga: {{datos_liga.nombre_liga}}</h4>
                        <h4> Equipo: {{datos_liga.nombre}}</h4>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
                            <div class="admin-content analysis-progrebar-ctn res-mg-t-30">
                                <h4 class="text-left text-uppercase"><b>Total Compras</b></h4>
                                <div class="row vertical-center-box vertical-center-box-tablet">
                                    <div class="text-left col-xs-3 mar-bot-15">
                                        <label class="label bg-red"> <i class="fa fa-level-down" aria-hidden="true"></i></label>
                                    </div>
                                    <div class="col-xs-9 cus-gh-hd-pro">
                                        <h2 class="text-right no-margin">{{total_compras}} $</h2>
                                    </div>
                                </div>
                                <div class="progress progress-mini">
                                    <div style="width: 100%;" class="progress-bar progress-bar-danger bg-red"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="admin-content analysis-progrebar-ctn">
                                <h4 class="text-left text-uppercase"><b>Total Ventas</b></h4>
                                <div class="row vertical-center-box vertical-center-box-tablet">
                                    <div class="col-xs-3 mar-bot-15 text-left">
                                        <label class="label bg-green"> <i class="fa fa-level-up" aria-hidden="true"></i></label>
                                    </div>
                                    <div class="col-xs-9 cus-gh-hd-pro">
                                        <h2 class="text-right no-margin">{{total_ventas}} $</h2>
                                    </div>
                                </div>
                                <div class="progress progress-mini">
                                    <div style="width: 100%;" class="progress-bar bg-green"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div style="display:flex">
                                <div  class="product-status-wrap col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                                    <button class="btn btn-danger" @click="ver_compras()">Ver de Compras</button>
                                </div>
                                <div class="product-status-wrap col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                                    <button class="btn btn-success" @click="ver_ventas()">Ver de Ventas</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="mostrar_compras" class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="product-status-wrap listado-equipos">
                                <h4>Listado de Compras</h4>
                                <table>
                                    <thead>
                                        <th>
                                            <div class="text-center">TEMPORADA</div>
                                        </th>
                                        <th>
                                            <div class="text-center">Nombre del Jugador</div>
                                        </th>
                                        <th>
                                            <div class="text-center">Overall</div>
                                        </th>
                                        <th>
                                            <div class="text-center">Precio</div>
                                        </th>
                                        <th>
                                            <div class="text-center">Fecha</div>
                                        </th>

                                    </thead>
                                    <tr v-for="compra in compras">
                                        <td>
                                            <div class="text-center">Temporada N° {{compra.numero_temporada}}</div>
                                        </td>
                                        <td>
                                            <div class="text-center">{{compra.nombre_jugador}}</div>
                                        </td>
                                        <td>
                                            <div class="center-div">{{compra.overall}}</div>
                                        </td>
                                        <td>
                                            <div class="center-div">{{compra.monto}} $</div>
                                        </td>
                                        <td>
                                            <div class="center-div">{{compra.fecha_edicion}}</div>
                                        </td>
                                    </tr>
                                    <tr v-if="compras.length == 0"><td class="text-center" colspan="5">No hay compras registradas</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row"  v-if="mostrar_ventas">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="product-status-wrap listado-equipos">
                                <h4>Listado de Ventas</h4>
                                <table>
                                    <thead>
                                        <th>
                                            <div class="text-center">TEMPORADA</div>
                                        </th>
                                        <th>
                                            <div class="text-center">Nombre del Jugador</div>
                                        </th>
                                        <th>
                                            <div class="text-center">Overall</div>
                                        </th>
                                        <th>
                                            <div class="text-center">Precio</div>
                                        </th>
                                        <th>
                                            <div class="text-center">Fecha</div>
                                        </th>

                                    </thead>
                                        <tr v-for="venta in ventas">
                                            <td>
                                                <div class="text-center">Temporada N° {{venta.numero_temporada}}</div>
                                            </td>
                                            <td>
                                                <div class="text-center">{{venta.nombre_jugador}}</div>
                                            </td>
                                            <td>
                                                <div class="center-div">{{venta.overall}}</div>
                                            </td>
                                            <td>
                                                <div class="center-div">{{venta.monto}} $</div>
                                            </td>
                                            <td>
                                                <div class="center-div">{{venta.fecha_edicion}}</div>
                                            </td>
                                        </tr>
                                        <tr v-if="ventas.length == 0"><td class="text-center" colspan="4">No hay ventas registradas</td></tr>
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
                        <h3>Reporte de compras y ventas</h3>
                        <p class="blanco">{{mensaje}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>