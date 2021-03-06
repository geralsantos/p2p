<?php
class modeloPortada extends MySQL{

  public function getSesion($id){

    $usuario = $this->executeQuery("select * from usuarios where id=:id",array("id"=>$id));
    if($usuario){

      $sql = "SELECT b.*, (SELECT t.estado FROM temporada_liga t WHERE t.liga_id=b.id ORDER BY t.id DESC LIMIT 1) as temporada_estado,
      (SELECT t.numero FROM temporada_liga t WHERE t.liga_id=b.id ORDER BY t.id DESC LIMIT 1) as temporada_numero,
      c.nombre as equipo, c.id as equipo_id FROM liga_participantes a LEFT JOIN liga b on a.liga_id=b.id LEFT JOIN equipos c ON b.id=c.liga_id
      WHERE b.estado<>0 AND  c.usuario_creacion = ".$usuario[0]["id"]. " ORDER BY b.fecha_creacion LIMIT 1";

      $liga =  $this->executeQuery($sql);

      if($liga){
          $usuario[0]["solicitudes"]='';
          $usuario[0]["temporada_numero"] = $liga[0]["temporada_numero"];
          $usuario[0]["liga"]= $liga[0]["nombre"];
          $usuario[0]["codigo"]= (isset($liga[0]["codigo"]))?$liga[0]["codigo"]:'';
          $usuario[0]["equipo"]= $liga[0]["equipo"];
          if($liga[0]["estado"]== 1){
              if($liga[0]["temporada_estado"]== 1){
                $usuario[0]["estado_liga"] = 'Activa';
              }elseif($liga[0]["temporada_estado"]== 2){
                $usuario[0]["estado_liga"] = 'En espera del sorteo de los partidos';
              }elseif($liga[0]["temporada_estado"]== 3){

                $solicitudes =  $this->executeQuery("SELECT * FROM jugadores_subastas WHERE otro_equipo_id = ".$liga[0]["equipo_id"]. " AND estado = 1");
                if($solicitudes){
                  $usuario[0]["solicitudes"] ="Tienes nuevas solicitudes de compra.";
                }

                $usuario[0]["estado_liga"] = 'Período de Negociaciones';
              }elseif($liga[0]["temporada_estado"]== 4){
                $usuario[0]["estado_liga"] = 'Período de subastas';
              }elseif($liga[0]["temporada_estado"]== 5){
                $usuario[0]["estado_liga"] = 'Período de compras extemporáneas';
              }else{
                $usuario[0]["estado_liga"] = 'Activa';
              }

          }elseif($liga[0]["estado"]== 3){
              $usuario[0]["estado_liga"] = 'En espera de sorteo';
          }elseif($liga[0]["estado"]== 2){
              $usuario[0]["estado_liga"] = 'Proceso de Kickoff';
          }elseif($liga[0]["estado"]== 0){
              $usuario[0]["estado_liga"] = 'Inactiva';
          }else{
              $usuario[0]["estado_liga"] = 'Desconocido';
          }
          $res = $this->executeQuery("SELECT * FROM temporada_liga WHERE liga_id = ". $liga[0]["id"]." ORDER BY fecha_creacion DESC LIMIT 1");
          if($res){
            $usuario[0]["temporada_liga_estado"] = $res[0]["estado"];
          }else{
            $usuario[0]["temporada_liga_estado"] = '';
          }
      }else{
          $usuario[0]["temporada_numero"] ='';
          $usuario[0]["solicitudes"]='';
          $usuario[0]["liga"]= 'Ninguna';
          $usuario[0]["equipo"]= 'Ninguno';
          $usuario[0]["codigo"]= '';
          $usuario[0]["estado_liga"] = 'Liga no registrada';
          $usuario[0]["temporada_liga_estado"] = '';
      }
  }

    unset($_SESSION["usuario"]);

    return $usuario;

}
  public function getModulos(){
    echo "getModulos";
      // $modulos = $this->selectAll("modulos");
      // $modulos_det = $this->selectAll("modulos_det");
      // return [$modulos,$modulos_det];
  }

