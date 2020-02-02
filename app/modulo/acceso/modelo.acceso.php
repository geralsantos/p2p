<?php
class modeloAcceso extends MySQL{

    public function getSesion($usuario, $clave){
        $usuario = $this->executeQuery("select * from usuarios where usuario=:usuario and clave=:clave",array("usuario"=>$usuario,"clave"=>$clave));
        if($usuario){

            $sql = "SELECT b.*, (SELECT t.estado FROM temporada_liga t WHERE t.liga_id=b.id ORDER BY t.id DESC LIMIT 1) as temporada_estado,
             (SELECT t.numero FROM temporada_liga t WHERE t.liga_id=b.id ORDER BY t.id DESC LIMIT 1) as temporada_numero,
            c.nombre as equipo, c.id as equipo_id FROM liga_participantes a LEFT JOIN liga b on a.liga_id=b.id LEFT JOIN equipos c ON b.id=c.liga_id WHERE b.estado=1 AND c.usuario_creacion = ".$usuario[0]["id"]. " ORDER BY b.fecha_creacion LIMIT 1";

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
            }else{
                $usuario[0]["temporada_numero"] ='';
                $usuario[0]["solicitudes"]='';
                $usuario[0]["codigo"]= '';
                $usuario[0]["liga"]= 'Ninguna';
                $usuario[0]["equipo"]= 'Ninguno';
                $usuario[0]["estado_liga"] = 'Liga no registrada';
            }
        }
        return $usuario;
    }
    public function getSesionFacebook($id_facebook){
        $usuario = $this->executeQuery("select * from usuarios where id_fb=:id_fb",array("id_fb"=>$id_facebook));
        return $usuario;
    }
    public function registro($plan_id, $usuario, $email, $clave, $nombre, $apellido){
      $values=array("plan_id"=>$plan_id,"nombre"=>$nombre,"apellido"=>$apellido,"usuario"=>$usuario,"correo"=>$email,"clave"=>$clave,"fecha_creacion"=>date("Y-m-d H:i:s"));
      $usuario = $this->insertData("usuarios",$values);
      return $usuario;
    }
    public function registroFacebook($plan_id,$name, $email, $id_facebook){
      $values=array("plan_id"=>$plan_id,"nombre"=>$name,"correo"=>$email,"id_fb"=>$id_facebook,"fecha_creacion"=>date("Y-m-d H:i:s"));
      $usuario = $this->insertData("usuarios",$values);
      return $usuario;
    }
}
