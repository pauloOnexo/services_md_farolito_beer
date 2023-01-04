<?php
/*defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );
header( 'Access-Control-Allow-Methods: POST' );
header( 'Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding' );
*/

class Articulo extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->model( 'login_model' );
        $this->load->model( 'Articulo_model' );
        $this->load->model( 'Sucursal_model' );
        $this->load->model( 'Categoria_model' );
        $this->load->model( 'Subcategoria_model' );
        $this->load->model( 'Experiencia_model' );
		$this->load->model( 'Idioma_model');
        $this->load->model( 'Extra_model' );
        $this->load->model( 'Registros_model' );
    }

    function index() {

        $_POST = json_decode( file_get_contents( 'php://input' ), true );
        $token = $_GET['token'];
        $id_marca = $_GET['id_marca'];
        $response = array();
        //verificar token
        $res = $this->login_model->buscar_token( $token );
        if ( count( $res ) == 0 ) {
            $response['status'] = 'Error';
            $response['respuesta'] = 'Error al ingresar';

        } else {

            $response['status'] = 'OK';
            $response['respuesta'] = 'Ingreso correcto';
            $response['registros'] = $this->Articulo_model->get( $id_marca );
        }
        //$this->load->view( 'Articulos', $response );

        echo json_encode( $response );
    }
    public function add() {

        $response = array();
        $token = $_GET['token'];
        $id_marca = $_GET['id_marca'];
        $token = $_GET['token'];
        $res = $this->login_model->buscar_token( $token );
        if ( count( $res ) == 0 ) {
            $response['status'] = 'Error';
            $response['respuesta'] = 'Error al ingresar';

        } else {
            $response['status'] = 'OK';
            $response['respuesta'] = 'Ingreso correcto';
            $response['sucursales'] = $this->Sucursal_model->get( $id_marca );
            $response['categorias'] = $this->Categoria_model->get( $id_marca );
            $response['subcategorias'] = $this->Subcategoria_model->get_categoria( $id_marca );
            $response['experiencias'] = $this->Experiencia_model->get_todas( $id_marca );
            $response['extras'] = $this->Extra_model->get_activos();

        }
        echo json_encode( $response );
        //$this->load->view( 'insert_Articulo', $response );
    }

    public function checkNow(){
        $response = array();
        $response['today'] = "2022-11-30 13:30:00";

        echo json_encode( $response );
        
    }

     public function add_db() {
        header( 'Access-Control-Allow-Origin', '*' );

        $response = array();

        $envio_imagen_titulo = false;
        $nombre_titulo = '';
        $nuevo_titulo = '';
        $temporal_titulo = '';
		
		$token = $_POST['token'];
		$res = $this->login_model->buscar_token( $token );
        $data = array();
		 if ( count( $res ) == 0 ) {
                $response['status'] = 'Error';
                $response['respuesta'] = 'Error al ingresar';

        } else {
			if ( !isset( $_POST['detalle_imagen'] ) ) {
				$nombre_titulo = $_FILES['detalle_imagen']['name'];
				list( $base, $extension ) = explode( '.', $nombre_titulo );
				$nuevo_titulo = implode( '.', [$base, time(), $extension] );
				$nuevo_titulo = trim( $nuevo_titulo );
				$temporal_titulo = $_FILES['detalle_imagen']['tmp_name'];
			} else {
				$nombre_titulo = '';
				$temporal_titulo = '';
			}
			$nombre = '';
			$nuevo = '';
			$temporal = '';
			$nuevo_min='';
			if ( !isset( $_POST['file'] ) ) {
				$nombre = $_FILES['file']['name'];
				list( $base, $extension ) = explode( '.', $nombre );
				$nuevo = implode( '.', [$base, time(), $extension] );
				$nuevo_min = implode('.', [$base."_min", time(), $extension]);
				if ( move_uploaded_file( $_FILES['file']['tmp_name'], getenv('DIR_FILES').'/menudigital/Articulos/'.$nuevo ) ) {
			
				//Rediminesionar imagenes min
				$this->redimensionarImagen(getenv('DIR_FILES')."/menudigital/Articulos/".$nuevo,getenv('DIR_FILES')."/menudigital/Articulos/".$nuevo_min, 300, 300);
				} else {
					$response['status'] = 'ERROR';
					$response['respuesta'] = 'Error al cargar archivo';
				}
			} else {
				$nombre = '';
				$temporal = '';
			}     
        
			$medidas = json_decode( $_POST['medidas'], true );
            if ( $nombre_titulo != '' )
            {
                if ( move_uploaded_file( $temporal_titulo, getenv('DIR_FILES').'/menudigital/Articulos/'.$nuevo_titulo ) ) {
                    $envio_imagen_titulo = true;
					$data['detalle_imagen'] =  '/menudigital/Articulos/'.$nuevo_titulo;
					$data['nombre_detalle_imagen'] = $nuevo_titulo;
                } else {
                    $response['status'] = 'ERROR';
                    $response['respuesta'] = 'Error al cargar archivo';
                }
            }
			if ( isset( $_POST['extra'] ) ) {
				$data['extra'] = $_POST['extra'];
			}
			if ( isset( $_POST['etiqueta_extra'] ) ) {
				$data['etiqueta_extra'] = $_POST['etiqueta_extra'];
			}
			
			if ( isset( $_POST['numero_opciones'] ) ) {
				$data['numero_opciones'] = $_POST['numero_opciones'];
			}
            if ( isset( $_POST['precio_colegio_porcentaje'] ) ) {
				$data['precio_colegio_porcentaje'] = $_POST['precio_colegio_porcentaje'];
			}else{
                $data['precio_colegio_porcentaje'] = '0';
            }
            if ( isset( $_POST['precio_colegio_cuota'] ) ) {
				$data['precio_colegio_cuota'] = $_POST['precio_colegio_cuota'];
			}else{
                $data['precio_colegio_cuota'] = '0';
            }
			$data['platillo'] = $_POST['platillo'];
			$data['id_categoria'] = $_POST['id_categoria'];
			//'categorias' => $_POST['categorias'],
			$data['id_subcategoria'] = $_POST['id_subcategoria'];
			$data['id_experiencia'] = $_POST['id_experiencia'];
			$data['nombre'] = $nuevo;
			$data['descripcion'] = $_POST['descripcion'];
			$data['logo'] = $_POST['logo'];
			$data['simbologia'] = $_POST['simbologia'];
			// 'ubicacion' => 'https://pruebasgerard.com/menudigital/Articulos/'.$nuevo,
			$data['detalle_imagen'] =  '/menudigital/Articulos/'.$nuevo_titulo;
			$data['nombre_detalle_imagen'] = $nuevo_titulo;
			$data['ubicacion'] = '/menudigital/Articulos/'.$nuevo;
			$data['imagen'] = $nuevo_titulo;
			$data['imagen_min'] = $nuevo_min;
			$data['precio_nacional'] = $_POST['nacional'];
			$data['precio_acapulco'] = $_POST['acapulco'];
			$data['precio_cc'] = $_POST['cc'];
			$data['precio_tijuana'] = $_POST['tijuana'];
			$data['precio_pl'] = $_POST['pl'];
			$data['precio_aeropuerto'] = $_POST['precio_aeropuerto'];
			$data['precio_tezontle'] = $_POST['precio_tezontle'];
			$data['orden'] = $_POST['orden'];
			$data['descripcion_imagen'] = $_POST['descripcion_imagen'];
			$data['activo'] = 1;  
                $data['vigencia'] = $_POST['vigencia']; 
                $obj3['fechainicio'] = $_POST['fechainicio'];
                $obj3['fechafin'] = $_POST['fechafin'];
                //if($_POST['vigencia'] ==1){
                    $data['fechainicio'] = $_POST['fechainicio'];  
                    $data['fechafin'] = $_POST['fechafin']; 
                //} 
                $id_insertado = $this->Articulo_model->add( $data );
// ACTUALIZACION AGREGAR CON SUCURSALES RESTRINGIDAS
                   // La sucursales nuevas a restringir para el producto
            $sucursalesEntrantes = $_POST['id_sucursales'];

            // Caso si las sucursales entrantes vienen sin valores
            if ($sucursalesEntrantes == "") {
                // Transformando la data a un array
                $sucursalesEntrantes_arr = [];
            }else{
                // Transformando la data a un array
                $sucursalesEntrantes_arr = explode(",",$sucursalesEntrantes);
            }
                if (count($sucursalesEntrantes_arr)> 0) {
                    // Caso cuando en la nueva inserción se seleccionaron sucursales a restringir
                    foreach ($sucursalesEntrantes_arr as $key => $value) {
                        $this->Sucursal_model->updateRestrictions($id_insertado,$value);
                    }
                }
            
			if ( $id_insertado>0 ) {
				if ( $_POST['medidas'] != '' ) {
					foreach ( $medidas as $value ) {
						$data_medidas = array(
							'id_articulo' => $id_insertado,
							'numero' => $value['numero'],
							'nombre' => $value['nombre_medida'],
							'precio_nacional' => $value['precio_nacional'],
							'precio_acapulco' => $value['precio_acapulco'],
							'precio_cc' => $value['precio_cc'],
							'precio_tijuana' => $value['precio_tijuana'],
							'precio_pl' => $value['precio_pl'],
							'precio_aeropuerto' => $value['precio_aeropuerto'],
							'precio_tezontle' => $value['precio_tezontle'],
							'precio_colegio_porcentaje' => $value['precio_colegio_porcentaje'],
							'precio_colegio_cuota' => $value['precio_colegio_cuota'],
							'cantidad_x_porcion_medida'=>$value['cantidad_x_porcion_medida'],
							'activo' => 1,
						);

						if ( $this->Articulo_model->add_articulo_medidas( $data_medidas ) ) {
							$response['status'] = 'OK';
							$response['respuesta'] = 'Artículo registrado correctamente';
						} else {
							$response['status'] = 'ERROR';
							$response['respuesta'] = 'Error al registrar el articulo';
						}
					}
				} else {
					$response['status'] = 'OK';
					$response['respuesta'] = 'Artículo registrado correctamente';
				}
			} else {
				$response['status'] = 'ERROR';
				$response['respuesta'] = 'Error al registrar la sucursl';
			}
		}
        
        echo json_encode( $response );
    }

    function update() {

        $token = $_GET['token'];
        $id = $_GET['id'];
        $id_marca = $_GET['id_marca'];
        $response = array();
		$id_idioma= (!isset($_GET['id_idioma'])) ? "0" : $_GET['id_idioma'];
        $res = $this->login_model->buscar_token( $token );
        if ( count( $res ) == 0 ) {
            $response['status'] = 'Error';
            $response['respuesta'] = 'Error al ingresar';

        } else {
			if($id_idioma>0){
				$resI = $this->Articulo_model->getByIdIdioma( $id,$id_idioma );
				$dataI = array(
					'id' => $resI['id'],
					'platillo' => $resI['platillo'],
					'id_categoria' => $resI['id_categoria'],
					'categorias' => $resI['categorias'],
					'id_subcategoria' => $resI['id_subcategoria'],
					'id_experiencia' => $resI['id_experiencia'],
					'descripcion' => $resI['descripcion'],
					'logo' => $resI['logo'],
					'simbologia' => $resI['simbologia'],
					'ubicacion' => $resI['ubicacion'],
					'precio_nacional' => $resI['precio_nacional'],
					'precio_acapulco' => $resI['precio_acapulco'],
					'precio_cc' => $resI['precio_cc'],
					'precio_tijuana' => $resI['precio_tijuana'],
					'precio_pl' => $resI['precio_pl'],
					'precio_aeropuerto' => $resI['precio_aeropuerto'],
					'precio_tezontle' => $resI['precio_tezontle'],
					'precio_colegio_porcentaje' => $resI['precio_colegio_porcentaje'],
					'precio_colegio_cuota' => $resI['precio_colegio_cuota'],
					'precio_aeropuerto_aifa' => $resI['precio_aeropuerto_aifa'],
					'activo' => $resI['activo'],
					'orden' => $resI['orden'],
					'cantidad_x_porcion' => $resI['cantidad_x_porcion'],
					'descripcion_imagen' => $resI['descripcion_imagen'],
					'medidas' => $this->Articulo_model->get_medidas_idioma( $id,$id_idioma ),
					'detalle_imagen' => $resI['detalle_imagen'],
					'extra' => $resI['extra'],
					'etiqueta_extra' => $resI['etiqueta_extra'],
					'numero_opciones' => $resI['numero_opciones']

				);
				$response['status'] = 'OK';
				$response['respuesta'] = 'Ingreso correcto';
				$response['registro'] = $dataI;
			}
			else{
				$response['status'] = 'OK';
				$response['respuesta'] = 'Ingreso correcto';
				$res = $this->Articulo_model->getById( $id );
                $sucursalesRestringidas = $this->Articulo_model->getSucursalesRestringidas($res['id']);
				$data = array(
					'id' => $res['id'],
					'platillo' => $res['platillo'],
					'id_categoria' => $res['id_categoria'],
					'categorias' => $res['categorias'],
					'id_subcategoria' => $res['id_subcategoria'],
					'id_experiencia' => $res['id_experiencia'],
					'descripcion' => $res['descripcion'],
					'logo' => $res['logo'],
					'simbologia' => $res['simbologia'],
					'ubicacion' => $res['ubicacion'],
					'precio_nacional' => $res['precio_nacional'],
					'precio_acapulco' => $res['precio_acapulco'],
					'precio_cc' => $res['precio_cc'],
					'precio_tijuana' => $res['precio_tijuana'],
					'precio_pl' => $res['precio_pl'],
					'precio_aeropuerto' => $res['precio_aeropuerto'],
					'precio_tezontle' => $res['precio_tezontle'],
					'precio_colegio_porcentaje' => $res['precio_colegio_porcentaje'],
					'precio_colegio_cuota' => $res['precio_colegio_cuota'],
					'precio_aeropuerto_aifa' => $res['precio_aeropuerto_aifa'],
					'activo' => $res['activo'],
					'orden' => $res['orden'],
					'cantidad_x_porcion' => $res['cantidad_x_porcion'],
					'descripcion_imagen' => $res['descripcion_imagen'],
					'medidas' => $this->Articulo_model->get_medidas( $id ),
					'detalle_imagen' => $res['detalle_imagen'],
					'extra' => $res['extra'],
					'etiqueta_extra' => $res['etiqueta_extra'],
					'vigencia' => $res['vigencia'],
					'fechainicio' => $res['fechainicio'],
					'fechafin' => $res['fechafin'],
                    'restricciones' => $sucursalesRestringidas


				);
				$response['registro'] = $data;
			}
			
            $response['categorias'] = $this->Categoria_model->get( $id_marca );
            $response['subcategorias'] = $this->Subcategoria_model->get_categoria( $id_marca );
            $response['experiencias'] = $this->Experiencia_model->get_todas( $id_marca );
            $response['extras'] = $this->Extra_model->get_activos();
		    $response["idiomas"] = $this->Idioma_model->get_activos();
            $response['sucursales'] = $this->Sucursal_model->obtener_activas( $id_marca );
            //$response['sucursales'] = $this->Sucursal_model->get( 0 );
        }
        echo json_encode( $response );
        //$this->load->view( 'update_Articulo', $response );
    }

    public function update_db() {
        //$_POST = json_decode( file_get_contents( 'php://input' ), true );
        header( 'Access-Control-Allow-Origin', '*' );

        $token = $_POST['token'];

        $response = array();
        $data = array();

        $envio_imagen = false;
        $medidas = json_decode( $_POST['medidas'], true );

        $envio_imagen_titulo = false;
        $nombre_titulo = '';
        $nuevo_titulo = '';
        $temporal_titulo = '';

        if ( !isset( $_POST['detalle_imagen'] ) ) {
            $nombre_titulo = $_FILES['detalle_imagen']['name'];
            list( $base, $extension ) = explode( '.', $nombre_titulo );
            $nuevo_titulo = implode( '.', [$base, time(), $extension] );
            $nuevo_titulo = trim( $nuevo_titulo );
            $temporal_titulo = $_FILES['detalle_imagen']['tmp_name'];
        } else {
            $nombre_titulo = '';
            $temporal_titulo = '';
        }

        
        $nombre = '';
        $nuevo = '';
        $temporal = '';
        if ( !isset( $_POST['file'] ) ) {
            $nombre = $_FILES['file']['name'];
            list( $base, $extension ) = explode( '.', $nombre );
			$nuevo = implode( '.', [$base, time(), $extension] );
			$nuevo_min = implode('.', [$base."_min", time(), $extension]);
            $temporal = $_FILES['file']['tmp_name'];
        } else {
            $nombre = '';
            $temporal = '';
        }
        $res = $this->login_model->buscar_token( $token );
        if ( count( $res ) == 0 ) {
            $response['status'] = 'Error';
            $response['respuesta'] = 'Error al ingresar';

        } else {
            if ( $nuevo != '' )
            {

                // $data['ubicacion'] =  'https://pruebasgerard.com/menudigital/Articulos/'.$nuevo;
                $data['ubicacion'] =  '/menudigital/Articulos/'.$nuevo;
				$data['imagen'] = $nuevo;
				$data['imagen_min'] = $nuevo_min;
                if ( move_uploaded_file( $temporal, getenv('DIR_FILES').'/menudigital/Articulos/'.$nuevo ) ) {
					$envio_imagen = true;
					$this->redimensionarImagen(getenv('DIR_FILES')."/menudigital/Articulos/".$nuevo,getenv('DIR_FILES')."/menudigital/Articulos/".$nuevo_min, 300, 300);
                } else {
                    $response['status'] = 'ERROR';
                    $response['respuesta'] = 'Error al cargar archivo';
                }
            }

            if ( $nombre_titulo != '' && strlen($nombre_titulo) > 2)
            {

                
                if ( move_uploaded_file( $temporal_titulo, getenv('DIR_FILES').'/menudigital/Articulos/'.$nombre_titulo ) ) {
                    $data['detalle_imagen'] =  '/menudigital/Articulos/'.$nombre_titulo;
					$data['nombre_detalle_imagen'] = $nuevo_titulo;
					$envio_imagen_titulo = true;
                } else {
                    $response['status'] = 'ERROR';
                    $response['respuesta'] = 'Error al cargar archivo';
                }
            }
            if ($_POST['file_delete'] == 1) {
                $data['imagen'] =  '';
                $data['descripcion_imagen'] =  '';
                $data['detalle_imagen'] =  '';
                $data['nombre_detalle_imagen'] = '';
                $data['imagen_min'] = '';
            }
			if ( isset( $_POST['extra'] ) ) {
				$data['extra'] = $_POST['extra'].',';
			}
			
			if ( isset( $_POST['etiqueta_extra'] ) ) {
				$data['etiqueta_extra'] = $_POST['etiqueta_extra'];
			}
			if ( isset( $_POST['numero_opciones'] ) ) {
				$data['numero_opciones'] = $_POST['numero_opciones'];
			}

            // La sucursales nuevas a restringir para el producto
            $sucursalesEntrantes = $_POST['id_sucursales'];

            // Caso si las sucursales entrantes vienen sin valores
            if ($sucursalesEntrantes == "") {
                // Transformando la data a un array
                $sucursalesEntrantes_arr = [];
            }else{
                // Transformando la data a un array
                $sucursalesEntrantes_arr = explode(",",$sucursalesEntrantes);
            }


            $sucursalesWhereExist = $this->Sucursal_model->findProductRestrictionInSucursal( $_POST['id']);

            // echo json_encode($sucursalesEntrantes);
            // echo json_encode($sucursalesWhereExist);
            
            if (!count($sucursalesWhereExist)>0) {
                // Caso cuando la restriccion de este producto no existe actualmente en alguna sucursal
                if (count($sucursalesEntrantes_arr)> 0) {
                    // Caso cuando en la nueva inserción se seleccionaron sucursales a restringir
                    foreach ($sucursalesEntrantes_arr as $key => $value) {
                        $this->Sucursal_model->updateRestrictions($_POST['id'],$value);
                    }
                }
                
            }else{

                foreach ($sucursalesWhereExist as $key => $value) {
                    unset($value->{"nombre"});
                    unset($value->{"res_arts"});
                }
                $newSucursalesWhereExist = array_column($sucursalesWhereExist, 'id');

                sort($newSucursalesWhereExist);
                sort($sucursalesEntrantes_arr);

                if ($newSucursalesWhereExist != $sucursalesEntrantes_arr) {
                    // echo json_encode('Los arrays no son iguales');
                    // Llamada a función para borra la restricción por cada sucursal dónde se encuentre
                    
                    foreach ($newSucursalesWhereExist as $key => $value) {
                        if (!$this->Sucursal_model->removeRestrictionSucursal($_POST['id'],$value)) {
                            return json_encode('Ocurrio un error al remover las viejas restricciones');
                        }
                    }

                    // return;

                    // Llamada para insertar los nuevos registros
                    foreach ($sucursalesEntrantes_arr as $key => $value) {
                        $this->Sucursal_model->updateRestrictions($_POST['id'],$value);
                    }


                }

            }


            $data ['nombre'] = $_POST['nombre'] != 'null'? $_POST['nombre'] : '';
            $data ['activo'] = $_POST['activo'];

            $data ['platillo'] = $_POST['nombre'] != 'null'? $_POST['nombre'] : '';
            $data ['id_categoria'] = $_POST['id_categoria'];
            //$data ['categorias'] = $_POST['categorias'];
            $data ['id_subcategoria'] = $_POST['id_subcategoria'];
            $data ['id_experiencia'] = $_POST['id_experiencia'];
            $data ['descripcion'] =  $_POST['descripcion'] != 'null'? $_POST['descripcion'] : '';
            $data ['logo'] =  $_POST['logo'] != 'null'? $_POST['logo'] : '';
            $data ['simbologia'] =  $_POST['simbologia'] != 'null'? $_POST['simbologia'] : '';
            $data ['precio_nacional'] = $_POST['nacional'];
            $data ['precio_acapulco'] = $_POST['acapulco'];
            $data ['precio_cc'] = $_POST['cc'];
            $data ['precio_tijuana'] = $_POST['tijuana'];
            $data ['precio_pl'] = $_POST['pl'];
            $data ['precio_aeropuerto'] = $_POST['precio_aeropuerto'];
            $data ['precio_tezontle'] = $_POST['precio_tezontle'];
            $data ['precio_aeropuerto_aifa'] = $_POST['precio_aeropuerto_aifa'];

            // $data ['precio_colegio_porcentaje'] = $_POST['precio_colegio_porcentaje'] != 'null' ? '0' : $_POST['precio_colegio_porcentaje']; // VERIFICAR 14/06/2022
            
            if ( isset( $_POST['precio_colegio_porcentaje'] ) ) {
				$data['precio_colegio_porcentaje'] = $_POST['precio_colegio_porcentaje'];
			}else{
                $data['precio_colegio_porcentaje'] = '0';
            }

            if ( isset( $_POST['precio_colegio_cuota'] ) ) {
				$data['precio_colegio_cuota'] = $_POST['precio_colegio_cuota'];
			}else{
                $data['precio_colegio_cuota'] = '0';
            }
            // $data ['precio_colegio_cuota'] = $_POST['precio_colegio_cuota'] != 'null' ? '0' : $_POST['precio_colegio_cuota'];
            $data ['vigencia'] = $_POST['vigencia'];
            $data ['fechainicio'] = $_POST['fechainicio'];
            $data ['fechafin'] = $_POST['fechafin'];

            $data ['cantidad_x_porcion'] = $_POST['cantidad_x_porcion'] != 'null'? $_POST['cantidad_x_porcion'] : '';
            $data ['orden'] = $_POST['orden'];
            $data ['descripcion_imagen'] = $_POST['descripcion_imagen'] != 'null'? $_POST['descripcion_imagen'] : '';

			if ( isset( $_POST['id_idioma']) && $_POST['id_idioma'] >0 ) {
				
				$data ['id_articulo'] = $_POST['id'];
				$data ['id_idioma'] = $_POST['id_idioma'];
				if ( $this->Articulo_model->update_idioma( $data, $_POST['id'],$_POST['id_idioma'] ) ) {
					if ( $_POST['medidas'] != '' ) {
						$this->Articulo_model->del_medidas_idioma( $_POST['id'],$_POST['id_idioma'] );
						foreach ( $medidas as $value ) {
							$data_medidas = array(
								'id_articulo' => $_POST['id'],
								'id_idioma' => $_POST['id_idioma'],
								'numero' => $value['numero'],
								'nombre' => $value['nombre_medida'],
								'precio_nacional' => $value['precio_nacional'],
								'precio_acapulco' => $value['precio_acapulco'],
								'precio_cc' => $value['precio_cc'],
								'precio_tijuana' => $value['precio_tijuana'],
								'precio_pl' => $value['precio_pl'],
								'precio_aeropuerto' => $value['precio_aeropuerto'],
								'precio_tezontle' => $value['precio_tezontle'],
								'precio_colegio_porcentaje' => $value['precio_colegio_porcentaje'],
								'precio_colegio_cuota' => $value['precio_colegio_cuota'],
								'precio_aeropuerto_aifa' => $value['precio_aeropuerto_aifa'],
								'cantidad_x_porcion_medida' => $value['cantidad_x_porcion_medida'],
								'id_articulo_medida' => $value['id_articulo_medida'],
								'activo' => $value['activo'],
							);

							if ( $this->Articulo_model->add_articulo_medidas_idioma( $data_medidas ) ) {
								$response['status'] = 'OK';
								$response['respuesta'] = 'Artículo registrado correctamente';
							} else {
								$response['status'] = 'ERROR';
								$response['respuesta'] = 'Error al registrar el articulo';
							}
						}
					} else {
						$response['status'] = 'OK';
						$response['respuesta'] = 'Artículo actualizado correctamente';
					}
				} else {
					$response['status'] = 'ERROR';
					$response['respuesta'] = 'Error al actualizar el artículo';
				}
			}
			else{
				if ( $this->Articulo_model->update( $data, $_POST['id'] ) ) {
					$this->Articulo_model->del_medidas( $_POST['id'] );
					if ( $_POST['medidas'] != '' ) {
						foreach ( $medidas as $value ) {
							$data_medidas = array(
								'id_articulo' => $_POST['id'],
								'numero' => $value['numero'],
								'nombre' => $value['nombre_medida'],
								'precio_nacional' => $value['precio_nacional'],
								'precio_acapulco' => $value['precio_acapulco'],
								'precio_cc' => $value['precio_cc'],
								'precio_tijuana' => $value['precio_tijuana'],
								'precio_pl' => $value['precio_pl'],
								'precio_aeropuerto' => $value['precio_aeropuerto'],
								'precio_tezontle' => $value['precio_tezontle'],
								'precio_colegio_porcentaje' => $value['precio_colegio_porcentaje'],
								'precio_colegio_cuota' => $value['precio_colegio_cuota'],
								'precio_aeropuerto_aifa' => $value['precio_aeropuerto_aifa'],

								'cantidad_x_porcion_medida' => $value['cantidad_x_porcion_medida'],
								'activo' => $value['activo'],
							);

							if ( $this->Articulo_model->add_articulo_medidas( $data_medidas ) ) {
								$response['status'] = 'OK';
								$response['respuesta'] = 'Artículo registrado correctamente';
							} else {
								$response['status'] = 'ERROR';
								$response['respuesta'] = 'Error al registrar el articulo';
							}
						}
					} else {
						$response['status'] = 'OK';
						$response['respuesta'] = 'Artículo actualizado correctamente';
					}
				} else {
					$response['status'] = 'ERROR';
					$response['respuesta'] = 'Error al actualizar el artículo';
				}
			}
        }
        echo json_encode( $response );
    }

    public function delete() {
        //$_POST = json_decode( file_get_contents( 'php://input' ), true );
        $token = $_POST['token'];

        $id = $_POST['id'];

        $response = array();
        $res = $this->login_model->buscar_token( $token );
        if ( count( $res ) == 0 ) {
            $response['status'] = 'Error';
            $response['respuesta'] = 'Error al ingresar';

        } else {
            if ( $this->Articulo_model->eliminar( $id ) ) {
                $response['status'] = 'OK';
                $response['respuesta'] = 'Artículo eliminada correctamente';
            } else {
                $response['status'] = 'ERROR';
                $response['respuesta'] = 'Error al eliminar el artículo';
            }
        }

        echo json_encode( $response );
    }

	function getRealIP() {

        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];
           
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
       
        return $_SERVER['REMOTE_ADDR'];
	}
    function articulos_categoria() {

        $id_categoria = $_GET['categoria'];
        $id_sucursal = $_GET['sucursal'];
        $token = $_GET['token'];
        $hora_solicitud = $_GET['hora_solicitud'];
        $fecha_solicitud = $_GET['fecha_solicitud'];
		/********************************registro*/
			date_default_timezone_set('America/Mexico_City');
			$datos_registros = array();
			$datos_registros ['hora_solicitud'] = $hora_solicitud;
			$datos_registros ['id_categoria'] = $id_categoria;
			$datos_registros ['id_sucursal'] = $id_sucursal;
			$datos_registros ['fecha'] = date("Y-m-d");
			$datos_registros ['fecha_solicitud'] = $fecha_solicitud;
			$datos_registros ['ip'] = $_SERVER['REMOTE_ADDR'];
			
			/********************************registro*/
        $response = array();
        //verificar token
        $res = $this->login_model->buscar_token( $token );
		
        if ( count( $res ) == 0 ) {
            $response['status'] = 'Error';
            $response['respuesta'] = 'Error al ingresar';

        } else {

            $response['status'] = 'OK';
            $response['respuesta'] = 'Ingreso correcto';
            $categorias = array();
            $categorias = $this->Articulo_model->categoria_sucursal( $id_categoria, $id_sucursal );
            $sucursal = $this->db->query("SELECT * FROM Sucursal a where a.id = " . $id_sucursal." limit 1");

            $subcategorias = $this->Articulo_model->categoria_subcategorias( $id_categoria, $id_sucursal );
            $i = 0;

            $j = 0;

            $k = 0;

            $agregar = 0;
            $obj = array();
            $id_subcategorias = array();
            foreach ( $categorias as $fila ) {
                $obj ['categoria'] = $fila->nombre_categoria;
                $obj ['descripcion'] = $fila->descripcion_subcategoria;
                $id_categoria = $fila->nombre_categoria;
                $obj ['id_categoria'] = $fila->id_categoria;
                $obj ['ubicacion'] = $fila->ubicacion_categoria;
                $obj ['imagen_titulo_categoria'] = $fila->imagen_titulo_categoria;
                $obj2 = array();
                if ( !is_null( $subcategorias ) ) {
                    foreach ( $subcategorias as $fila ) {
                        $obj2['id_subcategoria'] = $fila->id_subcategoria;
                        $obj2['subcategoria'] = $fila->nombre_subcategoria;
                        $obj2['descripcion'] = $fila->descripcion_subcategoria;
                        $obj2['color_pleca'] = $fila->color_pleca;
                        $obj3 = array();

                        $id_articulos = array();
                        $id_subcategorias_ = $fila->id_subcategoria;
                        $art = array();
                        $art = $this->Articulo_model->articulo_subcategorias2( $id_subcategorias_, $id_sucursal, $sucursal );
                        $id_articulos3 = array();
                        foreach ( $art as $fila3 ) {
                            //print_r( $fila3 );
                            $obj3['id_articulo'] =  $fila3->id_articulo;
                            $obj3['platillo'] =  $fila3->platillo;
                            $obj3['nombre_articulo'] =  $fila3->nombre_articulo;
                            $obj3['descripcion_articulo'] =  $fila3->descripcion_articulo;
                            $obj3['simbologia'] =  $fila3->simbologia;
                            $obj3['logo'] =  $fila3->logo;
                            $obj3['id_experiencia'] =  $fila3->id_experiencia;
                            $obj3['nombre_experiencia'] =  $fila3->nombre_experiencia;
                            $obj3['descripcion_experiencia'] =  $fila3->descripcion_experiencia;
                            $obj3['ubicacion_experiencia'] =  $fila3->ubicacion_experiencia;
							$obj3['ubicacion_articulo'] =   $fila3->ubicacion_articulo;
							$obj3['ubicacion_articulo_min'] =   $fila3->ubicacion_articulo_min;
                            $obj3['precio'] =  $fila3->precio;
                            $obj3['cantidad_x_porcion'] = $fila3->cantidad_x_porcion;
                            $obj3['descripcion_imagen'] = $fila3->descripcion_imagen;
                            $obj3['orden'] = $fila3->orden;
                            $obj3['detalle_imagen'] = $fila3->detalle_imagen;
                            $obj3['vigencia'] = $fila3->vigencia;
                            $obj3['fechainicio'] = $fila3->fechainicio;
                            $obj3['fechafin'] = $fila3->fechafin;
                            $medida = array();
                            $medida = $this->Articulo_model->get_medidas_precio( $fila3->id_articulo, $fila3->precio, $id_sucursal, $id_subcategorias_, $fila3->nombre_articulo );
                            $experiencias = $this->Articulo_model->get_experiencias( $fila3->id_experiencia );
                            if ( !is_null( $medida ) ) {
                                $x = 0;
                                foreach ( $medida as $fila_medida ) {
                                    if ( $x == 0 ) {
                                        $obj3['precio'] =  $fila_medida->precio;
                                        break;
                                    }
                                }
                            }
                            $obj3['medidas'] = $medida;
                            $obj3['experiencias'] = $experiencias;
                            $id_articulos3[$k] = $obj3;
                            $k++;
                            unset( $medida );
                        }
                        unset( $art );
                        if ( count( $id_articulos3 )>0 ) {
                            $id_articulos[$j] = $id_articulos3;
                            unset( $id_articulos3 );
                            $j++;
                            $obj2['articulos'] = $id_articulos;
                            unset( $id_articulos );

                            $id_subcategorias[$i] = $obj2;
                            $i++;
                        }
                    }

                    $obj['subcategoria'] = $id_subcategorias;
                    unset( $id_subcategorias );
                }
                // if size subcategorias
                else {
                    $art = array();
                    $art = $this->Articulo_model->articulo_sin_subcategorias( $fila->id_categoria, $id_sucursal );
                    $id_articulos3 = array();
                    foreach ( $art as $fila3 ) {
                        //print_r( $fila3 );
                        $obj3['id_articulo'] =  $fila3->id_articulo;
                        $obj3['platillo'] =  $fila3->platillo;
                        $obj3['nombre_articulo'] =  $fila3->nombre_articulo;
                        $obj3['descripcion_articulo'] =  $fila3->descripcion_articulo;
                        $obj3['simbologia'] =  $fila3->simbologia;
                        $obj3['logo'] =  $fila3->logo;
                        // $obj3['id_experiencia'] =  $fila3->id_experiencia;
                        // $obj3['nombre_experiencia'] =  $fila3->nombre_experiencia;
                        // $obj3['descripcion_experiencia'] =  $fila3->descripcion_experiencia;
                        // $obj3['ubicacion_experiencia'] =  $fila3->ubicacion_experiencia;
						$obj3['ubicacion_articulo'] =   $fila3->ubicacion_articulo;
						$obj3['ubicacion_articulo_min'] =   $fila3->ubicacion_articulo_min;
                        $obj3['precio'] =  $fila3->precio;
                        $obj3['cantidad_x_porcion'] = $fila3->cantidad_x_porcion;
                        $obj3['descripcion_imagen'] = $fila3->descripcion_imagen;
                        $obj3['orden'] = $fila3->orden;
                        $obj3['detalle_imagen'] = $fila3->detalle_imagen;
                        $medida = array();
                        $medida = $this->Articulo_model->get_medidas_precio( $fila3->id_articulo, $fila3->precio, $id_sucursal, 0, $fila3->nombre_articulo );
                        $experiencias = $this->Articulo_model->get_experiencias( $fila3->id_experiencia );
                        $obj3['medidas'] = $medida;
                        $obj3['experiencias'] = $experiencias;
                        $id_articulos3[$k] = $obj3;
                        $k++;
                        unset( $medida );
                    }
                    unset( $art );
                    if ( count( $id_articulos3 )>0 ) {
                        $id_articulos[$j] = $id_articulos3;
                        $obj['articulos'] = $id_articulos3;
                        unset( $id_articulos3 );
                        $j++;

                        //unset( $id_articulos );

                        //$id_subcategorias[$i] = $obj;
                        //$i++;
                    }
                    //$obj['articulos'] = $obj;
                }

            }			
			$datos_registros ['hora_peticion'] = date("H:i:s");
			//$res2 = $this->Registros_model->add( $datos_registros );
            $response['categoria'] = $obj;
            echo json_encode( $response );
        }

    }
    function articulos_sucursal() {
        $id_sucursal = $_GET['sucursal'];
        $id_marca = $_GET['marca'];
        $token = $_GET['token'];
		/*$id_idioma =0;
		if(isset($_GET['id_idioma'])
			$id_idioma = $_GET['id_idioma'];*/
        $hora_solicitud = $_GET['hora_solicitud'];
        $fecha_solicitud = $_GET['fecha_solicitud'];
		/********************************registro*/
			date_default_timezone_set('America/Mexico_City');
			$datos_registros = array();
			$datos_registros ['hora_solicitud'] = $hora_solicitud;
			$datos_registros ['id_categoria'] = 0;
			$datos_registros ['id_sucursal'] = $id_sucursal;
			$datos_registros ['fecha'] = date("Y-m-d");
			$datos_registros ['fecha_solicitud'] = $fecha_solicitud;
			$datos_registros ['ip'] = $_SERVER['REMOTE_ADDR'];
			
			/********************************registro*/
        $response = array();
        //verificar token
        $res = $this->login_model->buscar_token( $token );
		
        if ( count( $res ) == 0 ) {
            $response['status'] = 'Error';
            $response['respuesta'] = 'Error al ingresar';

        } else {

            $response['status'] = 'OK';
            $response['respuesta'] = 'Ingreso correcto';
            $categorias_arreglo = array();
            $categorias = array();
            $categorias = $this->Categoria_model->get_activas_sucursal( $id_sucursal, $id_marca,0 );
            $sucursal = $this->db->query("SELECT * FROM Sucursal a where a.id = " . $id_sucursal." limit 1");
            $sucursalResult = array();

            $sucursalResult = $sucursal->result();
            $sucursalResult = array();

            $sucursalResult = $sucursal->result();
            $i = 0;$j = 0;$k = 0;$l=0;
            $agregar = 0;
            $obj = array();
            $id_subcategorias = array();
            foreach ( $categorias as $fila ) {
                $obj ['categoria'] = $fila->nombre_categoria;
                $obj ['descripcion'] = $fila->descripcion_categoria;
                $obj ['id_categoria'] = $fila->id_categoria;
                $obj ['ubicacion'] = $fila->ubicacion_categoria;
                $obj ['imagen_titulo_categoria'] = $fila->imagen_titulo_categoria;
				$obj ['imagen_fondo'] = $fila->imagen_fondo;
                $obj2 = array();
				$subcategorias = $this->Articulo_model->categoria_subcategorias( $fila->id_categoria, $id_sucursal );
                if ( !is_null( $subcategorias ) ) {
                    foreach ( $subcategorias as $fila2 ) {
                        $obj2['id_subcategoria'] = $fila2->id_subcategoria;
                        $obj2['subcategoria'] = $fila2->nombre_subcategoria;
                        $obj2['descripcion'] = $fila2->descripcion_subcategoria;
						$obj2['destacado'] = $fila2->destacado;
                        $obj2['color_pleca'] = $fila2->color_pleca;
                        $obj3 = array();

                        $id_articulos = array();
                        $id_subcategorias_ = $fila2->id_subcategoria;
                        $art = array();
                        $art = $this->Articulo_model->articulo_subcategorias2( $id_subcategorias_, $id_sucursal, $sucursal );
                        $id_articulos3 = array();
                        foreach ( $art as $fila3 ) {
                            //print_r( $fila3 );
                            $obj3['id_articulo'] =  $fila3->id_articulo;
                            $obj3['platillo'] =  $fila3->platillo;
                            $obj3['nombre_articulo'] =  $fila3->nombre_articulo;
                            $obj3['descripcion_articulo'] =  $fila3->descripcion_articulo;
                            $obj3['simbologia'] =  $fila3->simbologia;
                            $obj3['logo'] =  $fila3->logo;
                            $obj3['id_experiencia'] =  $fila3->id_experiencia;
                            $obj3['nombre_experiencia'] =  $fila3->nombre_experiencia;
                            $obj3['descripcion_experiencia'] =  $fila3->descripcion_experiencia;
                            $obj3['ubicacion_experiencia'] =  $fila3->ubicacion_experiencia;
							$obj3['ubicacion_articulo'] =   $fila3->ubicacion_articulo;
							$obj3['ubicacion_articulo_min'] =   $fila3->ubicacion_articulo_min;
                            $obj3['precio'] =  $fila3->precio;
                            $obj3['cantidad_x_porcion'] = $fila3->cantidad_x_porcion;
                            $obj3['descripcion_imagen'] = $fila3->descripcion_imagen;
                            $obj3['orden'] = $fila3->orden;
                            $obj3['detalle_imagen'] = $fila3->detalle_imagen;
                            $obj3['etiqueta_extra'] = $fila3->etiqueta_extra;
                            $obj3['numero_opciones'] = $fila3->numero_opciones;
                            $obj3['vigencia'] = $fila3->vigencia;
                            $obj3['fechainicio'] = $fila3->fechainicio;
                            $obj3['fechafin'] = $fila3->fechafin;
                            $medida = array();
                            $medida = $this->Articulo_model->get_medidas_precio( $fila3->id_articulo, $fila3->precio, $id_sucursal, $id_subcategorias_, $fila3->nombre_articulo );
                            $experiencias = $this->Articulo_model->get_experiencias( $fila3->id_experiencia );
                            $extras = $this->Articulo_model->get_extras( $fila3->extra,$id_sucursal );
                            if ( !is_null( $medida ) ) {
                                $x = 0;
                                foreach ( $medida as $fila_medida ) {
                                    if ( $x == 0 ) {
                                        $obj3['precio'] =  $fila_medida->precio;
                                        break;
                                    }
                                }
                            }
                            $obj3['medidas'] = $medida;
                            $obj3['experiencias'] = $experiencias;
                            $obj3['extras'] = $extras;
                            $id_articulos3[$k] = $obj3;
                            $k++;
                            unset( $medida );
                        }
                        //unset( $art );
                        if ( count( $id_articulos3 )>0 ) {
                            $id_articulos[$j] = $id_articulos3;
                            unset( $id_articulos3 );
                            $j++;
                            $obj2['articulos'] = $id_articulos;
                            unset( $id_articulos );

                            $id_subcategorias[$i] = $obj2;
							 unset( $id_articulos );
                            $i++;
							$k=0;
                        }
						/*if ( count( $id_articulos3 )>0 ) {
                            $id_articulos[$j] = $id_articulos3;
                            unset( $id_articulos3 );
                            $j++;
                            $obj2['articulos'] = $id_articulos;
                            unset( $id_articulos );
                            $id_subcategorias[$i] = $obj2;
                            unset( $art );
                            unset( $id_articulos3 );
							$k=0;
						}*/
                    }

                    $obj['subcategoria'] = $id_subcategorias;
                    unset( $id_subcategorias );
					$j=0;
					$i=0;
                }

                // if size subcategorias
                else {
                    $art = array();
                    $art = $this->Articulo_model->articulo_sin_subcategorias( $fila->id_categoria, $id_sucursal );
                    $id_articulos3 = array();
                    foreach ( $art as $fila3 ) {
                        //print_r( $fila3 );
                        $obj3['id_articulo'] =  $fila3->id_articulo;
                        $obj3['platillo'] =  $fila3->platillo;
                        $obj3['nombre_articulo'] =  $fila3->nombre_articulo;
                        $obj3['descripcion_articulo'] =  $fila3->descripcion_articulo;
                        $obj3['simbologia'] =  $fila3->simbologia;
                        $obj3['logo'] =  $fila3->logo;
                        // $obj3['id_experiencia'] =  $fila3->id_experiencia;
                        // $obj3['nombre_experiencia'] =  $fila3->nombre_experiencia;
                        // $obj3['descripcion_experiencia'] =  $fila3->descripcion_experiencia;
                        // $obj3['ubicacion_experiencia'] =  $fila3->ubicacion_experiencia;
						$obj3['ubicacion_articulo'] =   $fila3->ubicacion_articulo;
						$obj3['ubicacion_articulo_min'] =   $fila3->ubicacion_articulo_min;
                        $obj3['precio'] =  $fila3->precio;
                        $obj3['cantidad_x_porcion'] = $fila3->cantidad_x_porcion;
                        $obj3['descripcion_imagen'] = $fila3->descripcion_imagen;
                        $obj3['orden'] = $fila3->orden;
                        $obj3['detalle_imagen'] = $fila3->detalle_imagen;
                        $medida = array();
                        $medida = $this->Articulo_model->get_medidas_precio( $fila3->id_articulo, $fila3->precio, $id_sucursal, 0, $fila3->nombre_articulo );
                        $experiencias = $this->Articulo_model->get_experiencias( $fila3->id_experiencia );
                        $obj3['medidas'] = $medida;
                        $obj3['experiencias'] = $experiencias;
                        $id_articulos3[$k] = $obj3;
                        $k++;
                        unset( $medida );
                    }
                    unset( $art );
                    if ( count( $id_articulos3 )>0 ) {
                        $id_articulos[$j] = $id_articulos3;
                        $obj['articulos'] = $id_articulos3;
                        unset( $id_articulos3 );
                        $j++;

                        //unset( $id_articulos );

                        //$id_subcategorias[$i] = $obj;
                        //$i++;
                    }
                    //$obj['articulos'] = $obj;
                }
				$categorias_arreglo[$l]= $obj;
				$l++;
            }			
			$datos_registros ['hora_peticion'] = date("H:i:s");
			//$res2 = $this->Registros_model->add( $datos_registros );
            //$response['categoria'] = $obj;
            $response['categoria'] = $categorias_arreglo;
            $response['id_sucursal'] = $id_sucursal;
            $response['region'] = $sucursalResult[0]->id_region;
            

            echo json_encode( $response );
        }

    }

    function updateBeerFactoryProducts(){
        $beer_products = $this->db->query("SELECT * FROM precios_nuevos_beerfactory where nuevo_precio != 0")->result();
        foreach ($beer_products as $key => $value) {
            $this->db->query("UPDATE articulo SET precio_nacional = " . $value->nuevo_precio ." WHERE id = " . $value->id_producto);
            $this->db->query("UPDATE articulo SET precio_acapulco = " . $value->nuevo_precio ." WHERE id = " . $value->id_producto);
            $this->db->query("UPDATE articulo SET precio_cc = " . $value->nuevo_precio ." WHERE id = " . $value->id_producto);
            $this->db->query("UPDATE articulo SET precio_tijuana = " . $value->nuevo_precio ." WHERE id = " . $value->id_producto);
            $this->db->query("UPDATE articulo SET precio_pl = " . $value->nuevo_precio ." WHERE id = " . $value->id_producto);
            $this->db->query("UPDATE articulo SET precio_aeropuerto = " . $value->nuevo_precio ." WHERE id = " . $value->id_producto);
            $this->db->query("UPDATE articulo SET precio_tezontle = " . $value->nuevo_precio ." WHERE id = " . $value->id_producto);
        }

        $response['response'] = 'Hecho!';

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

	function articulos_sucursal_region() {

        $region = $_GET['region'];
        $kilos = $_GET['kilos'];
        $individual = $_GET['individual'];
        $alcohol = $_GET['alcohol'];
		if($region==6){
			$individual=1;
		}
        $id_marca = $_GET['marca'];
        $id_sucursal = $this->Articulo_model->sucursal_region($id_marca, $region, $kilos, $individual, $alcohol );
        $token = $_GET['token'];
        $hora_solicitud = $_GET['hora_solicitud'];
        $fecha_solicitud = $_GET['fecha_solicitud'];
		/********************************registro*/
			date_default_timezone_set('America/Mexico_City');
			$datos_registros = array();
			$datos_registros ['hora_solicitud'] = $hora_solicitud;
			$datos_registros ['id_categoria'] = 0;
			$datos_registros ['id_sucursal'] = $id_sucursal;
			$datos_registros ['fecha'] = date("Y-m-d");
			$datos_registros ['fecha_solicitud'] = $fecha_solicitud;
			$datos_registros ['ip'] = $_SERVER['REMOTE_ADDR'];
			
			/********************************registro*/
        $response = array();
        //verificar token
        $res = $this->login_model->buscar_token( $token );
		
        if ( count( $res ) == 0 ) {
            $response['status'] = 'Error';
            $response['respuesta'] = 'Error al ingresar';

        } else {

            $response['status'] = 'OK';
            $response['respuesta'] = 'Ingreso correcto';
            $categorias_arreglo = array();
            $categorias = array();
            $categorias = $this->Categoria_model->get_activas_sucursal( $id_sucursal, $id_marca,$kilos);
            $i = 0;$j = 0;$k = 0;$l=0;
            $agregar = 0;
            
            $id_subcategorias = array();
            foreach ( $categorias as $fila ) {
                $obj = array();
                $obj ['categoria'] = $fila->nombre_categoria;
                $obj ['descripcion'] = $fila->descripcion_categoria;
                $obj ['id_categoria'] = $fila->id_categoria;
                $obj ['ubicacion'] = $fila->ubicacion_categoria;
                $obj ['imagen_titulo_categoria'] = $fila->imagen_titulo_categoria;
                $obj ['imagen_fondo'] = $fila->imagen_fondo;
                $obj2 = array();
				$subcategorias = $this->Articulo_model->categoria_subcategorias( $fila->id_categoria, $id_sucursal );
                if ( !is_null( $subcategorias ) ) {
                    foreach ( $subcategorias as $fila2 ) {
                        $obj2['id_subcategoria'] = $fila2->id_subcategoria;
                        $obj2['subcategoria'] = $fila2->nombre_subcategoria;
                        $obj2['descripcion'] = $fila2->descripcion_subcategoria;
                        $obj2['destacado'] = $fila2->destacado;
                        $obj2['color_pleca'] = $fila2->color_pleca;
                        $obj3 = array();

                        $id_articulos = array();
                        $id_subcategorias_ = $fila2->id_subcategoria;
                        $art = array();
                        $art = $this->Articulo_model->articulo_subcategorias2( $id_subcategorias_, $id_sucursal );
                        $id_articulos3 = array();
                        foreach ( $art as $fila3 ) {
                            //print_r( $fila3 );
                            $obj3['id_articulo'] =  $fila3->id_articulo;
                            $obj3['platillo'] =  $fila3->platillo;
                            $obj3['nombre_articulo'] =  $fila3->nombre_articulo;
                            $obj3['descripcion_articulo'] =  $fila3->descripcion_articulo;
                            $obj3['simbologia'] =  $fila3->simbologia;
                            $obj3['logo'] =  $fila3->logo;
                            $obj3['id_experiencia'] =  $fila3->id_experiencia;
                            $obj3['nombre_experiencia'] =  $fila3->nombre_experiencia;
                            $obj3['descripcion_experiencia'] =  $fila3->descripcion_experiencia;
                            $obj3['ubicacion_experiencia'] =  $fila3->ubicacion_experiencia;
							$obj3['ubicacion_articulo'] =   $fila3->ubicacion_articulo;
							$obj3['ubicacion_articulo_min'] =   $fila3->ubicacion_articulo_min;
                            $obj3['precio'] =  $fila3->precio;
                            $obj3['cantidad_x_porcion'] = $fila3->cantidad_x_porcion;
                            $obj3['descripcion_imagen'] = $fila3->descripcion_imagen;
                            $obj3['orden'] = $fila3->orden;
                            $obj3['detalle_imagen'] = $fila3->detalle_imagen;
                            $obj3['etiqueta_extra'] = $fila3->etiqueta_extra;
                            $obj3['numero_opciones'] = $fila3->numero_opciones;
                            $obj3['vigencia'] = $fila3->vigencia;
                            $obj3['fechainicio'] = $fila3->fechainicio;
                            $obj3['fechafin'] = $fila3->fechafin;
							
                            $medida = array();
                            $medida = $this->Articulo_model->get_medidas_precio( $fila3->id_articulo, $fila3->precio, $id_sucursal, $id_subcategorias_, $fila3->nombre_articulo );
                            $experiencias = $this->Articulo_model->get_experiencias( $fila3->id_experiencia );
                            $extras = $this->Articulo_model->get_extras( $fila3->extra,$id_sucursal );
                            if ( !is_null( $medida ) ) {
                                $x = 0;
                                foreach ( $medida as $fila_medida ) {
                                    if ( $x == 0 ) {
                                        $obj3['precio'] =  $fila_medida->precio;
                                        break;
                                    }
                                }
                            }
                            $obj3['medidas'] = $medida;
                            $obj3['experiencias'] = $experiencias;
                            $obj3['extras'] = $extras;
                            $id_articulos3[$k] = $obj3;
                            $k++;
                            unset( $medida );
                        }
                        //unset( $art );
                        if ( count( $id_articulos3 )>0 ) {
                            $id_articulos[$j] = $id_articulos3;
                            unset( $id_articulos3 );
                            $j++;
                            $obj2['articulos'] = $id_articulos;
                            unset( $id_articulos );

                            $id_subcategorias[$i] = $obj2;
							 unset( $id_articulos );
                            $i++;
							$k=0;
                        }
                    }

                    $obj['subcategoria'] = $id_subcategorias;
                    unset( $id_subcategorias );
					$j=0;
					$i=0;
                }

                // if size subcategorias
                else {
                    $art = array();
                    $art = $this->Articulo_model->articulo_sin_subcategorias( $fila->id_categoria, $id_sucursal );
                    $id_articulos3 = array();
                    foreach ( $art as $fila3 ) {
                        //print_r( $fila3 );
                        $obj3['id_articulo'] =  $fila3->id_articulo;
                        $obj3['platillo'] =  $fila3->platillo;
                        $obj3['nombre_articulo'] =  $fila3->nombre_articulo;
                        $obj3['descripcion_articulo'] =  $fila3->descripcion_articulo;
                        $obj3['simbologia'] =  $fila3->simbologia;
                        $obj3['logo'] =  $fila3->logo;
                        // $obj3['id_experiencia'] =  $fila3->id_experiencia;
                        // $obj3['nombre_experiencia'] =  $fila3->nombre_experiencia;
                        // $obj3['descripcion_experiencia'] =  $fila3->descripcion_experiencia;
                        // $obj3['ubicacion_experiencia'] =  $fila3->ubicacion_experiencia;
						$obj3['ubicacion_articulo'] =   $fila3->ubicacion_articulo;
						$obj3['ubicacion_articulo_min'] =   $fila3->ubicacion_articulo_min;
                        $obj3['precio'] =  $fila3->precio;
                        $obj3['cantidad_x_porcion'] = $fila3->cantidad_x_porcion;
                        $obj3['descripcion_imagen'] = $fila3->descripcion_imagen;
                        $obj3['orden'] = $fila3->orden;
                        $obj3['detalle_imagen'] = $fila3->detalle_imagen;
                        $medida = array();
                        $medida = $this->Articulo_model->get_medidas_precio( $fila3->id_articulo, $fila3->precio, $id_sucursal, 0, $fila3->nombre_articulo );
                        $experiencias = $this->Articulo_model->get_experiencias( $fila3->id_experiencia );
                        $obj3['medidas'] = $medida;
                        $obj3['experiencias'] = $experiencias;
                        $id_articulos3[$k] = $obj3;
                        $k++;
                        unset( $medida );
                    }
                    unset( $art );
                    if ( count( $id_articulos3 )>0 ) {
                        $id_articulos[$j] = $id_articulos3;
                        $obj['articulos'] = $id_articulos3;
                        unset( $id_articulos3 );
                        $j++;
                    }
                }
				$categorias_arreglo[$l]= $obj;
				$l++;
            }			
			$datos_registros ['hora_peticion'] = date("H:i:s");
			//$res2 = $this->Registros_model->add( $datos_registros );
            $response['categoria'] = $categorias_arreglo;
            $response['id_sucursal'] = $id_sucursal;
            echo json_encode( $response );
        }

    }

	function articulos_idioma() {

        $region = $_GET['region'];
        $kilos = $_GET['kilos'];
         $individual = $_GET['individual'];
        if($region == 5)
			$individual = 0;
        $alcohol = $_GET['alcohol'];
        $id_idioma = $_GET['id_idioma'];
		if($region==6){
			$individual=1;
		}
        $id_marca = $_GET['marca'];
        $id_sucursal = $this->Articulo_model->sucursal_region($id_marca, $region, $kilos, $individual, $alcohol );
        $token = $_GET['token'];
        $hora_solicitud = $_GET['hora_solicitud'];
        $fecha_solicitud = $_GET['fecha_solicitud'];
		/********************************registro*/
			date_default_timezone_set('America/Mexico_City');
			$datos_registros = array();
			$datos_registros ['hora_solicitud'] = $hora_solicitud;
			$datos_registros ['id_categoria'] = 0;
			$datos_registros ['id_sucursal'] = $id_sucursal;
			$datos_registros ['fecha'] = date("Y-m-d");
			$datos_registros ['fecha_solicitud'] = $fecha_solicitud;
			$datos_registros ['ip'] = $_SERVER['REMOTE_ADDR'];
			
			/********************************registro*/
        $response = array();
        //verificar token
        $res = $this->login_model->buscar_token( $token );
		$cadena ="";
        if ( count( $res ) == 0 ) {
            $response['status'] = 'Error';
            $response['respuesta'] = 'Error al ingresar';

        } else {
            $response['status'] = 'OK';
            $response['respuesta'] = 'Ingreso correcto';
            $categorias_arreglo = array();
            $categorias = array();
			if($id_idioma > 0)
				$categorias = $this->Categoria_model->get_activas_sucursal_idioma( $id_sucursal, $id_marca,$id_idioma,$kilos );//idioma
            else
				$categorias = $this->Categoria_model->get_activas_sucursal( $id_sucursal, $id_marca,$kilos);
            
			$i = 0;$j = 0;$k = 0;$l=0; //$guardar =0;
            //$agregar = 0;
            
            $id_subcategorias = array();
            $art = array();
            foreach ( $categorias as $fila ) {
                $obj = array();
                $obj ['categoria'] = $fila->nombre_categoria;
                $obj ['descripcion'] = $fila->descripcion_categoria;
                $obj ['id_categoria'] = $fila->id_categoria;
                $obj ['ubicacion'] = $fila->ubicacion_categoria;
                $obj ['imagen_titulo_categoria'] = $fila->imagen_titulo_categoria;
                $obj ['imagen_fondo'] = $fila->imagen_fondo;
                $obj2 = array();
                $subcategorias = array();
			//$cadena = $fila->id_categoria.' '. $id_sucursal.' '.$id_idioma;
				if($id_idioma > 0)
					$subcategorias = $this->Articulo_model->categoria_subcategorias_idioma( $fila->id_categoria, $id_sucursal,$id_idioma  );//idioma
                //else
					//$subcategorias = $this->Articulo_model->categoria_subcategorias( $fila->id_categoria, $id_sucursal );
                    
                    if ( !is_null( $subcategorias ) ) {
                    foreach ( $subcategorias as $fila2 ) {
                        $obj2['id_subcategoria'] = $fila2->id_subcategoria;
                        $obj2['subcategoria'] = $fila2->nombre_subcategoria;
                        $obj2['descripcion'] = $fila2->descripcion_subcategoria;
                        $obj2['destacado'] = $fila2->destacado;
                        $obj2['color_pleca'] = $fila2->color_pleca;
                        $obj2['orden'] = $fila2->orden;
                        $obj3 = array();

                        $id_articulos = array();
                        $id_subcategorias_ = $fila2->id_subcategoria;
					//$cadena =  $id_subcategorias_.' '. $id_sucursal. ' '.$id_idioma;
						if($id_idioma > 0)
							$art = $this->Articulo_model->articulo_subcategorias2_idioma( $id_subcategorias_, $id_sucursal,$id_idioma );//idioma
                        else
							$art = $this->Articulo_model->articulo_subcategorias2( $id_subcategorias_, $id_sucursal );//
						$id_articulos3 = array();
                        foreach ( $art as $fila3 ) {
                            //print_r( $fila3 );
                            $obj3['id_articulo'] =  $fila3->id_articulo;
                            $obj3['platillo'] =  $fila3->platillo;
                            $obj3['nombre_articulo'] =  $fila3->nombre_articulo;
                            $obj3['descripcion_articulo'] =  $fila3->descripcion_articulo;
                            $obj3['simbologia'] =  $fila3->simbologia;
                            $obj3['logo'] =  $fila3->logo;
                            $obj3['id_experiencia'] =  $fila3->id_experiencia;
                            $obj3['nombre_experiencia'] =  $fila3->nombre_experiencia;
                            $obj3['descripcion_experiencia'] =  $fila3->descripcion_experiencia;
                            $obj3['ubicacion_experiencia'] =  $fila3->ubicacion_experiencia;
							$obj3['ubicacion_articulo'] =   $fila3->ubicacion_articulo;
							$obj3['ubicacion_articulo_min'] =   $fila3->ubicacion_articulo_min;
                            $obj3['precio'] =  $fila3->precio;
                            $obj3['cantidad_x_porcion'] = $fila3->cantidad_x_porcion;
                            $obj3['descripcion_imagen'] = $fila3->descripcion_imagen;
                            $obj3['orden'] = $fila3->orden;
                            $obj3['detalle_imagen'] = $fila3->detalle_imagen;
                            $obj3['etiqueta_extra'] = $fila3->etiqueta_extra;
                            $obj3['numero_opciones'] = $fila3->numero_opciones;
                            $obj3['vigencia'] = $fila3->vigencia;
                            $obj3['fechainicio'] = $fila3->fechainicio;
                            $obj3['fechafin'] = $fila3->fechafin;
							
                            $medida = array();
                            $experiencias = array();
                            $extras = array();
							if($id_idioma > 0){

								$medida = $this->Articulo_model->get_medidas_precio_idioma( $fila3->id_articulo, $fila3->precio, $id_sucursal, $id_subcategorias_, $fila3->nombre_articulo,$id_idioma );
								//$experiencias = $this->Articulo_model->get_experiencias_idioma( $fila3->id_experiencia,$id_idioma  );
								$experiencias = $this->Articulo_model->get_experiencias( $fila3->id_experiencia );
								$extras = $this->Articulo_model->get_extras_idioma( $fila3->extra,$id_sucursal,$id_idioma );
							}
							else{
								
								$medida = $this->Articulo_model->get_medidas_precio( $fila3->id_articulo, $fila3->precio, $id_sucursal, $id_subcategorias_, $fila3->nombre_articulo );
								$experiencias = $this->Articulo_model->get_experiencias( $fila3->id_experiencia );
								$extras = $this->Articulo_model->get_extras( $fila3->extra,$id_sucursal );
							}
                            if ( !is_null( $medida ) ) {
                                $x = 0;
                                foreach ( $medida as $fila_medida ) {
                                    if ( $x == 0 ) {
                                        $obj3['precio'] =  $fila_medida->precio;
                                        break;
                                    }
                                }
                            }
                            $obj3['medidas'] = $medida;
                            $obj3['experiencias'] = $experiencias;
                            $obj3['extras'] = $extras;
                            $id_articulos3[$k] = $obj3;
                            $k++;
                            unset( $medida );
                        }
                        //unset( $art );
                        if ( count( $id_articulos3 )>0 ) {
                            $id_articulos[$j] = $id_articulos3;
                            unset( $id_articulos3 );
                            $j++;
                            $obj2['articulos'] = $id_articulos;
                            unset( $id_articulos );

                            $id_subcategorias[$i] = $obj2;
							 unset( $id_articulos );
                            $i++;
							$k=0;
							//$guardar ++;
                        }
                    }
					//if($i >0)
					$obj['subcategoria'] = $id_subcategorias;
                    unset( $id_subcategorias );
					$j=0;
					$i=0;
                }

                // if size subcategorias
                /*else {
                    $art = array();
                    $art = $this->Articulo_model->articulo_sin_subcategorias( $fila->id_categoria, $id_sucursal );
                    $id_articulos3 = array();
                    foreach ( $art as $fila3 ) {
                        //print_r( $fila3 );
                        $obj3['id_articulo'] =  $fila3->id_articulo;
                        $obj3['platillo'] =  $fila3->platillo;
                        $obj3['nombre_articulo'] =  $fila3->nombre_articulo;
                        $obj3['descripcion_articulo'] =  $fila3->descripcion_articulo;
                        $obj3['simbologia'] =  $fila3->simbologia;
                        $obj3['logo'] =  $fila3->logo;
                        // $obj3['id_experiencia'] =  $fila3->id_experiencia;
                        // $obj3['nombre_experiencia'] =  $fila3->nombre_experiencia;
                        // $obj3['descripcion_experiencia'] =  $fila3->descripcion_experiencia;
                        // $obj3['ubicacion_experiencia'] =  $fila3->ubicacion_experiencia;
						$obj3['ubicacion_articulo'] =   $fila3->ubicacion_articulo;
						$obj3['ubicacion_articulo_min'] =   $fila3->ubicacion_articulo_min;
                        $obj3['precio'] =  $fila3->precio;
                        $obj3['cantidad_x_porcion'] = $fila3->cantidad_x_porcion;
                        $obj3['descripcion_imagen'] = $fila3->descripcion_imagen;
                        $obj3['orden'] = $fila3->orden;
                        $obj3['detalle_imagen'] = $fila3->detalle_imagen;
                        $medida = array();
                        $medida = $this->Articulo_model->get_medidas_precio( $fila3->id_articulo, $fila3->precio, $id_sucursal, 0, $fila3->nombre_articulo );
                        $experiencias = $this->Articulo_model->get_experiencias( $fila3->id_experiencia );
                        $obj3['medidas'] = $medida;
                        $obj3['experiencias'] = $experiencias;
                        $id_articulos3[$k] = $obj3;
                        $k++;
                        unset( $medida );
                    }
                    unset( $art );
                    if ( count( $id_articulos3 )>0 ) {
                        $id_articulos[$j] = $id_articulos3;
                        $obj['articulos'] = $id_articulos3;
                        unset( $id_articulos3 );
                        $j++;
							$guardar ++;
                    }
                }*/

                // if size subcategorias
                else {
                    $art = array();
                    if($id_idioma > 0) $art = $this->Articulo_model->articulo_sin_subcategorias_traduccion( $fila->id_categoria, $id_sucursal );

                    if($id_idioma == 0) $art = $this->Articulo_model->articulo_sin_subcategorias( $fila->id_categoria, $id_sucursal );

                    $id_articulos3 = array();
                    foreach ( $art as $fila3 ) {
                        //print_r( $fila3 );
                        $obj3['id_articulo'] =  $fila3->id_articulo;
                        $obj3['platillo'] =  $fila3->platillo;
                        $obj3['nombre_articulo'] =  $fila3->nombre_articulo;
                        $obj3['descripcion_articulo'] =  $fila3->descripcion_articulo;
                        $obj3['simbologia'] =  $fila3->simbologia;
                        $obj3['logo'] =  $fila3->logo;
                        // $obj3['id_experiencia'] =  $fila3->id_experiencia;
                        // $obj3['nombre_experiencia'] =  $fila3->nombre_experiencia;
                        // $obj3['descripcion_experiencia'] =  $fila3->descripcion_experiencia;
                        // $obj3['ubicacion_experiencia'] =  $fila3->ubicacion_experiencia;
						$obj3['ubicacion_articulo'] =   $fila3->ubicacion_articulo;
						$obj3['ubicacion_articulo_min'] =   $fila3->ubicacion_articulo_min;
                        $obj3['precio'] =  $fila3->precio;
                        $obj3['cantidad_x_porcion'] = $fila3->cantidad_x_porcion;
                        $obj3['descripcion_imagen'] = $fila3->descripcion_imagen;
                        $obj3['orden'] = $fila3->orden;
                        $obj3['detalle_imagen'] = $fila3->detalle_imagen;
                        
                        $medida = array();
                            $experiencias = array();
                            $extras = array();
                            if($id_idioma > 0){

								$medida = $this->Articulo_model->get_medidas_precio_idioma( $fila3->id_articulo, $fila3->precio, $id_sucursal, $id_subcategorias_, $fila3->nombre_articulo,$id_idioma );
								//$experiencias = $this->Articulo_model->get_experiencias_idioma( $fila3->id_experiencia,$id_idioma  );
								$experiencias = $this->Articulo_model->get_experiencias( $fila3->id_experiencia );
							}else{
                                $medida = $this->Articulo_model->get_medidas_precio( $fila3->id_articulo, $fila3->precio, $id_sucursal, 0, $fila3->nombre_articulo );
                                $experiencias = $this->Articulo_model->get_experiencias( $fila3->id_experiencia );
                            }
                        $obj3['medidas'] = $medida;
                        $obj3['experiencias'] = $experiencias;
                        $id_articulos3[$k] = $obj3;
                        $k++;
                        unset( $medida );
                    }
                    unset( $art );
                    if ( count( $id_articulos3 )>0 ) {
                        $id_articulos[$j] = $id_articulos3;
                        $obj['articulos'] = $id_articulos3;
                        unset( $id_articulos3 );
                        $j++;
                        $i++;
                        $k=0;
                        //unset( $id_articulos );

                        //$id_subcategorias[$i] = $obj;
                        //$i++;
                    }
                    //$obj['articulos'] = $obj;
                    $j=0;
					$i=0;
                }
				/*if(	$guardar > 0)
				{
					$categorias_arreglo[$l]= $obj;
					$l++;
				}
				
							$guardar =0;*/
                $categorias_arreglo[$l]= $obj;
				$l++;
            }			
			$datos_registros ['hora_peticion'] = date("H:i:s");
			//$res2 = $this->Registros_model->add( $datos_registros );
            $response['categoria'] = $categorias_arreglo;
            $response['id_sucursal'] = $id_sucursal;
            //$response['prueba'] = $cadena;
            //$response['prueba2'] = $subcategorias;
            echo json_encode( $response );
        }

    }
    function nombre() {

        $token = $_GET['token'];
        $nombre = $_GET['nombre'];
        $id_marca = $_GET['id_marca'];
        $response = array();
        $res = $this->login_model->buscar_token( $token );
        if ( count( $res ) == 0 ) {
            $response['status'] = 'Error';
            $response['respuesta'] = 'Error al ingresar';

        } else {
            $response['status'] = 'OK';
            $response['respuesta'] = 'Ingreso correcto';
            $res = $this->Articulo_model->articulo_nombre_sucursal( $nombre, $id_marca );
            $data = array(
                'id' => $res['id'],
                'platillo' => $res['platillo'],
                'id_categoria' => $res['id_categoria'],
                'id_subcategoria' => $res['id_subcategoria'],
                'id_experiencia' => $res['id_experiencia'],
                'descripcion' => $res['descripcion'],
                'logo' => $res['logo'],
                'simbologia' => $res['simbologia'],
                'ubicacion' => $res['ubicacion'],
                'precio_nacional' => $res['precio_nacional'],
                'precio_acapulco' => $res['precio_acapulco'],
                'precio_cc' => $res['precio_cc'],
                'precio_tijuana' => $res['precio_tijuana'],
                'precio_pl' => $res['precio_pl'],
                'activo' => $res['activo'],
                'cantidad_x_porcion'=>$res['cantidad_x_porcion'],
                'orden'=>$res['orden'],
                'medidas' => $this->Articulo_model->get_medidas_precio( $id )
            );

            $response['registro'] = $data;
        }
        echo json_encode( $response );

        //$this->load->view( 'update_Articulo', $response );

    }

    function redimensionarImagen( $origin, $destino, $newWidth, $newHeight, $jpgQuality = 100 ) {
        // getimagesize devuelve un array con: anchura, altura, tipo, cadena de
        // texto con el valor correcto height = 'yyy' width = 'xxx'
        $datos = getimagesize( $origin );

        // comprobamos que la imagen sea superior a los tamaños de la nueva imagen
        if ( $datos[0]>$newWidth || $datos[1]>$newHeight ) {

            // creamos una nueva imagen desde el original dependiendo del tipo
            if ( $datos[2] == 1 )
            $img = imagecreatefromgif ( $origin );
            if ( $datos[2] == 2 )
            $img = imagecreatefromjpeg( $origin );
            if ( $datos[2] == 3 )
            $img = imagecreatefrompng( $origin );

            // Redimensionamos proporcionalmente
            if ( rad2deg( atan( $datos[0]/$datos[1] ) )>rad2deg( atan( $newWidth/$newHeight ) ) ) {
                $anchura = $newWidth;
                $altura = round( ( $datos[1]*$newWidth )/$datos[0] );
            } else {
                $altura = $newHeight;
                $anchura = round( ( $datos[0]*$newHeight )/$datos[1] );
            }

            // creamos la imagen nueva
            $newImage = imagecreatetruecolor( $anchura, $altura );

            // redimensiona la imagen original copiandola en la imagen
            imagecopyresampled( $newImage, $img, 0, 0, 0, 0, $anchura, $altura, $datos[0], $datos[1] );

            // guardar la nueva imagen redimensionada donde indicia $destino
            if ( $datos[2] == 1 )
            imagegif ( $newImage, $destino );
            if ( $datos[2] == 2 )
            imagejpeg( $newImage, $destino, $jpgQuality );
            if ( $datos[2] == 3 )
            imagepng( $newImage, $destino );

            // eliminamos la imagen temporal
            imagedestroy( $newImage );

            return true;
        }
        return false;
    }

    function articulos_sucursal_traducion() {
        $id_sucursal = $_GET['sucursal'];
        $id_marca = $_GET['marca'];
        $token = $_GET['token'];
        $id_idioma = $_GET['id_idioma'];
		/*$id_idioma =0;
		if(isset($_GET['id_idioma'])
			$id_idioma = $_GET['id_idioma'];*/
		/********************************registro*/
			date_default_timezone_set('America/Mexico_City');
			$datos_registros = array();
			$datos_registros ['id_categoria'] = 0;
			$datos_registros ['id_sucursal'] = $id_sucursal;
			$datos_registros ['fecha'] = date("Y-m-d");
			$datos_registros ['ip'] = $_SERVER['REMOTE_ADDR'];
			
			/********************************registro*/
        $response = array();
        //verificar token
        $res = $this->login_model->buscar_token( $token );
		
        if ( count( $res ) == 0 ) {
            $response['status'] = 'Error';
            $response['respuesta'] = 'Error al ingresar';

        } else {

            $response['status'] = 'OK';
            $response['respuesta'] = 'Ingreso correcto';
            $categorias_arreglo = array();
            $categorias = array();
            //$categorias = $this->Categoria_model->get_activas_sucursal( $id_sucursal, $id_marca,0 );
            if($id_idioma > 0)
				$categorias = $this->Categoria_model->get_activas_sucursal_idioma( $id_sucursal, $id_marca,$id_idioma,0 );//idioma
            else
				$categorias = $this->Categoria_model->get_activas_sucursal( $id_sucursal, $id_marca,$kilos);
            $i = 0;$j = 0;$k = 0;$l=0;
            $agregar = 0;
            
            $id_subcategorias = array();
            $art = array();
            
            foreach ( $categorias as $fila ) {
                $obj = array();
                $obj ['categoria'] = $fila->nombre_categoria;
                $obj ['descripcion'] = $fila->descripcion_categoria;
                $obj ['id_categoria'] = $fila->id_categoria;
                $obj ['ubicacion'] = $fila->ubicacion_categoria;
                $obj ['imagen_titulo_categoria'] = $fila->imagen_titulo_categoria;
				$obj ['imagen_fondo'] = $fila->imagen_fondo;
                $obj2 = array();

                $subcategorias = array();
                if($id_idioma > 0){
                    $subcategorias = $this->Articulo_model->categoria_subcategorias_idioma( $fila->id_categoria, $id_sucursal,$id_idioma  );
                                       
                }
					
                //idioma
                
                    //else
					//$subcategorias = $this->Articulo_model->categoria_subcategorias( $fila->id_categoria, $id_sucursal );
				//$subcategorias = $this->Articulo_model->categoria_subcategorias( $fila->id_categoria, $id_sucursal );
                
                if ( !is_null( $subcategorias ) ) {
                    foreach ( $subcategorias as $fila2 ) {
                        $obj2['id_subcategoria'] = $fila2->id_subcategoria;
                        $obj2['subcategoria'] = $fila2->nombre_subcategoria;
                        $obj2['descripcion'] = $fila2->descripcion_subcategoria;
						$obj2['destacado'] = $fila2->destacado;
                        $obj2['color_pleca'] = $fila2->color_pleca;
                        $obj3 = array();

                        $id_articulos = array();
                        $id_subcategorias_ = $fila2->id_subcategoria;
                        $art = array();
                        
                        $id_subcategorias_ = $fila2->id_subcategoria;
                        if($id_idioma > 0)
							$art = $this->Articulo_model->articulo_subcategorias2_idioma( $id_subcategorias_, $id_sucursal,$id_idioma );//idioma
                        //else
						//	$art = $this->Articulo_model->articulo_subcategorias2( $id_subcategorias_, $id_sucursal );//
                           // echo "-->".$id_subcategorias_;
                        //$art = $this->Articulo_model->articulo_subcategorias2( $id_subcategorias_, $id_sucursal );
                        $id_articulos3 = array();
                        foreach ( $art as $fila3 ) {
                            //print_r( $fila3 );
                            $obj3['id_articulo'] =  $fila3->id_articulo;
                            $obj3['platillo'] =  $fila3->platillo;
                            $obj3['nombre_articulo'] =  $fila3->nombre_articulo;
                            $obj3['descripcion_articulo'] =  $fila3->descripcion_articulo;
                            $obj3['simbologia'] =  $fila3->simbologia;
                            $obj3['logo'] =  $fila3->logo;
                            $obj3['id_experiencia'] =  $fila3->id_experiencia;
                            $obj3['nombre_experiencia'] =  $fila3->nombre_experiencia;
                            $obj3['descripcion_experiencia'] =  $fila3->descripcion_experiencia;
                            $obj3['ubicacion_experiencia'] =  $fila3->ubicacion_experiencia;
							$obj3['ubicacion_articulo'] =   $fila3->ubicacion_articulo;
							$obj3['ubicacion_articulo_min'] =   $fila3->ubicacion_articulo_min;
                            $obj3['precio'] =  $fila3->precio;
                            $obj3['cantidad_x_porcion'] = $fila3->cantidad_x_porcion;
                            $obj3['descripcion_imagen'] = $fila3->descripcion_imagen;
                            $obj3['orden'] = $fila3->orden;
                            $obj3['detalle_imagen'] = $fila3->detalle_imagen;
                            $obj3['etiqueta_extra'] = $fila3->etiqueta_extra;
                            $obj3['numero_opciones'] = $fila3->numero_opciones;
                            $obj3['vigencia'] = $fila3->vigencia;
                            $obj3['fechainicio'] = $fila3->fechainicio;
                            $obj3['fechafin'] = $fila3->fechafin;

                            $medida = array();
                            $experiencias = array();
                            $extras = array();
                            if($id_idioma > 0){

								$medida = $this->Articulo_model->get_medidas_precio_idioma( $fila3->id_articulo, $fila3->precio, $id_sucursal, $id_subcategorias_, $fila3->nombre_articulo,$id_idioma );
								//$experiencias = $this->Articulo_model->get_experiencias_idioma( $fila3->id_experiencia,$id_idioma  );
								$experiencias = $this->Articulo_model->get_experiencias( $fila3->id_experiencia );
							}else{
                                $medida = $this->Articulo_model->get_medidas_precio( $fila3->id_articulo, $fila3->precio, $id_sucursal, $id_subcategorias_, $fila3->nombre_articulo );
                                $experiencias = $this->Articulo_model->get_experiencias( $fila3->id_experiencia );
                                $extras = $this->Articulo_model->get_extras( $fila3->extra,$id_sucursal );
                            }

                            
                            if ( !is_null( $medida ) ) {
                                $x = 0;
                                foreach ( $medida as $fila_medida ) {
                                    if ( $x == 0 ) {
                                        $obj3['precio'] =  $fila_medida->precio;
                                        break;
                                    }
                                }
                            }
                            $obj3['medidas'] = $medida;
                            $obj3['experiencias'] = $experiencias;
                            $obj3['extras'] = $extras;
                            $id_articulos3[$k] = $obj3;
                            $k++;
                            unset( $medida );
                        }
                        //unset( $art );
                        if ( count( $id_articulos3 )>0 ) {
                            $id_articulos[$j] = $id_articulos3;
                            unset( $id_articulos3 );
                            $j++;
                            $obj2['articulos'] = $id_articulos;
                            unset( $id_articulos );

                            $id_subcategorias[$i] = $obj2;
							 unset( $id_articulos );
                            $i++;
							$k=0;
                        }

                        
						/*if ( count( $id_articulos3 )>0 ) {
                            $id_articulos[$j] = $id_articulos3;
                            unset( $id_articulos3 );
                            $j++;
                            $obj2['articulos'] = $id_articulos;
                            unset( $id_articulos );
                            $id_subcategorias[$i] = $obj2;
                            unset( $art );
                            unset( $id_articulos3 );
							$k=0;
						}*/
                    }

                    $obj['subcategoria'] = $id_subcategorias;
                    unset( $id_subcategorias );
					$j=0;
					$i=0;
                }

                // if size subcategorias
                else {
                    $art = array();
                    if($id_idioma > 0) $art = $this->Articulo_model->articulo_sin_subcategorias_traduccion( $fila->id_categoria, $id_sucursal );

                    if($id_idioma == 0) $art = $this->Articulo_model->articulo_sin_subcategorias( $fila->id_categoria, $id_sucursal );

                    $id_articulos3 = array();
                    foreach ( $art as $fila3 ) {
                        //print_r( $fila3 );
                        $obj3['id_articulo'] =  $fila3->id_articulo;
                        $obj3['platillo'] =  $fila3->platillo;
                        $obj3['nombre_articulo'] =  $fila3->nombre_articulo;
                        $obj3['descripcion_articulo'] =  $fila3->descripcion_articulo;
                        $obj3['simbologia'] =  $fila3->simbologia;
                        $obj3['logo'] =  $fila3->logo;
                        // $obj3['id_experiencia'] =  $fila3->id_experiencia;
                        // $obj3['nombre_experiencia'] =  $fila3->nombre_experiencia;
                        // $obj3['descripcion_experiencia'] =  $fila3->descripcion_experiencia;
                        // $obj3['ubicacion_experiencia'] =  $fila3->ubicacion_experiencia;
						$obj3['ubicacion_articulo'] =   $fila3->ubicacion_articulo;
						$obj3['ubicacion_articulo_min'] =   $fila3->ubicacion_articulo_min;
                        $obj3['precio'] =  $fila3->precio;
                        $obj3['cantidad_x_porcion'] = $fila3->cantidad_x_porcion;
                        $obj3['descripcion_imagen'] = $fila3->descripcion_imagen;
                        $obj3['orden'] = $fila3->orden;
                        $obj3['detalle_imagen'] = $fila3->detalle_imagen;
                        
                        $medida = array();
                            $experiencias = array();
                            $extras = array();
                            if($id_idioma > 0){

								$medida = $this->Articulo_model->get_medidas_precio_idioma( $fila3->id_articulo, $fila3->precio, $id_sucursal, $id_subcategorias_, $fila3->nombre_articulo,$id_idioma );
								//$experiencias = $this->Articulo_model->get_experiencias_idioma( $fila3->id_experiencia,$id_idioma  );
								$experiencias = $this->Articulo_model->get_experiencias( $fila3->id_experiencia );
							}else{
                                $medida = $this->Articulo_model->get_medidas_precio( $fila3->id_articulo, $fila3->precio, $id_sucursal, 0, $fila3->nombre_articulo );
                                $experiencias = $this->Articulo_model->get_experiencias( $fila3->id_experiencia );
                            }
                        $obj3['medidas'] = $medida;
                        $obj3['experiencias'] = $experiencias;
                        $id_articulos3[$k] = $obj3;
                        $k++;
                        unset( $medida );
                    }
                    unset( $art );
                    if ( count( $id_articulos3 )>0 ) {
                        $id_articulos[$j] = $id_articulos3;
                        $obj['articulos'] = $id_articulos3;
                        unset( $id_articulos3 );
                        $j++;
                        $i++;
                        $k=0;
                        //unset( $id_articulos );

                        //$id_subcategorias[$i] = $obj;
                        //$i++;
                    }
                    //$obj['articulos'] = $obj;
                    $j=0;
					$i=0;
                }
				$categorias_arreglo[$l]= $obj;
				$l++;
            }			
			$datos_registros ['hora_peticion'] = date("H:i:s");
			//$res2 = $this->Registros_model->add( $datos_registros );
            //$response['categoria'] = $obj;
            $response['categoria'] = $categorias_arreglo;
            $response['id_sucursal'] = $id_sucursal;
            
            echo json_encode( $response );
        }

    }
}