  public function crear_liga($tabla, $equipo, $liga){

    $where = array('usuario_id' => $_SESSION["usuario"][0]["id"], 'estado'=>1);

    $res = $this->selectRowData( $tabla,'*', $where );
    if($res){
      //si ya existe un usuario con una liga
      echo json_encode(array("resultado"=>false, "titulo"=>"Alerta", "mensaje"=>"Ya tienes una liga activa", "accion"=>"warning"));

    }else{
      try{
        $this->beginTransaction();
        $codigo = createRandomPassword();
        $liga["codigo"] = $codigo;
        $liga["estado"] = 3;
        $liga['fecha_creacion'] = date("Y-m-d H:i:s");
        $liga['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
        $liga['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
        $liga['usuario_id'] = $_SESSION["usuario"][0]["id"];
        $liga['plan_id'] = $_SESSION["usuario"][0]["plan_id"];
        //solo puedes crear una liga

        $res = $this->insertData( $tabla, $liga );

        if($res){
          $idliga = $this->getLastId('liga');

          $valores['usuario_id'] = $_SESSION["usuario"][0]["id"];
          $valores["liga_id"] = $idliga[0]["id"];
          $valores['fecha_creacion'] = date("Y-m-d H:i:s");
          $valores['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
          $valores['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
          $res = $this->insertData( 'liga_participantes', $valores);

          if ($res){
            $idligaParticipantes = $this->getLastId('liga_participantes');
            $equipo["liga_participantes_id"] = $idligaParticipantes[0]["id"];
            $equipo['fecha_creacion'] = date("Y-m-d H:i:s");
            $equipo['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
            $equipo['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
            $equipo['liga_id'] = $idliga[0]["id"];
            $equipo['estado'] = 2;
            $res = $this->insertData( 'equipos', $equipo );

            if($res){
              $res = $this->updateData( 'usuarios', array("is_admin"=>1,"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("id"=> $_SESSION["usuario"][0]["id"]));

              if($res){
                //insertar saldo
                $saldos["liga_participantes_id"] = $idligaParticipantes[0]["id"];
                $saldos['fecha_creacion'] = date("Y-m-d H:i:s");
                $saldos['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
                $saldos['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
                $saldos['saldo'] = 0;
                $res = $this->insertData( 'saldo_disponible', $saldos );

                if($res){
                  echo json_encode(array("resultado"=>true, "titulo"=>"Liga Registrada", "mensaje"=>"La liga se ha creado exitosamente.", "accion"=>"success", "codigo"=>$codigo));

                }else{

                  echo json_encode(array("resultado"=>false, "titulo"=>"Error", "mensaje"=>"Un error ha ocurrido", "accion"=>"danger"));
                }

              }else{

                echo json_encode(array("resultado"=>false, "titulo"=>"Alerta", "mensaje"=>"Error en registro1", "accion"=>"warning"));
              }

            }else{

              echo json_encode(array("resultado"=>false, "titulo"=>"Alerta", "mensaje"=>"Error en registro2", "accion"=>"warning"));
            }

          }else{

            echo json_encode(array("resultado"=>false, "titulo"=>"Error", "mensaje"=>"Un error ha ocurrido", "accion"=>"danger"));
          }
        }
      $this->commit();
      }catch(Exception $e) {
        $this->rollback();
        echo json_encode(array("resultado"=>false, "titulo"=>"Alerta", "mensaje"=>$e->errorInfo, "accion"=>"warning"));
      }


    }
  }

  public function mostrar_formulario_crear_liga(){

    $res = $this->selectRowData('liga_participantes','*', array("usuario_id"=>$_SESSION["usuario"][0]["id"], "estado"=>1) );

    if($res){
      $res = $this->selectRowData('liga','*', array("id"=>$res["liga_id"]) );
      if($res){
        echo json_encode(array("resultado"=>true, "crear"=>false,"codigo"=>$res["codigo"]));
      }
    }else{
        echo json_encode(array("resultado"=>true, "crear"=>true));
    }

  }

  public function mostrar_formulario_unir_liga(){
    $res = $this->selectRowData('liga_participantes','*', array("usuario_id"=>$_SESSION["usuario"][0]["id"], "estado"=>1) );

    if($res){
      $idLiga = $res["liga_id"];
      $res = $this->selectRowData('liga','*', array("id"=>$idLiga) );

      if($res){
        echo json_encode(array("resultado"=>true, "form"=>false,"nombre_liga"=>$res["nombre"]));
      }else{
        echo json_encode(array("resultado"=>false, "titulo"=>"Error", "mensaje"=>"Un error ha ocurrido", "accion"=>"error"));
      }

    }else{
      echo json_encode(array("resultado"=>true, "form"=>true));
    }
  }

  public function unirse_liga($equipo, $liga){
    $liga = $this->selectRowData( 'liga','*', $liga );
    $liga_participantes = $liga["nro_participantes"];

    if(!$liga){

      echo json_encode(array("resultado"=>false, "mensaje"=>"La liga no existe", "accion"=>"error"));

    }else{
        //verificar los datos del admin
      $res = $this->selectRowData( 'usuarios','*', array("id"=> $liga["usuario_creacion"]) );

      if($res){
        $usuario_admin_plan_id = $res["plan_id"];
        $res_liga_participantes = $this->selectRowData( 'liga_participantes','count(*) as total', array("liga_id"=>$liga["id"], "estado"=>1) );
        if($res_liga_participantes["total"]>= $liga_participantes){
          echo json_encode(array("resultado"=>false, "mensaje"=>"Esta liga ya tiene los participantes completos"));
        }else{
          $datosUser = $this->selectRowData( 'usuarios','*', array( "id"=>$_SESSION["usuario"][0]["id"], "estado"=>1));
          if($datosUser){
            try{
              $this->beginTransaction();
              if( $datosUser["plan_id"] != $usuario_admin_plan_id ){
                echo json_encode(array("resultado"=>false, "mensaje"=>"No posee el mismo plan de la liga", "accion"=>"error"));
              }

              $valores['liga_id'] = $liga["id"];
              $valores['usuario_id'] = $_SESSION["usuario"][0]["id"];
              $valores['fecha_creacion'] = date("Y-m-d H:i:s");
              $valores['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
              $valores['usuario_edicion'] = $_SESSION["usuario"][0]["id"];

              $res = $this->insertData( 'liga_participantes', $valores);

              if($res){

                $idligaParticipantes = $this->getLastId('liga_participantes');

                  $equipo["liga_participantes_id"] = $idligaParticipantes[0]["id"];
                  $equipo['fecha_creacion'] = date("Y-m-d H:i:s");
                  $equipo['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
                  $equipo['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
                  $equipo['liga_id'] = $liga["id"];
                  $equipo['estado'] =2 ;
                  $res = $this->insertData('equipos', $equipo);


                  if($res){
                    $res = $this->updateData( 'usuarios', array("is_admin"=>0,"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("id"=> $_SESSION["usuario"][0]["id"]));
                    if($res){
                      //insertar saldo
                      $saldos["liga_participantes_id"] = $idligaParticipantes[0]["id"];
                      $saldos['fecha_creacion'] = date("Y-m-d H:i:s");
                      $saldos['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
                      $saldos['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
                      $saldos['saldo'] = 0;
                      $res = $this->insertData( 'saldo_disponible', $saldos );
                      if($res){
                        echo json_encode(array("resultado"=>true, "mensaje"=>"Ahora eres parte de la liga " . $liga["nombre"]));
                      }else{
                        echo json_encode(array("resultado"=>false, "mensaje"=>"Un error ha ocurrido. Intente de nuevo"));
                      }
                    }else{
                      echo json_encode(array("resultado"=>false, "mensaje"=>"Un error ha ocurrido. Intente de nuevo"));
                    }
                  }else{
                    echo json_encode(array("resultado"=>false,  "mensaje"=>"Un error ha ocurrido. Intente de nuevo"));
                  }


              }else{
                echo json_encode(array("resultado"=>false, "mensaje"=>"Un error ha ocurrido. Intente de nuevo"));
              }
              $this->commit();
            }catch(Exception $e) {
              $this->rollback();
          echo json_encode(array("resultado"=>false, "mensaje"=>$e->errorInfo));
            }
          }
        }
      }


    }
  }

  public function guardar_jugadores($values, $where, $liga_participante, $liga, $jugadores){
    $res = $this->updateData( 'equipos', $values, $where);

      if($res){

        //borrar sus jugadores previos
        $res2 = $this->selectRowData('plantilla_jugadores','*', array("liga_participantes_id"=>$liga_participante, "estado"=>1));
          if($res2){
            //si existen jugadores registrados, se borran
            $res = $this->deleteData('plantilla_jugadores', array("liga_participantes_id"=>$liga_participante, "estado"=>1));
          }
          if($res){
            try{
              $this->beginTransaction();
              foreach($jugadores as $item){
                  $res = $this->insertData( 'plantilla_jugadores', array("liga_participantes_id"=>$liga_participante,
                  "liga_id"=>$liga,"jugadores_id"=>$item, "fecha_creacion"=>date("Y-m-d H:i:s"), "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                  "usuario_edicion"=>$_SESSION["usuario"][0]["id"]) );

              }
              $this->commit();
            }catch(Exception $e) {
              $this->rollback();
              echo json_encode(array("resultado"=>false, "titulo"=>"Error", "mensaje"=>$e->errorInfo, "accion"=>"error"));
            }
           if($res){
             echo json_encode(array("resultado"=>true, "titulo"=>"Registro Exitoso", "mensaje"=>"Se guardaron tus jugadores base", "accion"=>"success"));
           }else{
             echo json_encode(array("resultado"=>false, "titulo"=>"Error", "mensaje"=>"Un error ha ocurrido", "accion"=>"error"));
           }
        }

      }else{
        echo json_encode(array("resultado"=>false, "titulo"=>"Error", "mensaje"=>"Un error ha ocurrido", "accion"=>"error"));
      }
  }

  public function kickoff_sorteo($valores){

      $res = $this->selectData('equipos', array("liga_id"=>$valores["liga"], "estado"=>2));
      $participantes = [];
      foreach ($res AS $item){
        array_push($participantes, $item["id"]);
      }
      shuffle($participantes);
      $participantes_string = json_encode($participantes);
      $fecha_kickoff = $valores["fecha"] . ' ' . $valores["hora"];
      try{
        $this->beginTransaction();
        $nueva_fecha = $fecha_kickoff;
        $count = 0;
        if($res){
            $turno = 1;
            foreach($participantes as $item){
              if($count!=0){
                $nueva_fecha = date("Y-m-d H:i:s",strtotime($nueva_fecha." +10 minutes"));
              }
                $res = $this->updateData('equipos', array("fecha_kickoff"=>$nueva_fecha, "turno_kickoff"=>$turno), array("liga_id"=>$valores["liga"], "estado"=>2, "id"=>$item));
                $count++;
                $turno++;
            }

            if($res){
              $res = $this->updateData('liga', array("kickoff_array"=>$participantes_string, "kickoff_fecha"=>$fecha_kickoff, "estado"=>2), array("id"=>$valores["liga"]));
              if($res){
                echo json_encode(array("resultado"=>true, "titulo"=>"Registro Exitoso", "mensaje"=>"Kickoff Registrado", "accion"=>"success"));
              }

            }
          }
          $this->commit();
      }catch(Exception $e) {
        echo json_encode(array("resultado"=>false, "titulo"=>"Error", "mensaje"=>"Un error ha ocurrido...", "accion"=>"danger"));
      }


  }



  public function generar_partidos_liga($liga, $temporada_liga_id){

    $res = $this->executeQuery("SELECT * FROM temporada_liga WHERE id = ". $temporada_liga_id ." ORDER BY id DESC LIMIT 1");
    if($res){
      $id_temporada_liga = $res[0]["id"];

      $res = $this->selectData('equipos', array("liga_id"=>$liga, "estado"=>1));

      if($res){
        $todosEquipos =$res;
        try{
          $this->beginTransaction();
          $array_equipos = [];
          foreach ($todosEquipos as $item){


              array_push($array_equipos, $item["id"]);
              $equipo_id = $item["id"];
              foreach ($todosEquipos as $valor){
                $valores = [];
                if( $valor["id"]!=$equipo_id){
                  if($item["eliminado"]==1){
                    if($valor["eliminado"]==1){
                      //los dos están eliminados
                      $valores['goles_casa'] = 0;
                      $valores['goles_visitante'] =0;
                      $valores['pts_casa'] =1;
                      $valores['pts_visitante'] = 1;
                      $valores['registrado'] = 1;

                    }else{
                      //solo el local está eliminado
                      $valores['goles_casa'] = 0;
                      $valores['goles_visitante'] =2;
                      $valores['pts_casa'] =0;
                      $valores['pts_visitante'] = 3;
                      $valores['registrado'] = 1;
                    }

                  }else{
                    //visitante está eliminado
                    if($valor["eliminado"]==1){
                      //visitante pierde
                      $valores['goles_casa'] = 2;
                      $valores['goles_visitante'] =0;
                      $valores['pts_casa'] =3;
                      $valores['pts_visitante'] = 0;
                      $valores['registrado'] = 1;

                    }
                  }
                  $valores['temporada_liga_id'] = $id_temporada_liga;
                  $valores['casa'] = $equipo_id;
                  $valores['visitante'] = $valor["id"];
                  $valores['fecha_creacion'] = date("Y-m-d H:i:s");
                  $valores['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
                  $valores['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
                $res = $this->insertData( 'partidos_liga', $valores );
                }
              }
          }

          if($res){
            // BEGIN GENERAR PARTIDOS COPA
            $numero_participantes = count($array_equipos);
            $cont_equi = 0;
            if($numero_participantes == 16){

              foreach($array_equipos as $item){
                $valores_copa = [];

                if($cont_equi == 0 || $cont_equi == 1){
                  $cod_proximo_partido = "4-1";
                }elseif($cont_equi == 2 || $cont_equi == 3){
                  $cod_proximo_partido = "4-2";
                }elseif($cont_equi == 4 || $cont_equi == 5){
                  $cod_proximo_partido = "4-3";
                }elseif($cont_equi == 6 || $cont_equi == 7){
                  $cod_proximo_partido = "4-4";
                }
                $claves_aleatorias = array_rand($array_equipos, 2);
                $casa = $array_equipos[$claves_aleatorias[0]];
                $visitante = $array_equipos[$claves_aleatorias[1]];

                /* BEGIN ELIMINADO */
              /*   $p_casa =  $this->selectRowData('equipos','*', array("id"=>$casa));
                $p_visitante = $this->selectRowData('equipos','*', array("id"=>$visitante));
                if($p_casa["eliminado"]==1){
                  //perdió local
                  $valores_copa['goles_local'] = 0;
                  $valores_copa['goles_visitante'] =2;
                  $valores_copa['equipo_ganador'] = $visitante;
                  $valores_copa['equipo_perdedor'] = $casa;
                  $valores_copa['registrado'] = 1;
                }else{
                  if($p_visitante["eliminado"]==1){
                    //perdió visitante
                    $valores_copa['goles_local'] = 2;
                    $valores_copa['goles_visitante'] =0;
                    $valores_copa['equipo_ganador'] = $casa;
                    $valores_copa['equipo_perdedor'] = $visitante;
                    $valores_copa['registrado'] = 1;
                  }
                } */
                /*END ELIMINADO*/
                $valores_copa['fecha_creacion'] = date("Y-m-d H:i:s");
                $valores_copa['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
                $valores_copa['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
                $valores_copa['temporada_liga_id'] = $id_temporada_liga;
                $valores_copa['equipo_local'] = $casa;
                $valores_copa['etapa'] ="Octavos de Final";
                $valores_copa['cod_proximo_partido'] = $cod_proximo_partido;
                $valores_copa['equipo_visitante'] = $visitante;
                $res = $this->insertData( 'partidos_copa', $valores_copa);

                if($res){
                  unset($array_equipos[$claves_aleatorias[0]]);
                  unset($array_equipos[$claves_aleatorias[1]]);



                  if (count($array_equipos)==12){
                    //primer partido de cuartos
                    $res = $this->insertData( 'partidos_copa', array("fecha_creacion"=>date("Y-m-d H:i:s"),
                    "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                    "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
                    "temporada_liga_id"=>$id_temporada_liga,
                    "etapa"=>"Cuartos de Final",
                    "cod_proximo_partido"=>"s-1",
                    "codigo"=>'4-1'));
                    //buscar si se ha registrado

                  }elseif (count($array_equipos)==8) {
                    //segundo partido de cuartos
                    $res = $this->insertData( 'partidos_copa', array("fecha_creacion"=>date("Y-m-d H:i:s"),
                    "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                    "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
                    "temporada_liga_id"=>$id_temporada_liga,
                    "cod_proximo_partido"=>"s-1",
                    "etapa"=>"Cuartos de Final",
                    "codigo"=>'4-2'));
                  }elseif (count($array_equipos)==4) {
                    //tercer partido de cuartos
                    $res = $this->insertData( 'partidos_copa', array("fecha_creacion"=>date("Y-m-d H:i:s"),
                    "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                    "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
                    "temporada_liga_id"=>$id_temporada_liga,
                    "cod_proximo_partido"=>"s-2",
                    "etapa"=>"Cuartos de Final",
                    "codigo"=>'4-3'));
                  }elseif(count($array_equipos)==0){
                    //cuarto partidos de cuartos
                    $res = $this->insertData( 'partidos_copa', array("fecha_creacion"=>date("Y-m-d H:i:s"),
                    "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                    "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
                    "temporada_liga_id"=>$id_temporada_liga,
                    "cod_proximo_partido"=>"s-2",
                    "etapa"=>"Cuartos de Final",
                    "codigo"=>'4-4'));
                    break;
                  }
                }
                $cont_equi++;

              }
              $res = $this->insertData( 'partidos_copa', array("fecha_creacion"=>date("Y-m-d H:i:s"),
              "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
              "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
              "temporada_liga_id"=>$id_temporada_liga,
              "cod_proximo_partido"=>"f",
              "etapa"=>"Semifinal",
              "codigo"=>'s-1'));
              $res = $this->insertData( 'partidos_copa', array("fecha_creacion"=>date("Y-m-d H:i:s"),
              "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
              "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
              "temporada_liga_id"=>$id_temporada_liga,
              "cod_proximo_partido"=>"f",
              "etapa"=>"Semifinal",
              "codigo"=>'s-2'));

              $res = $this->insertData( 'partidos_copa', array("fecha_creacion"=>date("Y-m-d H:i:s"),
              "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
              "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
              "temporada_liga_id"=>$id_temporada_liga,
              "etapa"=>"Final",
              "codigo"=>'f'));



            }elseif ($numero_participantes == 8) {

                foreach($array_equipos as $item){
                  $valores_copa = [];
                  if($cont_equi == 0 || $cont_equi == 1){
                    $cod_proximo_partido = "s-1";
                  }elseif($cont_equi == 2 || $cont_equi == 3){
                    $cod_proximo_partido = "s-2";
                  }
                  $claves_aleatorias = array_rand($array_equipos, 2);
                  $casa = $array_equipos[$claves_aleatorias[0]];
                  $visitante = $array_equipos[$claves_aleatorias[1]];

                  //BEGIN ELIMINADO

                  /* $p_casa =  $this->selectRowData('equipos','*', array("id"=>$casa));
                  $p_visitante = $this->selectRowData('equipos','*', array("id"=>$visitante));
                  if($p_casa["eliminado"]==1){
                    //perdió local
                    $valores_copa['goles_local'] = 0;
                    $valores_copa['goles_visitante'] =2;
                    $valores_copa['equipo_ganador'] = $visitante;
                    $valores_copa['equipo_perdedor'] = $casa;
                    $valores_copa['registrado'] = 1;

                  }else{
                    if($p_visitante["eliminado"]==1){
                      //perdió visitante
                      $valores_copa['goles_local'] = 2;
                      $valores_copa['goles_visitante'] =0;
                      $valores_copa['equipo_ganador'] = $casa;
                      $valores_copa['equipo_perdedor'] = $visitante;
                      $valores_copa['registrado'] = 1;

                    }
                  } */

                  //END ELIMINADO

                  $valores_copa['fecha_creacion'] = date("Y-m-d H:i:s");
                  $valores_copa['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
                  $valores_copa['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
                  $valores_copa['temporada_liga_id'] = $id_temporada_liga;
                  $valores_copa['equipo_local'] = $casa;
                  $valores_copa['etapa'] ="Cuartos de Final";
                  $valores_copa['cod_proximo_partido'] = $cod_proximo_partido;
                  $valores_copa['equipo_visitante'] = $visitante;

                  $res = $this->insertData( 'partidos_copa', $valores_copa);

                  if($res){

                    unset($array_equipos[$claves_aleatorias[0]]);
                    unset($array_equipos[$claves_aleatorias[1]]);

                    /* if (count($array_equipos)==4) {
                      //primer partido de semis
                      $res = $this->insertData( 'partidos_copa', array("fecha_creacion"=>date("Y-m-d H:i:s"),
                      "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                      "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
                      "temporada_liga_id"=>$id_temporada_liga,
                      "cod_proximo_partido"=>"f",
                      "etapa"=>"Semifinal",
                      "codigo"=>'s-1'));
                    }elseif(count($array_equipos)==0){
                      //segundo partido de semis
                      $res = $this->insertData( 'partidos_copa', array("fecha_creacion"=>date("Y-m-d H:i:s"),
                      "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                      "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
                      "temporada_liga_id"=>$id_temporada_liga,
                      "cod_proximo_partido"=>"f",
                      "etapa"=>"Semifinal",
                      "codigo"=>'s-2'));
                      break;
                    } */
                    if(count($array_equipos)==0){
                      break;
                    }

                  }else{
                    exit();
                  }

                        $cont_equi++;

                }

                $res = $this->insertData( 'partidos_copa', array("fecha_creacion"=>date("Y-m-d H:i:s"),
                      "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                      "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
                      "temporada_liga_id"=>$id_temporada_liga,
                      "cod_proximo_partido"=>"f",
                      "etapa"=>"Semifinal",
                      "codigo"=>'s-1'));

                $res = $this->insertData( 'partidos_copa', array("fecha_creacion"=>date("Y-m-d H:i:s"),
                      "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                      "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
                      "temporada_liga_id"=>$id_temporada_liga,
                      "cod_proximo_partido"=>"f",
                      "etapa"=>"Semifinal",
                      "codigo"=>'s-2'));

                $res = $this->insertData( 'partidos_copa', array("fecha_creacion"=>date("Y-m-d H:i:s"),
                "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
                "temporada_liga_id"=>$id_temporada_liga,
                "etapa"=>"Final",
                "codigo"=>'f'));

              }else{
                $this->rollback();
                echo json_encode(array("resultado"=>false));
              }



            // END GENERAR PARTIDOS COPA

            $res = $this->updateData('temporada_liga', array( "estado"=>1, "partidos_generados"=>1), array("id"=>$id_temporada_liga));
            if($res){
              $res = $this->insertData( 'copa', array( "temporada_liga_id"=>$id_temporada_liga,
                  "fecha_creacion"=>date("Y-m-d H:i:s"),
                  "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                  "usuario_edicion"=>$_SESSION["usuario"][0]["id"]));
                  if($res){
                    echo json_encode(array("resultado"=>true));
                  }

            }else{
              echo json_encode(array("resultado"=>false));
            }

          }
          $this->commit();
        }catch(Exception $e) {
          $this->rollback();
          echo json_encode(array("resultado"=>false, "titulo"=>"Error", "mensaje"=>$e->errorInfo, "accion"=>"danger"));
        }
      }
    }
  }

  public function cargar_equipos_casa($liga, $temporada_liga_id){

  $res = $this->executeQuery("SELECT b.* FROM partidos_liga a INNER JOIN equipos b ON a.casa=b.id WHERE a.registrado=0 AND a.temporada_liga_id= ". $temporada_liga_id ." GROUP BY a.casa ORDER BY b.nombre");
    if($res){
      echo json_encode(array("resultado"=>true, "data"=>$res));
    }
  }


  public function cargar_equipos_visitante($temporada_liga, $id_equipo_local){
    $res = $this->executeQuery("SELECT a.*, b.nombre as nombre_visitante, (SELECT nombre FROM equipos WHERE id=a.casa) as nombre_local FROM partidos_liga a INNER JOIN equipos b ON a.visitante = b.id WHERE a.temporada_liga_id = ".$temporada_liga." AND a.estado =1 AND a.casa=". $id_equipo_local . " AND a.registrado=0");
    if($res){
      echo json_encode(array("resultado"=>true, "data"=>$res));
    }
  }

  public function guardar_resultados_liga($partidos_liga_id, $valores, $temporada_liga_id, $valores_1, $valores_2){


      try{
        $this->beginTransaction();

        $valores['fecha_edicion'] = date("Y-m-d H:i:s");
        $valores['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
        $valores['registrado'] = 1;
        $res = $this->updateData('partidos_liga', $valores, array("id"=>$partidos_liga_id));

        if($res){
          $tabla_temporada_liga = $this->selectRowData('temporada_liga','*', array("id"=>$temporada_liga_id));
          $liga_id = $tabla_temporada_liga["liga_id"];
          /* insertar premios */
          $valores_1['fecha_creacion'] = date("Y-m-d H:i:s");
          $valores_1['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
          $valores_1['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
          $valores_1['liga_id'] = $tabla_temporada_liga["liga_id"];
          $res = $this->insertData( 'premios_historial', $valores_1 );

          if($res){
            $valores_2['fecha_creacion'] = date("Y-m-d H:i:s");
            $valores_2['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
            $valores_2['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
            $valores_2['liga_id'] = $tabla_temporada_liga["liga_id"];
            $res = $this->insertData( 'premios_historial', $valores_2 );

            if($res){
              $res = $this->selectRowData('partidos_liga','*', array("temporada_liga_id"=>$temporada_liga_id, "registrado"=>0));
              if($res){
                //todavía faltan registrar resultados
                echo json_encode(array("resultado"=>true,  "resultados_completos"=>0));
              }else{
                /* La liga ha finalizado, insertar premios finales */

                $equipos = $this->executeQuery("SELECT (SUM(a.goles_casa)+ (SELECT SUM(b.goles_visitante) FROM partidos_liga b WHERE b.visitante = a.casa)) goles_favor,
                (SUM(a.pts_casa)+ (SELECT SUM(b.pts_visitante) FROM partidos_liga b WHERE b.visitante = a.casa)) puntaje,
                (SUM(a.goles_visitante)+ (SELECT SUM(b.goles_casa) FROM partidos_liga b WHERE b.visitante = a.casa)) goles_contra,
                a.casa equipo_id FROM partidos_liga a  LEFT JOIN equipos equi ON a.casa = equi.id
                WHERE a.temporada_liga_id = " .$temporada_liga_id. " AND a.estado =1 AND equi.eliminado=0 GROUP BY a.casa ORDER BY puntaje DESC, goles_favor DESC");
                $premios_liga = $this->executeQuery("SELECT * FROM premios where tipo =1");

                $id_equipo_goleador = '';
                $id_equipo_menos_goles_recibidos = '';
                $total_goles_anotados = 0;
                $total_goles_recibidos = 0;
                $count=0;
                foreach($equipos as $item => $value){

                  if($value["goles_favor"] >= $total_goles_anotados){
                    $total_goles_anotados = $value["goles_favor"];
                    $id_equipo_goleador = $value["equipo_id"];
                  }
                  if($count == 0){

                      $total_goles_recibidos = $value["goles_contra"];
                      $id_equipo_menos_goles_recibidos = $value["equipo_id"];

                  }else{
                    if($value["goles_contra"]<= $total_goles_recibidos){
                      $total_goles_recibidos =$value["goles_contra"];
                      $id_equipo_menos_goles_recibidos = $value["equipo_id"];
                    }
                  }

                  /* insertar premios por posiión en la liga */

                  $res = $this->insertData( 'premios_historial', array("fecha_creacion"=>date("Y-m-d H:i:s"),
                                          "usuario_edicion"=>$_SESSION["usuario"][0]["id"], "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                                          "monto"=>$premios_liga[$count]["monto"], "premios_id"=>$premios_liga[$count]["id"], "temporada_liga_id"=>$temporada_liga_id, "equipo_id"=>$value["equipo_id"],
                                          "liga_id"=>$tabla_temporada_liga["liga_id"]) );

                  $count++;

                }
                /* insertar máximo goleador liga*/
                $res = $this->insertData( 'premios_historial', array("fecha_creacion"=>date("Y-m-d H:i:s"),
                                          "usuario_edicion"=>$_SESSION["usuario"][0]["id"], "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                                          "monto"=>5, "premios_id"=>25, "temporada_liga_id"=>$temporada_liga_id, "equipo_id"=>$id_equipo_goleador,
                                          "liga_id"=>$tabla_temporada_liga["liga_id"]) );
                if($res){
                  /* insertar mejor defensa liga */
                  $res = $this->insertData( 'premios_historial', array("fecha_creacion"=>date("Y-m-d H:i:s"),
                  "usuario_edicion"=>$_SESSION["usuario"][0]["id"], "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                  "monto"=>5, "premios_id"=>26, "temporada_liga_id"=>$temporada_liga_id, "equipo_id"=>$id_equipo_menos_goles_recibidos,
                  "liga_id"=>$tabla_temporada_liga["liga_id"]) );

                  if($res){
                    $res = $this->selectRowData('partidos_copa','*', array("temporada_liga_id"=>$temporada_liga_id, "registrado"=>0));
                    if($res){
                      /* falta registrar resulatdos de la copa */
                      echo json_encode(array("resultado"=>true, "resultados_completos"=>1));
                    }else{

                         /* BEGIN SALARIOS */
                          $gastos_id = $this->selectRowData('gastos','*', array("codigo"=>"salarios"));
                       //   $todosEquipos = $this->selectData('equipos', array("liga_id"=>$liga_id));
                            $todosEquipos = $this->executeQuery("SELECT * FROM equipos WHERE liga_id=".$liga_id."  AND eliminado=0 ");
                          if($todosEquipos){

                              foreach ($todosEquipos as $item){

                                  $plantilla =   json_decode($item["jugadores"],true);
                                  $salario_monto = 0;
                                  foreach ($plantilla as $indice => $id_jugador) {
                                  //value = jugador id
                                  $jugador_data = $this->selectRowData('jugadores_valor','*', array("id_jugador"=>$id_jugador));
                                  $salario_monto = $jugador_data["salario"] + $salario_monto;
                                  }

                                  //pagar salario acumulado
                                  $val['temporada_liga_id'] = $temporada_liga_id;
                                  $val['monto'] = $salario_monto;
                                  $val['gastos_id'] = $gastos_id["id"];
                                  $val['liga_id'] = $liga_id;
                                  $val['equipo_id'] = $item["id"];
                                  $val['fecha_creacion'] = date("Y-m-d H:i:s");
                                  $val['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
                                  $val['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
                                  $pagar_salario = $this->insertData( 'gastos_historial', $val );


                              }

                              // END SALARIOS //

                              //BEGIN ACTUALIZAR SALDOS
                              $equipos = $this->executeQuery("SELECT ((SELECT SUM(monto) FROM premios_historial  WHERE liga_id=".$liga_id."   AND
                              equipo_id= a.id) - (SELECT SUM(monto) FROM gastos_historial WHERE liga_id=".$liga_id." AND
                              equipo_id= a.id) ) saldo, a.liga_participantes_id FROM equipos a WHERE a.liga_id=".$liga_id." AND a.eliminado=0");
                              if($equipos){

                                foreach ($equipos as $key =>$item){
                                  $res = $this->updateData('saldo_disponible', array("saldo"=>$item["saldo"]), array("liga_participantes_id"=>$item["liga_participantes_id"]));
                                }
                                //END ACTUALIZAR SALDOS

                                 /* temporada finalizada. Cambiar a estado a negociaciones */
                                 $res = $this->updateData('temporada_liga', array("estado"=>3, "fecha_edicion"=>date("Y-m-d H:i:s"), "usuario_edicion"=>$_SESSION["usuario"][0]["id"]), array("id"=>$temporada_liga_id));
                                 if($res){
                                   echo json_encode(array("resultado"=>true, "resultados_completos"=>1));

                                 }else{
                                   echo json_encode(array("resultado"=>false));
                                 }
                              }else{
                                exit();
                              }

                          }
                    }

                  }
                }

              }
            }
          }
        }

        $this->commit();
      }catch(Exception $e) {
        $this->rollback();
        echo json_encode(array("resultado"=>false, "mensaje"=>$e->errorInfo));
      }

  }


  public function kickoff_paso_2( $refuerzo1, $refuerzo2, $jugador_estrella, $equipo_id, $liga_id, $liga_participantes_id, $jugadores ){

    try{
      $this->beginTransaction();

        //se insertan los 3 jugadores restantes en la plantilla
          $nuevos_valores = [$refuerzo1, $refuerzo2, $jugador_estrella];
          foreach($nuevos_valores as $item){
               $res = $this->insertData( 'plantilla_jugadores', array("liga_participantes_id"=>$liga_participantes_id,
               "liga_id"=>$liga_id,"jugadores_id"=>$item, "fecha_creacion"=>date("Y-m-d H:i:s"), "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
               "usuario_edicion"=>$_SESSION["usuario"][0]["id"]) );
         }

         if($res){
          //se actualiza el equipo
          $res = $this->updateData( 'equipos', array("registro_plantilla"=>2,"estado"=>1, "jugadores"=>$jugadores), array("id"=>$equipo_id));
          if($res){
            $res = $this->executeQuery("SELECT * FROM equipos WHERE registro_plantilla<>2 AND liga_id=".$liga_id);
            if($res){
              // todavía falta
              echo json_encode(array("resultado"=>true, "titulo"=>"Registro Exitoso", "mensaje"=>"Los jugadores ahora pertenecen a tu plantilla.", "accion"=>"success"));
            }else{
              //ya están todos los equipos
              //empieza la temporada
              $res = $this->insertData( 'temporada_liga', array("liga_id"=>$liga_id,
              "fecha_inicio"=>date("Y-m-d H:i:s"),
              "fecha_creacion"=>date("Y-m-d H:i:s"),
              "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
              "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
              "estado"=>2));

              if($res){
                $res = $this->getLastId('temporada_liga');
                if($res){
                  $idTemporadaLiga = $res[0]["id"];


                  if($res){
                     //actualizar saldo disponible
                     $res = $this->selectData('liga_participantes', array("liga_id"=>$liga_id));
                    if($res){
                      $participantes = $res;
                      foreach( $participantes as $key => $item){
                        $res = $this->updateData( 'saldo_disponible', array("temporada_liga_id"=>$idTemporadaLiga), array("liga_participantes_id"=>$item["id"]));
                        if(!$res){
                          exit();
                        }
                      }
                      if($res){
                        $res = $this->updateData( 'liga', array("estado"=>1), array("id"=>$liga_id));
                        $res = $this->updateData( 'liga_participantes', array("temporada_liga_id"=>$idTemporadaLiga), array("id"=>$liga_participantes_id));
                        if($res){
                          echo json_encode(array("resultado"=>true, "titulo"=>"Registro Exitoso", "mensaje"=>"Los jugadores ahora pertenecen a tu plantilla. La liga ha empezado", "accion"=>"success"));
                        }else{
                          exit();
                          echo json_encode(array("resultado"=>false, "titulo"=>"Error", "mensaje"=>"Un error ha ocurrido", "accion"=>"error"));
                        }
                      }else{
                          exit();
                          echo json_encode(array("resultado"=>false, "titulo"=>"Error", "mensaje"=>"Un error ha ocurrido", "accion"=>"error"));
                      }
                    }



                  }
                }else{
                  exit();
                  echo json_encode(array("resultado"=>false, "titulo"=>"Error", "mensaje"=>"Un error ha ocurrido", "accion"=>"error"));
                }
              }else{
                exit();
                echo json_encode(array("resultado"=>false, "titulo"=>"Error", "mensaje"=>"Un error ha ocurrido", "accion"=>"error"));
              }
            }
          }else{
            exit();
            echo json_encode(array("resultado"=>false, "titulo"=>"Error", "mensaje"=>"Un error ha ocurrido", "accion"=>"error"));
          }
         }else{
          exit();
           echo json_encode(array("resultado"=>false, "titulo"=>"Error", "mensaje"=>"Un error ha ocurrido", "accion"=>"error"));
         }
      $this->commit();
    }catch(Exception $e) {
      $this->rollback();
      echo json_encode(array("resultado"=>false, "titulo"=>"Error", "mensaje"=>$e->errorInfo, "accion"=>"error"));
    }


  }

  public function cargar_datos_usuario($idUser){
    $res = $this->executeQuery("SELECT a.nombre as nombre_liga, a.estado as estado_liga,  a.nro_participantes, b.id as equipo_id, b.eliminado as eliminado, (SELECT saldo FROM saldo_disponible WHERE liga_participantes_id=b.liga_participantes_id AND estado=1) as saldo_disponible ,a.usuario_creacion as usuario_creacion_liga, b.*,
    (SELECT c.estado  FROM temporada_liga c WHERE c.liga_id = a.id AND c.estado <> 0 ORDER BY c.id DESC LIMIT 1) temporada_estado,
    (SELECT c.numero  FROM temporada_liga c WHERE c.liga_id = a.id AND c.estado <> 0 ORDER BY c.id DESC LIMIT 1) temporada_numero,
    (SELECT temporadas FROM plan WHERE id=". $_SESSION["usuario"][0]["plan_id"] .") numero_temporadas,
    (SELECT c.id  FROM temporada_liga c WHERE c.liga_id = a.id AND c.estado <> 0 ORDER BY c.id DESC LIMIT 1) temporada_id,
    (SELECT c.partidos_generados  FROM temporada_liga c WHERE c.liga_id = a.id AND c.estado <> 0 ORDER BY c.id DESC LIMIT 1) temporada_partidos_generados
    FROM liga a INNER JOIN equipos b ON a.id = b.liga_id WHERE a.estado<>0 AND  b.usuario_creacion= ". $idUser);
  // $res = $this->executeQuery("SELECT a.nombre as nombre_liga, b.*, (SELECT c.estado  FROM temporada_liga c WHERE c.liga_id = a.id AND c.estado <> 0 ORDER BY c.id DESC LIMIT 1) temporada_estado, (SELECT c.id  FROM temporada_liga c WHERE c.liga_id = a.id AND c.estado <> 0 ORDER BY c.id DESC LIMIT 1) temporada_id  FROM  liga a INNER JOIN equipos b ON a.id = b.liga_id WHERE  b.usuario_creacion= ". $idUser);
   if($res){
    $res3 = $this->selectRowData("usuarios", "*", array("id"=>$idUser));
    $res2 = $this->executeQuery("SELECT count(*) AS cont FROM liga_participantes a WHERE a.liga_id= ".$res[0]["liga_id"]." AND a.estado =1");
      if($res2){
        echo json_encode(array("resultado"=>true, "data"=>$res[0], "id_usuario"=>$_SESSION["usuario"][0]["id"], "participantes_registrados"=>$res2[0]["cont"], "usuario"=>$res3));
      }else{
        echo json_encode(array("resultado"=>true, "data"=>$res[0], "id_usuario"=>$_SESSION["usuario"][0]["id"], "participantes_registrados"=>'',"usuario"=>$res3));
      }
    }
  }


   public function cargar_jugadores_compra( $team, $datos_liga){


    if($datos_liga["temporada_numero"]==1){
      $sql_extra = " ju.overall <= 85 AND ";
    }else if ($datos_liga["temporada_numero"]==2){
      $sql_extra = " ju.overall <= 88 AND ";
    }else{
      $sql_extra = "";
    }
    $data = $this->executeQuery("SELECT ju.*, (SELECT jv.valor FROM jugadores_valor jv WHERE jv.id_jugador=ju.id) valor_jugador FROM jugadores ju WHERE ju.estado =1 AND ju.teams_id = ".$team."  AND ". $sql_extra ."
    ju.id not in (select plan.jugadores_id from plantilla_jugadores plan WHERE plan.liga_id = ".$datos_liga["liga_id"]." AND plan.estado = 1 AND  plan.jugadores_id <> '')
    AND ju.id not in (select sub.jugadores_id from jugadores_subastas sub WHERE sub.liga_participantes_id =".$datos_liga["liga_participantes_id"]." AND sub.estado = 1 AND sub.temporada_liga_id=".$datos_liga["temporada_id"].") ");
    if($data){
        echo json_encode(array("resultado"=>true, "data"=>$data));
     }
   }

   public function comprar_jugador( $jugador, $liga_participantes, $temporada_liga_id, $datos_liga){

    try{
      $this->beginTransaction();
        //verificar que se encuentre en período de compras

        $res = $this->selectRowData("temporada_liga", "*", array("id"=>$datos_liga["temporada_id"]));
        if($res["estado"]==3 || $res["estado"]==5 ){
          $res = $this->selectRowData("jugadores_subastas", "*", array("liga_participantes_id"=>$liga_participantes, "jugadores_id"=>$jugador["id"], "estado"=>1));
          if($res){
            echo json_encode(array("resultado"=>false, "mensaje"=>"Este jugador ya está en tu lista de compras"));
          }else{
            $res = $this->insertData( 'jugadores_subastas', array("valor"=>$jugador["valor_jugador"], "liga_participantes_id"=>$liga_participantes,
            "temporada_liga_id"=>$temporada_liga_id,"jugadores_id"=>$jugador["id"],"fecha_creacion"=>date("Y-m-d H:i:s"), "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
            "usuario_edicion"=>$_SESSION["usuario"][0]["id"]));
            if($res){
              echo json_encode(array("resultado"=>true, "mensaje"=>"Jugador en lista de compras"));
            }else{
              echo json_encode(array("resultado"=>false, "mensaje"=>$e->errorInfo));
            }
          }
        }else{
          echo json_encode(array("resultado"=>false, "mensaje"=>"Compra no permitida. El período de negociaciones ha culminado"));
        }

      $this->commit();
    }catch(Exception $e) {
      $this->rollback();

      echo json_encode(array("resultado"=>false, "mensaje"=>$e->errorInfo));

   }

  }
  public function comprar_jugador_periodo_extemporaneo( $jugador, $datos_liga){

    try{
      $this->beginTransaction();
      $res = $this->selectRowData("temporada_liga", "*", array("id"=>$datos_liga["temporada_id"]));
      if($res["estado"]==3 || $res["estado"]==5 ){
        $res = $this->selectRowData("plantilla_jugadores", "*", array("liga_id"=>$datos_liga["liga_id"], "jugadores_id"=>$jugador["id"], "estado"=>1));
        if(!$res){
          //se inserta la compra en tablas compras
          $res = $this->insertData( 'compras', array("liga_participantes_id"=>$datos_liga["liga_participantes_id"],
          "fecha_creacion"=>date("Y-m-d H:i:s"),
          "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
          "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
          "monto"=>$jugador["valor_jugador"],
          "temporada_liga_id"=>$datos_liga["temporada_id"],
          "equipo_id"=>$datos_liga["equipo_id"],
          "jugadores_id"=>$jugador["id"]));

          if($res){
            //se registra el gasto
            $gasto = $this->selectRowData("gastos","*", array("codigo"=>"compras"));
            $res = $this->insertData("gastos_historial", array(
              "fecha_creacion"=>date("Y-m-d H:i:s"),
              "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
              "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
              "monto"=>$jugador["valor_jugador"],
              "temporada_liga_id"=>$datos_liga["temporada_id"],
              "equipo_id"=>$datos_liga["equipo_id"],
              "liga_id"=>$datos_liga["liga_id"],
              "gastos_id"=>$gasto["id"]));

              if($res){
                //insertar jugador a plantilla
                $res = $this->insertData("plantilla_jugadores", array(
                  "fecha_creacion"=>date("Y-m-d H:i:s"),
                  "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                  "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
                  "jugadores_id"=>$jugador["id"],
                  "liga_id"=>$datos_liga["liga_id"],
                  "liga_participantes_id"=>$datos_liga["liga_participantes_id"]));
                  if($res){

                    $plantilla =   json_decode($datos_liga["jugadores"],true);
                    array_push($plantilla, $jugador["id"]);
                    $plantilla = json_encode(array_values($plantilla));
                    //insertar jugador al equipo
                    $res = $this->updateData( 'equipos', array("jugadores"=>$plantilla,"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("id"=>$datos_liga["equipo_id"]));
                    if($res){
                        //actualizar su saldo
                        $res = $this->executeQuery("SELECT ((SELECT SUM(monto) FROM premios_historial  WHERE liga_id=".$datos_liga["liga_id"]."  AND
                            equipo_id= a.id) - (SELECT SUM(monto) FROM gastos_historial WHERE liga_id=".$datos_liga["liga_id"]." AND
                            equipo_id= a.id) ) saldo FROM equipos a WHERE liga_id=".$datos_liga["liga_id"]. " AND a.liga_participantes_id=".$datos_liga["liga_participantes_id"]);
                          if($res){
                            $res = $this->updateData('saldo_disponible', array("saldo"=>$res[0]["saldo"],"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("liga_participantes_id"=>$datos_liga["liga_participantes_id"]));
                            if($res){
                              echo json_encode(array("resultado"=>true, "mensaje"=>"Jugador Comprado"));
                            }else{
                              echo json_encode(array("resultado"=>false, "mensaje"=>"Ha ocurrido un error. Intente de nuevo"));
                            }
                          }else{
                            echo json_encode(array("resultado"=>false, "mensaje"=>"Ha ocurrido un error. Intente de nuevo"));
                          }
                    }else{
                      echo json_encode(array("resultado"=>false, "mensaje"=>"Ha ocurrido un error. Intente de nuevo"));
                    }
                  }else{
                    echo json_encode(array("resultado"=>false, "mensaje"=>"Ha ocurrido un error. Intente de nuevo"));
                  }
              }else{
                echo json_encode(array("resultado"=>false, "mensaje"=>"Ha ocurrido un error. Intente de nuevo"));
              }
          }else{
            echo json_encode(array("resultado"=>false, "mensaje"=>"Ha ocurrido un error. Intente de nuevo"));
          }
        }else{
          echo json_encode(array("resultado"=>false, "mensaje"=>"Lo siento, este jugador ya ha sido comprado. Intente con otro jugador"));
        }
      }else{
        echo json_encode(array("resultado"=>false, "mensaje"=>"Compra no permitida. El período de negociaciones ha culminado"));
      }

      $this->commit();
    }catch(Exception $e) {
      $this->rollback();

      echo json_encode(array("resultado"=>false, "mensaje"=>$e->errorInfo));

   }

  }
  public function cargar_jugadores_ventas($liga_participantes, $temporada_liga_id){

    $data = $this->executeQuery("SELECT a.*, (SELECT ROUND((c.valor*0.8),2) FROM jugadores_valor c WHERE c.id_jugador = a.id ) valor_venta  FROM jugadores a INNER JOIN plantilla_jugadores b ON a.id = b.jugadores_id WHERE b.estado=1 AND b.estado=1 AND b.liga_participantes_id= " .$liga_participantes. " ORDER BY a.overall ASC ");

    if($data){
      $res = $this->selectRowData("ventas","count(*) contador_ventas", array("temporada_liga_id"=>$temporada_liga_id, "liga_participantes_id"=>$liga_participantes, "estado"=>1));

      if($res){
        echo json_encode(array("resultado"=>true, "data"=>$data, "contador_ventas"=>$res["contador_ventas"]));
      }
     }
   }

   public function vender_mercado($jugador, $temporada_liga, $liga_participantes, $equipo, $liga_id, $nuevo_saldo){

      try{
        $this->beginTransaction();

        $res = $this->selectRowData("temporada_liga", "*", array("id"=>$temporada_liga));
        if($res["estado"]==3 || $res["estado"]==5 ){

          $res = $this->selectRowData("jugadores_subastas", "*", array("otro_equipo_id"=>$equipo["id"], "jugadores_id"=>$jugador["id"], "estado"=>1));

          if(!$res){
            $res = $this->selectRowData("jugadores_valor","*", array("id_jugador"=>$jugador["id"], "estado"=>1));
            if($res){

              $res = $this->insertData( 'ventas', array("liga_participantes_id"=>$liga_participantes,
              "jugadores_id"=>$jugador["id"],"temporada_liga_id"=>$temporada_liga, "monto"=>$jugador["valor_venta"], "fecha_creacion"=>date("Y-m-d H:i:s"), "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
              "usuario_edicion"=>$_SESSION["usuario"][0]["id"]));

              if($res){
                //borrar de plantilla
                $res = $this->deleteData("plantilla_jugadores", array("jugadores_id"=>$jugador["id"], "liga_participantes_id"=>$liga_participantes, "estado"=>1));

                if($res){

                  $res = $this->selectRowData("premios", "*",array("codigo"=>"ventas"));
                    if($res){

                      //registrar premio
                        $equi = $this->insertData( 'premios_historial', array("fecha_creacion"=>date("Y-m-d H:i:s"),
                        "usuario_edicion"=>$_SESSION["usuario"][0]["id"], "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                        "monto"=>$jugador["valor_venta"], "premios_id"=>$res["id"], "temporada_liga_id"=>$temporada_liga, "equipo_id"=>$equipo["id"],
                        "liga_id"=>$liga_id) );

                        if($equi){
                          //borrar jugador de equipo
                          $plantilla =   json_decode($equipo["jugadores"],true);
                          foreach ($plantilla as $key => $value) {
                            if($value == $jugador["id"]){
                              unset($plantilla[$key]);
                            }
                          }

                          $nueva_plantilla = json_encode(array_values($plantilla));

                          $res = $this->updateData("equipos", array("jugadores"=>$nueva_plantilla), array("id"=>$equipo["id"], "estado"=>1));
                          if($res){
                            $res = $this->updateData("saldo_disponible", array("saldo"=>$nuevo_saldo), array("liga_participantes_id"=>$liga_participantes));
                            if($res){
                              echo json_encode(array("resultado"=>true, "mensaje"=>"Venta Realizada"));
                            }else{
                              exit;
                              echo json_encode(array("resultado"=>false, "mensaje"=>'Ha ocurrido un error'));
                            }

                          }else{
                            exit;
                            echo json_encode(array("resultado"=>false, "mensaje"=>'Ha ocurrido un error'));
                          }
                        }else{
                          exit;
                          echo json_encode(array("resultado"=>false, "mensaje"=>'Ha ocurrido un error en registro de premio'));
                        }

                    }else{
                      exit;
                      echo json_encode(array("resultado"=>false, "mensaje"=>'Ha ocurrido un error. Intente de nuevo'));
                    }


                }else{
                  exit;
                  echo json_encode(array("resultado"=>false, "mensaje"=>'Ha ocurrido un error. Intente de nuevo'));
                }

              }else{
                exit;
                echo json_encode(array("resultado"=>false, "mensaje"=>'Error. Venta no realizada'));
              }

            }else{
              exit;
              echo json_encode(array("resultado"=>false, "mensaje"=>'Jugador no encontrado'));
            }

          }else{
            echo json_encode(array("resultado"=>false, "mensaje"=>'No puedes vender este jugador hasta que respondas la solicitud'));
          }
        }else{
          echo json_encode(array("resultado"=>false, "mensaje"=>"Venta no permitida. El período de negociaciones ha culminado"));
        }

        $this->commit();
      }catch(Exception $e) {
        $this->rollback();

        echo json_encode(array("resultado"=>false, "mensaje"=>$e->errorInfo));

      }

    }


  public function cargar_partidos_copa($temporada_liga_id){

    $res = $this->executeQuery("SELECT a.*, (SELECT e.nombre FROM equipos e WHERE e.id = a.equipo_local AND e.estado=1) as nombre_local,
    (SELECT e.nombre FROM equipos e WHERE e.id = a.equipo_visitante AND e.estado=1) as nombre_visitante,
    (SELECT g.eliminado FROM equipos g WHERE g.id = a.equipo_local AND g.estado=1) as eliminado_local,
    (SELECT h.eliminado FROM equipos h WHERE h.id = a.equipo_visitante AND h.estado=1) as eliminado_visitante
    FROM partidos_copa a
    WHERE a.estado =1 AND a.temporada_liga_id=". $temporada_liga_id ." AND a.registrado=0 AND a.equipo_local!='' AND a.equipo_visitante!='' ");
    if($res){
      echo json_encode(array("resultado"=>true, "data"=>$res));
    }
 }

 public function cargar_goles_liga($id_partidos_liga){

  $res = $this->selectRowData( 'partidos_liga', '*', array("id"=>$id_partidos_liga));
  if($res){
    echo json_encode(array("resultado"=>true, "data"=>$res));
  }
}

public function buscar_equipos_tabla($id_liga){

  $res = $this->executeQuery("SELECT *, (SELECT ((select sum(c.goles_casa) FROM partidos_liga c where c.casa = b.id and c.registrado=1)+(select sum(d.goles_visitante) FROM partidos_liga d where d.visitante = b.id and d.registrado=1 and d.temporada_liga_id=a.temporada_liga_id)) FROM partidos_liga d LIMIT 1) as goles_anotados, (SELECT ((select sum(e.goles_visitante) FROM partidos_liga e where e.casa = b.id and e.registrado=1 and e.temporada_liga_id=a.temporada_liga_id)+(select sum(f.goles_casa) FROM partidos_liga f where f.visitante = b.id and f.registrado=1 and f.temporada_liga_id=a.temporada_liga_id)) FROM partidos_liga f LIMIT 1) goles_recibidos, (SELECT ((select count(*) FROM partidos_liga g where g.casa = b.id and g.goles_casa>g.goles_visitante and g.registrado=1 and g.temporada_liga_id=a.temporada_liga_id)+(select count(*) FROM partidos_liga h where h.visitante = b.id and h.goles_casa<h.goles_visitante and h.registrado=1 and h.temporada_liga_id=a.temporada_liga_id)) FROM partidos_liga LIMIT 1) partidos_ganados,
  (SELECT ((select count(*) FROM partidos_liga i where i.casa = b.id and i.goles_casa<i.goles_visitante and i.registrado=1 and i.temporada_liga_id=a.temporada_liga_id)+(select count(*) FROM partidos_liga j where j.visitante = b.id and j.goles_visitante<j.goles_casa and j.registrado=1 and j.temporada_liga_id=a.temporada_liga_id)) FROM partidos_liga LIMIT 1) partidos_perdidos,
  (SELECT count(*) FROM partidos_liga k where ( k.casa = b.id or k.visitante=b.id ) and k.goles_casa=k.goles_visitante and k.registrado=1 and k.temporada_liga_id=a.temporada_liga_id) partidos_empatados
  FROM liga_participantes a LEFT JOIN equipos b ON a.id = b.liga_participantes_id WHERE a.liga_id=".$id_liga." and b.estado<>0");

  if($res){
    echo json_encode(array("resultado"=>true, "data"=>$res));
  }

}
public function buscar_equipos_tabla_by_temporada($liga_id, $temporada_liga_id){

  $sql ="SELECT *, (SELECT ( IFNULL((select sum(c.goles_casa) FROM partidos_liga c where c.casa = b.id and c.registrado=1 and c.temporada_liga_id=".$temporada_liga_id."),0)+  IFNULL((select sum(d.goles_visitante) FROM partidos_liga d where d.visitante = b.id and d.registrado=1 and d.temporada_liga_id=".$temporada_liga_id."),0)) FROM partidos_liga d LIMIT 1) as goles_anotados,
  (SELECT ( IFNULL((select sum(e.goles_visitante) FROM partidos_liga e where e.casa = b.id and e.registrado=1 and e.temporada_liga_id=".$temporada_liga_id."),0)   + IFNULL((select sum(f.goles_casa) FROM partidos_liga f where f.visitante = b.id and f.registrado=1 and f.temporada_liga_id=".$temporada_liga_id."),0)) FROM partidos_liga f LIMIT 1) goles_recibidos,
  (SELECT ((select count(*) FROM partidos_liga g where g.casa = b.id and g.goles_casa>g.goles_visitante and g.registrado=1 and g.temporada_liga_id=".$temporada_liga_id.")+(select count(*) FROM partidos_liga h where h.visitante = b.id and h.goles_casa<h.goles_visitante and h.registrado=1 and h.temporada_liga_id=".$temporada_liga_id.")) FROM partidos_liga LIMIT 1) partidos_ganados,
  (SELECT ((select count(*) FROM partidos_liga i where i.casa = b.id and i.goles_casa<i.goles_visitante and i.registrado=1 and i.temporada_liga_id=".$temporada_liga_id.")+(select count(*) FROM partidos_liga j where j.visitante = b.id and j.goles_visitante<j.goles_casa and j.registrado=1 and j.temporada_liga_id=".$temporada_liga_id.")) FROM partidos_liga LIMIT 1) partidos_perdidos,
  (SELECT count(*) FROM partidos_liga k where ( k.casa = b.id or k.visitante=b.id ) and k.goles_casa=k.goles_visitante and k.registrado=1 and k.temporada_liga_id=".$temporada_liga_id.") partidos_empatados
  FROM liga_participantes a LEFT JOIN equipos b ON a.id = b.liga_participantes_id WHERE a.liga_id=".$liga_id." AND b.eliminado=0 ";


  $res= $this->executeQuery($sql);

  if($res){

    echo json_encode(array("resultado"=>true, "data"=>$res));
  }

}
public function cargar_equipos_historial($user_id){

  $res = $this->executeQuery("");

  if($res){
    echo json_encode(array("resultado"=>true, "data"=>$res));
  }

}
  public function cargar_negociaciones($liga_participantes_id, $temporada_liga_id){

    $ventas=0;
    $compras=0;
    $compras_acumu=0;
    $ventas_acumu=0;
  $compras = $this->executeQuery("SELECT a.*, (SELECT b.nombre  FROM jugadores b WHERE b.id = a.jugadores_id ) nombre_jugador,
   (SELECT d.numero FROM temporada_liga d WHERE d.id = a.temporada_liga_id ) numero_temporada,
  (SELECT b.overall  FROM jugadores b WHERE b.id = a.jugadores_id ) overall
  FROM compras a WHERE a.liga_participantes_id = ".$liga_participantes_id."  AND a.estado=1 ORDER BY a.fecha_creacion DESC");

    if($compras){
      $compras_acumu = 0;
      foreach($compras as $item => $value){
        $compras_acumu = $value["monto"] + $compras_acumu;
      }

    }

      $ventas = $this->executeQuery("SELECT a.*, (SELECT b.nombre  FROM jugadores b WHERE b.id = a.jugadores_id ) nombre_jugador,
       (SELECT d.numero FROM temporada_liga d WHERE d.id = a.temporada_liga_id ) numero_temporada,
       (SELECT b.overall  FROM jugadores b WHERE b.id = a.jugadores_id ) overall FROM ventas a WHERE a.liga_participantes_id = ".$liga_participantes_id."
        AND a.estado=1 ORDER BY a.fecha_creacion DESC");


      if($ventas){
        $ventas_acumu = 0;
        foreach($ventas as $item => $value){
          $ventas_acumu = $value["monto"] + $ventas_acumu;
        }
      }
        $saldo_disponible = $this->selectRowData( 'saldo_disponible','*', array("liga_participantes_id"=>$liga_participantes_id) );
        if($liga_participantes_id){
          echo json_encode(array("resultado"=>true, "compras"=>$compras, "ventas"=>$ventas, "total_compras"=>$compras_acumu, "total_ventas"=>$ventas_acumu, "saldo_disponible"=>$saldo_disponible["saldo"]));
        }else{
          echo json_encode(array("resultado"=>true, "compras"=>$compras, "ventas"=>$ventas, "total_compras"=>$compras_acumu, "total_ventas"=>$ventas_acumu, "saldo_disponible"=>0));
        }


  }
  public function buscar_equipo_estadisticas($liga_id, $temporada_liga_id, $equipo_id){

    $res = $this->executeQuery("SELECT *, (SELECT estado FROM temporada_liga WHERE id = a.temporada_liga_id) temporada_estado, (SELECT ((select sum(c.goles_casa) FROM partidos_liga c where c.casa = ".$equipo_id." and c.registrado=1)+(select sum(d.goles_visitante) FROM partidos_liga d where d.visitante = ".$equipo_id." and d.registrado=1 and d.temporada_liga_id=".$temporada_liga_id.")) FROM partidos_liga d LIMIT 1) as goles_anotados, (SELECT ((select sum(e.goles_visitante) FROM partidos_liga e where e.casa = ".$equipo_id." and e.registrado=1 and e.temporada_liga_id=".$temporada_liga_id.")+(select sum(f.goles_casa) FROM partidos_liga f where f.visitante = ".$equipo_id." and f.registrado=1 and f.temporada_liga_id=".$temporada_liga_id.")) FROM partidos_liga f LIMIT 1) goles_recibidos, (SELECT ((select count(*) FROM partidos_liga g where g.casa = ".$equipo_id." and g.goles_casa>g.goles_visitante and g.registrado=1 and g.temporada_liga_id=".$temporada_liga_id.")+(select count(*) FROM partidos_liga h where h.visitante = ".$equipo_id." and h.goles_casa<h.goles_visitante and h.registrado=1 and h.temporada_liga_id=".$temporada_liga_id.")) FROM partidos_liga LIMIT 1) partidos_ganados,
    (SELECT ((select count(*) FROM partidos_liga i where i.casa = ".$equipo_id." and i.goles_casa<i.goles_visitante and i.registrado=1 and i.temporada_liga_id=".$temporada_liga_id.")+(select count(*) FROM partidos_liga j where j.visitante = ".$equipo_id." and j.goles_visitante<j.goles_casa and j.registrado=1 and j.temporada_liga_id=".$temporada_liga_id.")) FROM partidos_liga LIMIT 1) partidos_perdidos,
    (SELECT count(*) FROM partidos_liga k where ( k.casa = ".$equipo_id." or k.visitante= ".$equipo_id." ) and k.goles_casa=k.goles_visitante and k.registrado=1 and k.temporada_liga_id=".$temporada_liga_id.") partidos_empatados
    FROM liga_participantes a LEFT JOIN equipos b ON a.id = b.liga_participantes_id WHERE b.id=".$equipo_id." AND a.liga_id=".$liga_id);


    if($res){
      echo json_encode(array("resultado"=>true, "data"=>$res[0]));
    }

  }
  public function cargar_partidos_liga($tabla, $temporada_liga_id){
    $res = $this->executeQuery("SELECT a.*, (SELECT nombre FROM equipos WHERE id = a.casa ) nombre_local, (SELECT nombre FROM equipos WHERE id = a.visitante ) nombre_visitante FROM partidos_liga a INNER JOIN equipos b ON a.casa = b.id WHERE a.estado=1 AND a.temporada_liga_id = " . $temporada_liga_id);
    if($res){
      echo json_encode(array("resultado"=>true, "data"=>$res));
    }
  }
  public function cargar_premios_equipo($equipo_id){
    $res = $this->executeQuery("SELECT a.*, SUM(a.monto)AS premio, b.nombre AS descripcion,
    (SELECT d.numero FROM temporada_liga d WHERE d.id=a.temporada_liga_id) numero_temporada,
    (SELECT e.nombre FROM liga e WHERE e.id=a.liga_id) liga
    FROM premios_historial a INNER JOIN premios b ON a.premios_id=b.id WHERE a.equipo_id = ". $equipo_id." GROUP BY a.premios_id, a.temporada_liga_id ORDER BY a.fecha_creacion DESC");
    if($res){
      echo json_encode(array("resultado"=>true, "data"=>$res));
    }
  }
  public function cargar_gastos_equipo($equipo_id){
    $res = $this->executeQuery("SELECT a.*, SUM(a.monto)AS gasto, b.nombre AS descripcion,
     (SELECT d.numero FROM temporada_liga d WHERE d.id=a.temporada_liga_id) numero_temporada,
     (SELECT e.nombre FROM liga e WHERE e.id=a.liga_id) liga FROM gastos_historial a INNER JOIN gastos b ON a.gastos_id=b.id
     WHERE a.equipo_id =  ". $equipo_id." GROUP BY a.gastos_id, a.temporada_liga_id ORDER BY a.fecha_creacion DESC");

    if($res){
      echo json_encode(array("resultado"=>true, "data"=>$res));
    }
  }
  public function guardar_resultados_copa($valores,$where, $temporada_liga_id, $liga_id, $liga_participantes_id, $partido){
    try{

      $this->beginTransaction();

      $valores['fecha_edicion'] = date("Y-m-d H:i:s");
      $valores['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
      $gastos_id = $this->selectRowData('gastos','*', array("codigo"=>"salarios"));

      $res = $this->updateData('partidos_copa', $valores, $where);
      if($res){

        if($partido["codigo"]!='f'){
          //inserta nuevo partido
          $res = $this->selectRowData('partidos_copa','*', array("codigo"=>$partido["cod_proximo_partido"], "temporada_liga_id"=>$temporada_liga_id));
          if($res){
            if($res["equipo_local"]==""){
              $res = $this->updateData('partidos_copa', array("equipo_local"=>$valores["equipo_ganador"]), array("codigo"=>$partido["cod_proximo_partido"], "temporada_liga_id"=>$temporada_liga_id));
            }else{
              $res = $this->updateData('partidos_copa', array("equipo_visitante"=>$valores["equipo_ganador"]), array("codigo"=>$partido["cod_proximo_partido"], "temporada_liga_id"=>$temporada_liga_id));
            }
          }else {

            exit();
          }
        }

         //VERIFICAR SI TODOS SE HAN REGISTRADO
        if($res){

          $res = $this->selectRowData('partidos_copa','*', array("temporada_liga_id"=>$temporada_liga_id, "registrado"=>0));

          if($res){
            //TODAVÌA FALTAN PARTIDOS POR REGISTRAR
            echo json_encode(array("resultado"=>true, "mensaje"=>"Partido registrado"));
          }else{
            //TODOS LO PARTIDOS HAN SIDO REGISTRADOS
            //INSERTAR PREMIOS

            $res = $this->selectData('equipos', array("liga_id"=>$liga_id));

            if($res){

              $equipos = $res;
              $cantidad_participantes = count($equipos);

              foreach($equipos as $key => $item){
                $nuevo_saldo = 0;
                $res = $this->executeQuery("SELECT COUNT(equipo_local) as num from partidos_copa where equipo_local=".$item["id"]." OR equipo_visitante=".$item["id"]);

                if($res){
                  if($cantidad_participantes == 8){
                    if($res[0]["num"]==1){
                      //solo llegò a cuartos
                      $res = $this->selectRowData('premios','*', array("codigo"=>"c"));

                          if($res){
                            $monto_total_ganado = $res["monto"];
                            $saldos["temporada_liga_id"] = $temporada_liga_id;
                            $saldos["liga_id"] = $liga_id;
                            $saldos["equipo_id"] = $item["id"];
                            $saldos["premios_id"] = $res["id"];
                            $saldos['fecha_creacion'] = date("Y-m-d H:i:s");
                            $saldos['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
                            $saldos['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
                            $saldos['monto'] = $res["monto"];
                            $res = $this->insertData( 'premios_historial', $saldos );

                          }
                    }elseif($res[0]["num"]==2){
                      //lego a semis
                      $res = $this->selectRowData('premios','*', array("codigo"=>"s"));

                          if($res){

                            $monto_total_ganado = $res["monto"];
                            $saldos["temporada_liga_id"] = $temporada_liga_id;
                            $saldos["liga_id"] = $liga_id;
                            $saldos["equipo_id"] = $item["id"];
                            $saldos["premios_id"] = $res["id"];
                            $saldos['fecha_creacion'] = date("Y-m-d H:i:s");
                            $saldos['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
                            $saldos['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
                            $saldos['monto'] = $res["monto"];
                            $res = $this->insertData( 'premios_historial', $saldos );
                          }
                    }elseif($res[0]["num"]==3){
                      //lego  a finales
                      $res = $this->selectRowData('partidos_copa','*', array("temporada_liga_id"=>$temporada_liga_id, "codigo"=>"f"));

                      if($res){
                        if($res["equipo_ganador"]==$item["id"]){
                          //ganador
                          $res = $this->selectRowData('premios','*', array("codigo"=>"g"));
                          if($res){
                            $monto_total_ganado = $res["monto"];
                            $saldos["temporada_liga_id"] = $temporada_liga_id;
                            $saldos["liga_id"] = $liga_id;
                            $saldos["equipo_id"] = $item["id"];
                            $saldos["premios_id"] = $res["id"];
                            $saldos['fecha_creacion'] = date("Y-m-d H:i:s");
                            $saldos['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
                            $saldos['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
                            $saldos['monto'] = $res["monto"];
                            $res = $this->insertData( 'premios_historial', $saldos );
                          }
                        }else{
                          //perdedor
                          $res = $this->selectRowData('premios','*', array("codigo"=>"f"));
                          if($res){
                            $monto_total_ganado = $res["monto"];
                            $saldos["temporada_liga_id"] = $temporada_liga_id;
                            $saldos["liga_id"] = $liga_id;
                            $saldos["equipo_id"] = $item["id"];
                            $saldos["premios_id"] = $res["id"];
                            $saldos['fecha_creacion'] = date("Y-m-d H:i:s");
                            $saldos['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
                            $saldos['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
                            $saldos['monto'] = $res["monto"];
                            $res = $this->insertData( 'premios_historial', $saldos );
                          }
                        }
                      }else{
                        echo json_encode(array("resultado"=>false, "mensaje"=>$e->errorInfo));
                      }
                    }

                  }else{
                    if($res[0]["num"]==1){

                      //solo llegò a octavos
                      $res = $this->selectRowData('premios','*', array("codigo"=>"o"));


                          if($res){
                            $monto_total_ganado = $res["monto"];
                            $saldos["temporada_liga_id"] = $temporada_liga_id;
                            $saldos["liga_id"] = $liga_id;
                            $saldos["equipo_id"] = $item["id"];
                            $saldos["premios_id"] = $res["id"];
                            $saldos['fecha_creacion'] = date("Y-m-d H:i:s");
                            $saldos['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
                            $saldos['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
                            $saldos['monto'] = $res["monto"];
                            $res = $this->insertData( 'premios_historial', $saldos );

                          }else{

                          }
                    }elseif($res[0]["num"]==2){
                      //lego a cuartos
                      $res = $this->selectRowData('premios','*', array("codigo"=>"c"));

                          if($res){
                            $monto_total_ganado = $res["monto"];
                            $saldos["temporada_liga_id"] = $temporada_liga_id;
                            $saldos["liga_id"] = $liga_id;
                            $saldos["equipo_id"] = $item["id"];
                            $saldos["premios_id"] = $res["id"];
                            $saldos['fecha_creacion'] = date("Y-m-d H:i:s");
                            $saldos['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
                            $saldos['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
                            $saldos['monto'] = $res["monto"];
                            $res = $this->insertData( 'premios_historial', $saldos );
                          }
                    }elseif($res[0]["num"]==3){
                      //lego a semis
                      $res = $this->selectRowData('premios','*', array("codigo"=>"s"));

                          if($res){
                            $monto_total_ganado = $res["monto"];
                            $saldos["temporada_liga_id"] = $temporada_liga_id;
                            $saldos["liga_id"] = $liga_id;
                            $saldos["equipo_id"] = $item["id"];
                            $saldos["premios_id"] = $res["id"];
                            $saldos['fecha_creacion'] = date("Y-m-d H:i:s");
                            $saldos['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
                            $saldos['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
                            $saldos['monto'] = $res["monto"];
                            $res = $this->insertData( 'premios_historial', $saldos );
                          }
                    }elseif($res[0]["num"]==4){
                      //lego  a finales
                      $res = $this->selectRowData('partidos_copa','*', array("temporada_liga_id"=>$temporada_liga_id, "codigo"=>"f"));

                      if($res){
                        if($res["equipo_ganador"]==$item["id"]){
                          //ganador
                          $res = $this->selectRowData('premios','*', array("codigo"=>"g"));
                          if($res){
                            $monto_total_ganado = $res["monto"];
                            $saldos["temporada_liga_id"] = $temporada_liga_id;
                            $saldos["liga_id"] = $liga_id;
                            $saldos["equipo_id"] = $item["id"];
                            $saldos["premios_id"] = $res["id"];
                            $saldos['fecha_creacion'] = date("Y-m-d H:i:s");
                            $saldos['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
                            $saldos['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
                            $saldos['monto'] = $res["monto"];
                            $res = $this->insertData( 'premios_historial', $saldos );
                          }
                        }else{
                          //perdedor
                          $res = $this->selectRowData('premios','*', array("codigo"=>"f"));
                          if($res){
                            $monto_total_ganado = $res["monto"];
                            $saldos["temporada_liga_id"] = $temporada_liga_id;
                            $saldos["liga_id"] = $liga_id;
                            $saldos["equipo_id"] = $item["id"];
                            $saldos["premios_id"] = $res["id"];
                            $saldos['fecha_creacion'] = date("Y-m-d H:i:s");
                            $saldos['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
                            $saldos['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
                            $saldos['monto'] = $res["monto"];
                            $res = $this->insertData( 'premios_historial', $saldos );
                          }
                        }
                      }
                    }
                  }
                }else{
                  exit();
                }
              } // end foreach
              //FIN DE PREMIOS
              if($res){
                //verificar si la liga ha finalizado
                $res = $this->selectRowData('partidos_liga','*', array("temporada_liga_id"=>$temporada_liga_id, "registrado"=>0));
                if($res){
                    //todavia no ha finalizado la liga
                    echo json_encode(array("resultado"=>true, "mensaje"=>"Todos los resultados han sido registrados."));
                }else{
                    /* temporada finalizada.  */

                   /* BEGIN SALARIOS */
                    $todosEquipos =  $this->executeQuery("SELECT * FROM equipos WHERE liga_id=".$liga_id ." AND eliminado=0");
                    //$todosEquipos = $this->selectData('equipos', array("liga_id"=>$liga_id));
                    if($todosEquipos){

                        foreach ($todosEquipos as $item){

                            $plantilla =   json_decode($item["jugadores"],true);
                            $salario_monto = 0;
                            foreach ($plantilla as $indice => $id_jugador) {
                            //value = jugador id
                            $jugador_data = $this->selectRowData('jugadores_valor','*', array("id_jugador"=>$id_jugador));
                            $salario_monto = $jugador_data["salario"] + $salario_monto;
                            }

                            //pagar salario acumulado
                            $val['temporada_liga_id'] = $temporada_liga_id;
                            $val['monto'] = $salario_monto;
                            $val['gastos_id'] = $gastos_id["id"];
                            $val['liga_id'] = $liga_id;
                            $val['equipo_id'] = $item["id"];
                            $val['fecha_creacion'] = date("Y-m-d H:i:s");
                            $val['usuario_creacion'] = $_SESSION["usuario"][0]["id"];
                            $val['usuario_edicion'] = $_SESSION["usuario"][0]["id"];
                            $pagar_salario = $this->insertData( 'gastos_historial', $val );


                        }

                        // END SALARIOS //

                        //BEGIN ACTUALIZAR SALDOS
                        $equipos = $this->executeQuery("SELECT ((SELECT SUM(monto) FROM premios_historial  WHERE liga_id=".$liga_id."   AND
                        equipo_id= a.id) - (SELECT SUM(monto) FROM gastos_historial WHERE liga_id=".$liga_id." AND
                        equipo_id= a.id) ) saldo, a.liga_participantes_id FROM equipos a WHERE a.liga_id=".$liga_id." AND a.eliminado=0");
                        if($equipos){

                          foreach ($equipos as $key =>$item){
                              $res = $this->updateData('saldo_disponible', array("saldo"=>$item["saldo"]), array("liga_participantes_id"=>$item["liga_participantes_id"]));
                          }
                          //END ACTUALIZAR SALDOS

                            /* temporada finalizada. Cambiar a estado a negociaciones */
                            $res = $this->updateData('temporada_liga', array("estado"=>3, "fecha_edicion"=>date("Y-m-d H:i:s"), "usuario_edicion"=>$_SESSION["usuario"][0]["id"]), array("id"=>$temporada_liga_id));
                            if($res){
                            echo json_encode(array("resultado"=>true, "mensaje"=>"Todos los resultados han sido registrados. Ha empezado el período de negociaciones."));

                            }else{
                            echo json_encode(array("resultado"=>false));
                            }
                        }else{
                        exit();
                        }

                    }
                }
              }
            }else{
              echo json_encode(array("resultado"=>false, "mensaje"=>$e->errorInfo));
            }
          }

        }else{
          echo json_encode(array("resultado"=>false, "mensaje"=>$e->errorInfo));
        exit();
        }

      }

      $this->commit();
    }catch(Exception $e) {
      $this->rollback();
      echo json_encode(array("resultado"=>false, "mensaje"=>$e->errorInfo));
    }

  }
  public function actualizar_usuarios($campos, $where){
    if(isset($campos["usuario"])){
      $query = "SELECT * FROM usuarios   WHERE id != ".$campos["id"]." AND usuario = '" . $campos["usuario"]. "' ";
      $res = $this->executeQuery($query);
      if($res){
        //usuario repetido
        echo json_encode(array("resultado"=>false, "mensaje"=>"Este nombre de usuario ya existe, por favor, ingresar otro."));
      }
    }
      //actualizar usuario
      $res = $this->updateData('usuarios', $campos, $where);
      if($res){
        $_SESSION["usuario"][0]["nombre"]= $campos["nombre"];
        $_SESSION["usuario"][0]["apellido"]= $campos["apellido"];
        echo json_encode(array("resultado"=>true, "mensaje"=>"Usuario Actualizado"));
      }else{
        echo json_encode(array("resultado"=>false, "mensaje"=>"Ha ocurrido un error."));
      }


  }

  public function comprar_otro_equipo_jugador( $jugador, $liga_participantes, $temporada_liga_id, $equipo_id){
    try{
      $this->beginTransaction();

      $res = $this->selectRowData("jugadores_subastas", "*", array("liga_participantes_id"=>$liga_participantes, "jugadores_id"=>$jugador["id"], "estado"=>1));
      if($res){
        echo json_encode(array("resultado"=>false, "mensaje"=>"Este jugador ya está en tu lista de compras"));
      }else{
        $res = $this->insertData( 'jugadores_subastas', array("valor"=>$jugador["valor_compra"], "liga_participantes_id"=>$liga_participantes,"otro_equipo_id"=>$equipo_id,
        "temporada_liga_id"=>$temporada_liga_id,"jugadores_id"=>$jugador["id"],"fecha_creacion"=>date("Y-m-d H:i:s"), "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
        "usuario_edicion"=>$_SESSION["usuario"][0]["id"]));
        if($res){
          echo json_encode(array("resultado"=>true, "mensaje"=>"Jugador en lista de compras"));
        }else{
          echo json_encode(array("resultado"=>false, "mensaje"=>$e->errorInfo));
        }
      }

      $this->commit();
    }catch(Exception $e) {
      $this->rollback();

      echo json_encode(array("resultado"=>false, "mensaje"=>$e->errorInfo));

   }
  }
  public function cargar_plantilla($jugadores, $liga_participantes_id, $temporada_liga_id, $equipo_id){
    $jugadores = json_decode( $jugadores );
    $valores = '(';
    foreach ($jugadores as $item) {
      $valores .= $item . ',';
    }

    $valores = substr($valores, 0, -1);
    $valores = $valores . ')';
    $modelo = new modeloPortada();
    $res = $modelo->executeQuery("SELECT a.*, b.valor valor_compra FROM jugadores a LEFT JOIN jugadores_valor b ON a.id = b.id_jugador
    WHERE a.id IN ".$valores ." AND a.estado=1 ");

        if($res){
          echo json_encode(array("resultado"=>true, "plantilla"=>$res));

        }
  }

  public function cargar_solicitudes_compra($datos_liga){
    $modelo = new modeloPortada();
    $sql = "SELECT a.*, b.nombre as solicitante_nombre, b.jugadores as solicitante_jugadores_plantilla, b.id as solicitante_equipo_id, b.avatar as solicitante_avatar, (SELECT d.nombre FROM jugadores d WHERE d.id= a.jugadores_id) as jugador_nombre, (SELECT e.valor FROM jugadores_valor e WHERE e.id_jugador= a.jugadores_id) as jugador_valor FROM jugadores_subastas a INNER JOIN equipos b ON a.liga_participantes_id=b.liga_participantes_id WHERE a.otro_equipo_id=".$datos_liga["id"]." AND a.estado = 1";
    $res = $modelo->executeQuery($sql);
    if($res){
      echo json_encode(array("resultado"=>true, "data"=>$res));

    }

  }

  public function vender_otro_jugador($datos_liga, $solicitud, $nuevo_equipo){

    try{
      $this->beginTransaction();
      //verificar si el comprador tiene saldo
      $res = $this->selectRowData("saldo_disponible","*", array("liga_participantes_id"=>$solicitud["liga_participantes_id"], "estado"=>1));
      if($res){
        $saldo_disponible = $res["saldo"];
        if(floatval($solicitud["jugador_valor"])>floatval($saldo_disponible)){
          //saldo insuficiente, se elimina la solictud
          $res = $this->deleteData("jugadores_subastas", array("id"=>$solicitud["id"]));
          if($res){
            echo json_encode(array("resultado"=>false, "mensaje"=>'Saldo insuficiente'));
          }

        }else{
          //SALDO DISPONIBLE

          //PROCESO COMPRADOR
            //se inserta la compra en tablas compras
            $res = $this->insertData( 'compras', array("liga_participantes_id"=>$solicitud["liga_participantes_id"],
            "fecha_creacion"=>date("Y-m-d H:i:s"),
            "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
            "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
            "monto"=>$solicitud["valor"],
            "temporada_liga_id"=>$solicitud["temporada_liga_id"],
            "equipo_id"=>$solicitud["solicitante_equipo_id"],
            "jugadores_id"=>$solicitud["jugadores_id"]));

            if($res){
              //se registra el gasto

              $gasto = $this->selectRowData("gastos","*", array("codigo"=>"compras"));

              $res = $this->insertData("gastos_historial", array(
                "fecha_creacion"=>date("Y-m-d H:i:s"),
                "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
                "monto"=>$solicitud["valor"],
                "temporada_liga_id"=>$solicitud["temporada_liga_id"],
                "equipo_id"=>$solicitud["solicitante_equipo_id"],
                "liga_id"=>$datos_liga["liga_id"],
                "gastos_id"=>$gasto["id"]));

                if($res){
                  //insertar jugador a plantilla
                  $res = $this->insertData("plantilla_jugadores", array(
                    "fecha_creacion"=>date("Y-m-d H:i:s"),
                    "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                    "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
                    "jugadores_id"=>$solicitud["jugadores_id"],
                    "liga_id"=>$datos_liga["liga_id"],
                    "liga_participantes_id"=>$solicitud["liga_participantes_id"]));
                    if($res){
                      //insertar jugador al equipo
                      $res = $this->updateData( 'equipos', array("jugadores"=>$nuevo_equipo), array("id"=>$solicitud["solicitante_equipo_id"]));
                      if($res){
                        //se actualiza el saldo
                        $nuevo_saldo = floatval($saldo_disponible) -  floatval($solicitud["valor"]) ;
                        $res = $this->updateData( 'saldo_disponible', array("saldo"=>$nuevo_saldo), array("liga_participantes_id"=>$solicitud["liga_participantes_id"]));
                        if($res){
                          //borrar el registro de jugadores subastas
                          $res = $this->deleteData("jugadores_subastas", array("id"=>$solicitud["id"]));
                          if($res){
                             //PROCESO VENDEDOR
                              //se inserta la venta
                              $res = $this->insertData( 'ventas', array(
                                "fecha_creacion"=>date("Y-m-d H:i:s"),
                                "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                                "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
                                "jugadores_id"=>$solicitud["jugadores_id"],
                                "monto"=>$solicitud["valor"],
                                "temporada_liga_id"=>$datos_liga["temporada_id"],
                                "liga_participantes_id"=>$datos_liga["liga_participantes_id"]));

                                if($res){
                                  //se inserta la venta en premios_historial
                                  $premio = $this->selectRowData("premios","*", array("codigo"=>"ventas"));

                                  $res = $this->insertData("premios_historial",
                                  array(
                                    "fecha_creacion"=>date("Y-m-d H:i:s"),
                                    "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                                    "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
                                    "monto"=>$solicitud["valor"],
                                    "temporada_liga_id"=>$datos_liga["temporada_id"],
                                    "equipo_id"=>$datos_liga["equipo_id"],
                                    "liga_id"=>$datos_liga["liga_id"],
                                    "premios_id"=>$premio["id"]));
                                    if($res){
                                      //quitar el jugador de la platilla
                                      $res = $this->deleteData("plantilla_jugadores", array("jugadores_id"=>$solicitud["jugadores_id"], "liga_participantes_id"=>$datos_liga["liga_participantes_id"], "estado"=>1));
                                      if($res){
                                        //borrar jugador de equipo
                                        $plantilla =   json_decode($datos_liga["jugadores"],true);
                                        foreach ($plantilla as $key => $value) {
                                          if($value == $solicitud["jugadores_id"]){
                                            unset($plantilla[$key]);
                                          }
                                        }
                                        $nueva_plantilla = json_encode(array_values($plantilla));
                                        $res = $this->updateData( 'equipos', array("jugadores"=>$nueva_plantilla), array("id"=>$datos_liga["equipo_id"]));
                                        if($res){
                                          //actualizar saldo_disponible
                                          $nuevo_saldo = floatval($datos_liga["saldo_disponible"]) -  floatval($solicitud["valor"]) ;
                                          $res = $this->updateData( 'saldo_disponible', array("saldo"=>$nuevo_saldo), array("liga_participantes_id"=>$datos_liga["liga_participantes_id"]));
                                          if($res){
                                            echo json_encode(array("resultado"=>true));
                                          }else{
                                            exit();
                                          }
                                        }
                                      }
                                    }
                                }
                          }else{
                            exit();
                          }
                        }
                      }
                    }
                }


            }else{
              exit();
            }
        }
      }
      $this->commit();
    }catch(Exception $e) {
      $this->rollback();
      echo json_encode(array("resultado"=>false, "mensaje"=>$e->errorInfo));

    }

  }
  public function cancelar_venta( $solicitud){
    $res = $this->deleteData("jugadores_subastas", array("id"=>$solicitud["id"]));
    if($res){
      echo json_encode(array("resultado"=>true));
    }
  }

  public function iniciar_periodo_subastas( $datos_liga){

    try{
      $this->beginTransaction();
      //  borrar compras a otro jugador
      $res = $this->executeQuery("SELECT * FROM jugadores_subastas WHERE otro_equipo_id <> '' ");

      if($res){

        $res = $this->deleteData("jugadores_subastas", array("otro_equipo_id"=>"NOT NULL"));

        if(!$res){
          exit();
        }
      }

      //ejecutar las compras de los jugadores que son compra directa
      $sql = "SELECT a.*, COUNT(*) cantidad_solicitudes, b.liga_id liga_id, b.id equipo_id, b.jugadores equipo_jugadores FROM jugadores_subastas a INNER JOIN equipos b ON a.liga_participantes_id=b.liga_participantes_id WHERE a.estado =1 GROUP by a.jugadores_id";
      $res = $this->executeQuery($sql);

      if($res ){

       foreach ($res as $key => $value) {
          if($value["cantidad_solicitudes"]==1){

              //verificar saldo
              $saldo_disponible = $this->selectRowData("saldo_disponible","*", array("liga_participantes_id"=>$value["liga_participantes_id"]));

              if(floatval($saldo_disponible["saldo"]) >= floatval($value["valor"])){

               //tiene saldo
                //se inserta la compra en tablas compras
                $res = $this->insertData( 'compras', array("liga_participantes_id"=>$value["liga_participantes_id"],
                "fecha_creacion"=>date("Y-m-d H:i:s"),
                "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
                "monto"=>$value["valor"],
                "temporada_liga_id"=>$value["temporada_liga_id"],
                "equipo_id"=>$value["equipo_id"],
                "jugadores_id"=>$value["jugadores_id"]));

                if($res){
                  //se registra el gasto
                  $gasto = $this->selectRowData("gastos","*", array("codigo"=>"compras"));
                  $res = $this->insertData("gastos_historial", array(
                    "fecha_creacion"=>date("Y-m-d H:i:s"),
                    "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                    "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
                    "monto"=>$value["valor"],
                    "temporada_liga_id"=>$value["temporada_liga_id"],
                    "equipo_id"=>$value["equipo_id"],
                    "liga_id"=>$value["liga_id"],
                    "gastos_id"=>$gasto["id"]));

                    if($res){
                      //insertar jugador a plantilla
                      $res = $this->insertData("plantilla_jugadores", array(
                        "fecha_creacion"=>date("Y-m-d H:i:s"),
                        "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
                        "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
                        "jugadores_id"=>$value["jugadores_id"],
                        "liga_id"=>$value["liga_id"],
                        "liga_participantes_id"=>$value["liga_participantes_id"]));
                        if($res){

                          $plantilla =   json_decode($value["equipo_jugadores"],true);
                          array_push($plantilla, $value["jugadores_id"]);
                          $plantilla = json_encode(array_values($plantilla));
                          //insertar jugador al equipo
                          $res = $this->updateData( 'equipos', array("jugadores"=>$plantilla,"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("id"=>$value["equipo_id"]));
                          if($res){
                              //borrar el registro de jugadores subastas
                              $res = $this->deleteData("jugadores_subastas", array("id"=>$value["id"]));
                              if($res){
                              //actualizar su saldo
                              $res = $this->executeQuery("SELECT ((SELECT SUM(monto) FROM premios_historial  WHERE liga_id=".$value["liga_id"]." AND
                                  equipo_id= a.id) - (SELECT SUM(monto) FROM gastos_historial WHERE liga_id=".$value["liga_id"]."  AND
                                  equipo_id= a.id) ) saldo FROM equipos a WHERE liga_id=".$value["liga_id"]. " AND a.liga_participantes_id=".$value["liga_participantes_id"]);
                                if($res){


                                  $consulta = $this->selectRowData("saldo_disponible","*", array("liga_participantes_id"=>$value["liga_participantes_id"]));
                                  if($consulta["saldo"]!=$res[0]["saldo"]){
                                   // $res2 = $this->updateData('saldo_disponible', array("saldo"=>$res[0]["saldo"],"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("liga_participantes_id"=>$value["liga_participantes_id"]));
                                    $res2 = $this->updateData('saldo_disponible', array("saldo"=>$res[0]["saldo"],"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("liga_participantes_id"=>$value["liga_participantes_id"]));
                                    if(!$res2){
                                      echo json_encode(array("resultado"=>false, "mensaje"=>$e->errorInfo));
                                      exit();
                                    }
                                  }
                                  if(!$res){

                                    exit();
                                  }
                                }
                              }
                          }
                        }
                    }
                }
             }else{
               //no tiene saldo

                //borrar el registro de jugadores subastas
                $res = $this->deleteData("jugadores_subastas", array("id"=>$value["id"]));
                if($res){
                //actualizar su saldo
                $query = "SELECT ((SELECT SUM(monto) FROM premios_historial  WHERE liga_id=".$value["liga_id"]."  AND
                equipo_id= a.id) - (SELECT SUM(monto) FROM gastos_historial WHERE liga_id=".$value["liga_id"]."  AND
                equipo_id= a.id) ) saldo FROM equipos a WHERE liga_id=".$value["liga_id"]. " AND a.liga_participantes_id=".$value["liga_participantes_id"];
                $res = $this->executeQuery($query);
                  if($res){

                    $consulta = $this->selectRowData("saldo_disponible","*", array("liga_participantes_id"=>$value["liga_participantes_id"]));
                    if($consulta["saldo"]!=$res[0]["saldo"]){
                      $res2 = $this->updateData('saldo_disponible', array("saldo"=>$res[0]["saldo"],"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("liga_participantes_id"=>$value["liga_participantes_id"]));
                      if(!$res2){

                        exit();
                      }
                    }

                  }
                }

             }

          }
       }


      }


        // actualizar temporada a período de subastas
        //verificar si queda algo en jugadores_subastas
        $res = $this->selectRowData("jugadores_subastas","*", array("temporada_liga_id"=>$datos_liga["temporada_id"]));

        if($res){
          $res = $this->updateData( 'temporada_liga', array("estado"=>4,"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("id"=> $datos_liga["temporada_id"]));
          if($res){
            echo json_encode(array("resultado"=>true, "mensaje"=>"Ha iniciado el perìodo de subastas."));
          }else{
            exit();
          }
        }else{
          $res = $this->updateData( 'temporada_liga', array("estado"=>5,"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("id"=> $datos_liga["temporada_id"]));
          if($res){
            echo json_encode(array("resultado"=>true, "mensaje"=>"Ningún jugador para subasta. Ha iniciado el período de compras extemporáneas."));
          }else{
            exit();
          }
        }

      $this->commit();
    }catch(Exception $e) {
      $this->rollback();
      echo json_encode(array("resultado"=>false, "mensaje"=>$e->errorInfo));

    }
  }
  public function finalizar_temporada($datos_liga){
    try{
      $this->beginTransaction();

        if($datos_liga["temporada_numero"] >= $datos_liga["numero_temporadas"]){

          //finaliza la temporada
          $res = $this->updateData( 'temporada_liga', array("estado"=>0,"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("id"=>$datos_liga["temporada_id"]));
          if($res){

            //la liga pasa a estado 0
            $res = $this->updateData( 'liga', array("estado"=>0,"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("id"=>$datos_liga["liga_id"]));

            if($res){
              //los equipos paan a estado 0
              $res = $this->updateData( 'equipos', array("estado"=>0,"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("liga_id"=>$datos_liga["liga_id"]));

              if($res){
                //los participantes de la liga pasan ha estado 0
                $res = $this->updateData( 'liga_participantes', array("estado"=>0,"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("liga_id"=>$datos_liga["liga_id"]));

                if($res){
                  //la copa pasa estado 0
                  $res = $this->updateData( 'copa', array("estado"=>0,"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("temporada_liga_id"=>$datos_liga["temporada_id"]));

                  if($res){
                    //desactivar los usuarios
                    $res = $this->executeQuery( "SELECT a.* FROM usuarios a INNER JOIN liga_participantes b ON b.usuario_creacion = a.id WHERE b.liga_id=".$datos_liga["liga_id"]);

                    if($res){
                      foreach ($res as $key => $value) {
                          $res = $this->updateData( 'usuarios', array("activo"=>0, "is_admin"=>0,"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("id"=>$value["id"]));
                      }
                      if($res){
                        echo json_encode(array("resultado"=>true, "finalizada"=>true, "mensaje"=>"Temporada finalizada. La liga ha culminado"));
                      }
                   }
                  }

                }
              }
            }
          }

        }else{
          //la temporada actual cambia estado 0
          $res = $this->updateData( 'temporada_liga', array("estado"=>0,"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("id"=>$datos_liga["temporada_id"]));

          //se abre una nueva temporada
          $numero_nueva_temporada = intval($datos_liga["temporada_numero"]) + 1;
          $res = $this->insertData( 'temporada_liga', array("liga_id"=>$datos_liga["liga_id"],
              "fecha_inicio"=>date("Y-m-d H:i:s"),
              "fecha_creacion"=>date("Y-m-d H:i:s"),
              "usuario_creacion"=>$_SESSION["usuario"][0]["id"],
              "usuario_edicion"=>$_SESSION["usuario"][0]["id"],
              "numero"=>$numero_nueva_temporada,
              "estado"=>1));
          if($res){
            $res = $this->getLastId('temporada_liga');
            if($res){
              //se actualiza la tbla liga_participantes con el id de la temporada actual
              $temporada_liga_id = $res[0]["id"];
              $res = $this->updateData( 'liga_participantes', array("temporada_liga_id"=>$temporada_liga_id,"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("liga_id"=>$datos_liga["liga_id"]));
              if($res){
                $res = $this->updateData( 'copa', array("estado"=>0,"usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")), array("temporada_liga_id"=>$datos_liga["temporada_id"]));
                if($res){
                  echo json_encode(array("resultado"=>true, "finalizada"=>false, "mensaje"=>"Temporada finalizada. Ha empezado la nueva temporada"));
                }
              }

            }


          }
        }


      $this->commit();
    }catch(Exception $e) {
      $this->rollback();
      echo json_encode(array("resultado"=>false, "mensaje"=>$e->errorInfo));

    }
  }

  public function cargar_equipos_copa_grafico($datos_liga, $temporada_liga_id){
    $query = "SELECT *, (SELECT b.nombre FROM equipos b WHERE  b.liga_id=".$datos_liga["liga_id"]." AND a.equipo_local=b.id) equipo_local_nombre,
     (SELECT b.nombre FROM equipos b WHERE b.liga_id=".$datos_liga["liga_id"]." AND a.equipo_visitante=b.id) equipo_visitante_nombre,
     (SELECT b.avatar FROM equipos b WHERE b.liga_id=".$datos_liga["liga_id"]." AND a.equipo_local=b.id) equipo_local_avatar,
      (SELECT b.avatar FROM equipos b WHERE b.liga_id=".$datos_liga["liga_id"]." AND a.equipo_visitante=b.id) equipo_visitante_avatar
       FROM partidos_copa a WHERE a.temporada_liga_id=".$temporada_liga_id;
    $equipos = $this->executeQuery($query);
    if($equipos){
      $copa = $this->selectRowData("copa","*", array("temporada_liga_id"=>$temporada_liga_id));
      if($copa){
        $newequipos= array();
        foreach ($equipos as $key => $value) {

          $datos_local =  ($value["equipo_local_nombre"]==null)? '': $value["equipo_local_nombre"] ;
          $goles_local =  ($value["goles_local"]==null)? '': ": " . $value["goles_local"] . " goles";
          $datos_visitante =  ($value["equipo_visitante_nombre"]==null)? '': $value["equipo_visitante_nombre"];
          $goles_visitante =  ($value["goles_visitante"]==null)? '': ": " . $value["goles_visitante"] . " goles";
          $equipos[$key]["datos_local"]= $datos_local . $goles_local;
          $equipos[$key]["datos_visitante"]=$datos_visitante. $goles_visitante;
          if($equipos[$key]["goles_local"] != null && $equipos[$key]["goles_local"]!= null){
            if( $equipos[$key]["goles_local"] >$equipos[$key]["goles_visitante"] ){
              $equipos[$key]["imagen"] = "../app/cargas/equipos_avatar/".$equipos[$key]["equipo_local_avatar"];

            }else{
              $equipos[$key]["imagen"] = "../app/cargas/equipos_avatar/".$equipos[$key]["equipo_visitante_avatar"];

            }
            $equipos[$key]["estado_partido"] = "Finalizado";
          }else{
            $equipos[$key]["imagen"] ="../images/equipo.jpg";
            $equipos[$key]["estado_partido"] = "Pendiente";
          }
          $equipos[$key]["code"] = $value["codigo"] == null?$value["id"]:$value["codigo"];

        }

        echo json_encode(array("resultado"=>true,"data"=> $equipos, "copa"=>$copa));
      }

    }
  }


  public function cargar_equipos_liga($datos_liga){
    $res = $this->executeQuery("SELECT a.*, (SELECT  c.numero FROM temporada_liga c WHERE c.liga_id = a.liga_id ORDER BY id DESC LIMIT 1) AS temporada,
    (SELECT  d.id FROM temporada_liga d WHERE d.liga_id = a.liga_id ORDER BY d.id DESC LIMIT 1) AS temporada_id,
    (SELECT d.nombre FROM liga d WHERE d.id = a.liga_id LIMIT 1) AS nombre_liga FROM equipos a WHERE a.liga_id=". $datos_liga["liga_id"] );
    foreach($res as $key => $item){

      if($item["eliminado"]==1){
        $res[$key]["estado_equipo"] = 'Eliminado';
      }else{
        if($item["estado"]==0){
          $res[$key]["estado_equipo"] = 'Inactivo';
        }elseif($item["estado"]==1){
          $res[$key]["estado_equipo"] = 'Activo';
        }elseif($item["estado"]==2){
          $res[$key]["estado_equipo"] = 'En espera del kickoff';
        }else{
          $res[$key]["estado_equipo"] = 'Activo';
        }
      }
    }

    if($res){
        echo json_encode(array("resultado"=>true, "data"=>$res));

    }
  }
  public function eliminar_equipo($equipo){
    //actualizar el estado
    $res = $this->updateData( 'equipos',
    array("eliminado"=>1,"jugadores"=>"","teams_id"=>"","usuario_edicion"=> $_SESSION["usuario"][0]["id"],"fecha_edicion"=> date("Y-m-d H:i:s")),
    array("id"=>$equipo["id"]));
    //liberar plantilla
    if($res){
      $res = $this->deleteData('plantilla_jugadores', array("liga_participantes_id"=>$equipo["liga_participantes_id"], "estado"=>1));
      if($res){
        echo json_encode(array("resultado"=>true));
      }
    }


  }
}
