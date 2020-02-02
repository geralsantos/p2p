<template id="liga-crear">
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
                        <h3>Crear Liga</h3>
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
                        <div class="row" v-if="!codigo">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 formulario">
                                <form v-on:submit.prevent="guardar" v-if="bloque_liga">
                                    <div class="row">
                                        <div class="form-group col-lg-12 text-center">
                                            <label>Ingresa el nombre de la Liga</label>
                                            <input v-model="nombre_liga" class="form-control" required>
                                        </div>
                                        <div class="form-group col-lg-12 text-center">
                                            <label>Ingresa el nombre de tu equipo</label>
                                            <input  v-model="nombre_equipo" class="form-control" required>
                                        </div>
                                        <div class="form-group col-lg-12 text-center">
                                            <label>Selecciona el número de particpantes de tu liga</label>
                                            <select style="border-radius:15px;" class="form-control" v-model="nro_participantes" required>
                                                <option value="8">8</option>
                                                <option value="16">16</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success btn-md">Registrar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row" v-if="codigo">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 formulario">
                                <div class="liga-registrada text-center">
                                    <p class="liga-registrada">Tu código de Liga es: {{codigo}}</p>
                                    <p class="liga-registrada">Comparte el código con tus amigos e invítalos a unirse a tu liga.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>