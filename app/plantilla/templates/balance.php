<template id="balance">
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
                        <h3>Balance</h3>
                    </div>
                    <div class="row" >
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"></div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 formulario">
                            <label style="color:white;" class="">Selecciona tu equipo</label>
                            <select style="border-radius:15px;" v-model="equipo" class="form-control" @change="cargar_premios()">
                                <option v-for="equipo in equipos" :value="equipo">{{equipo.nombre}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12" style="margin-bottom:1px;">
                            <div class="admin-content analysis-progrebar-ctn res-mg-t-30">
                                <h4 class="text-left text-uppercase"><b>Premios</b></h4>
                                <div class="row vertical-center-box vertical-center-box-tablet">
                                    <div class="text-left col-xs-3 mar-bot-15">
                                        <label class="label bg-green" style="font-size: 13px;"> <i class="fa fa-level-up" aria-hidden="true"></i> Total</label>
                                    </div>
                                    <div class="col-xs-9 cus-gh-hd-pro">
                                        <h2 class="text-right no-margin">{{premio}} $</h2>
                                        <button class="btn-success btn-md" @click="ver_premios()">Ver Premios</button>
                                    </div>
                                </div>
                                <div class="progress progress-mini">
                                    <div style="width: 100%;" class="progress-bar progress-bar-success bg-green"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="admin-content analysis-progrebar-ctn">
                                <h4 class="text-left text-uppercase"><b>Gastos</b></h4>
                                <div class="row vertical-center-box vertical-center-box-tablet">
                                    <div class="col-xs-3 mar-bot-15 text-left">
                                        <label class="label bg-red" style="font-size: 13px;"> <i class="fa fa-level-down" aria-hidden="true"></i> Total</label>
                                    </div>
                                    <div class="col-xs-9 cus-gh-hd-pro">
                                        <h2 class="text-right no-margin">{{gasto}} $</h2>
                                        <button class="btn-danger btn-md" @click="ver_gastos()">Ver Gastos</button>
                                    </div>
                                </div>
                                <div class="progress progress-mini">
                                    <div style="width: 100%;" class="progress-bar bg-red"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="admin-content analysis-progrebar-ctn">
                                <h4 class="text-left text-uppercase"><b>Saldo Disponible</b></h4>
                                <div class="row vertical-center-box vertical-center-box-tablet">
                                    <div class="col-xs-3 mar-bot-15 text-left">
                                        <label class="label bg-blue" style="font-size: 13px;"> <i class="fa fa-money" aria-hidden="true"></i> Total</label>
                                    </div>
                                    <div class="col-xs-9 cus-gh-hd-pro">
                                        <h2 class="text-right no-margin">{{saldo_disponible}} $</h2>
                                    </div>
                                </div>
                                <div class="progress progress-mini">
                                    <div style="width: 100%;" class="progress-bar bg-blue"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="mostrar_premios">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="product-status-wrap listado-equipos">
                                <h4>Listado de Premios</h4>
                                <div class="add-product">

                                </div>
                                <table>
                                    <thead>
                                        <th>
                                            <div class="text-center">LIGA</div>
                                        </th>
                                         <th>
                                            <div class="text-center">TEMPORADA</div>
                                        </th>
                                        <th>
                                            <div class="text-center">DESCRIPCIÓN</div>
                                        </th>
                                        <th>
                                            <div class="text-center">MONTO</div>
                                        </th>


                                    </thead>
                                        <tr v-for="premio in premios">
                                            <td>
                                                <div class="text-center">{{premio.liga}}</div>
                                            </td>
                                             <td>
                                                <div class="text-center">Temporada N° {{premio.numero_temporada}}</div>
                                            </td>
                                            <td>
                                                <div class="text-center">{{premio.descripcion}}</div>
                                            </td>
                                            <td>
                                                <div class="center-div">{{premio.premio}}</div>
                                            </td>

                                        </tr>
                                        <tr v-if="premios.length == 0"><td class="text-center" colspan="4">No hay premios registrados</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="mostrar_gastos">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="product-status-wrap listado-equipos">
                                <h4>Listado de Gastos</h4>
                                <div class="add-product">

                                </div>
                                <table>
                                    <thead>
                                        <th>
                                            <div class="text-center">LIGA</div>
                                        </th>
                                         <th>
                                            <div class="text-center">TEMPORADA</div>
                                        </th>
                                        <th>
                                            <div class="text-center">DESCRIPCIÓN</div>
                                        </th>
                                        <th>
                                            <div class="text-center">MONTO</div>
                                        </th>
                                    </thead>
                                        <tr v-for="gasto in gastos">
                                            <td>
                                                <div class="text-center">{{gasto.liga}}</div>
                                            </td>
                                             <td>
                                                <div class="text-center">Temporada N° {{gasto.numero_temporada}}</div>
                                            </td>
                                            <td>
                                                <div class="text-center">{{gasto.descripcion}}</div>
                                            </td>
                                            <td>
                                                <div class="text-center">{{gasto.gasto}}</div>
                                            </td>

                                        </tr>
                                        <tr v-if="gastos.length == 0"><td class="text-center" colspan="4">No hay gastos registrados</td></tr>
                                </table>
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
                        <h3>Balance</h3>
                        <p class="blanco">{{mensaje}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>