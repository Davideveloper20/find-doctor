<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
if ( ! function_exists('today')){
    
    function today($format = "datetime")
    {
        switch ($format) {
            case 'date':
                $date = date("Y-m-d");
                break;
            case 'hour':
                $date = date("H:i:s");
                break;
            
            default:
                $date = date("Y-m-d H:i:s");
                break;
        }
        return $date;
    }
} 
if ( ! function_exists('id_comprador')){
    
    function id_comprador($idorder)
    {
         // Get a reference to the controller object
        $CI = get_instance();

        // Call a function of the model
        $orden = $CI->ss->getCampos(TABLE_PREFIX."orders", ["id_orders"=>$idorder]);

        return $orden[0]['id_user'];
    }
}
if ( ! function_exists('datos_comprador')){
    
    function datos_comprador($token)
    {
         // Get a reference to the controller object
        $CI = get_instance();

        // Call a function of the model
        $orden = $CI->ss->getCamposAndJoin(TABLE_PREFIX."orders t1", ["order_token"=>$token],[USERS_TABLE." u"], ["u.id_users=t1.id_user"], "u.email,u.id_users,u.fullname");

        if( count($orden) > 0 )
        {
            return $orden[0];
        }

        return false;
    }
}
if ( ! function_exists('datos_usuario')){
    
    function datos_usuario($id)
    {
         // Get a reference to the controller object
        $CI = get_instance();

        // Call a function of the model
        $orden = $CI->ss->getCampos(USERS_TABLE." u", ["u.id_users"=>$id], "u.email,u.id_users,u.fullname");

        if( count($orden) > 0 )
        {
            return $orden[0];
        }

        return false;
    }
}
if ( ! function_exists('status_order')){
    
    function status_order($status_order, $icon = FALSE)
    {
        switch ($status_order) {
            case 1:
                $status = $icon?"fas fa-user-clock":"Pendiente de pago";
                break;
            case 2:
                $status = $icon?"fas fa-file-invoice-dollar":"Generada";
                break;
            case 3:
                $status = $icon?"far fa-clock":"En Preparación";
                break;
            case 4:
                $status = $icon?"fas fa-stopwatch":"Esperando domiciliario";
                break;
            case 5:
                $status = $icon?"fas fa-running":"Domiciliario en Camino";
                break;
            case 6:
                $status = $icon?"fas fa-motorcycle":"Recogida";
                break;
            case 7:
                $status = $icon?"far fa-check-circle":"Entregada";
                break;
            case 10:
                $status = $icon?"far fa-times-circle":"Cancelada";
                break;
            
            default:
                $status = "En espera";
                break;
        }

        return $status;
    }
}
if ( ! function_exists('retrieve_date_filter')){
    
    function retrieve_date_filter($filter, $field = "order_date")
    {
        switch ($filter) {
            case 1:
                $date = date('Y-m-d 00:00:00', strtotime('-1 days', strtotime(date('Y-m-d 00:00:00'))));
                $search = $field." > '".$date."'";
                break;
            case 2:
                $less_one = date('Y-m-d', strtotime('-1 days', strtotime(date('Y-m-d'))));
                $less_two = date('Y-m-d', strtotime('-2 days', strtotime(date('Y-m-d'))));
                $search = $field." BETWEEN '".$less_two."' and '".$less_one."'";
                break;
            case 3:
                $date = date('Y-m').'-01';
                $search = $field." > '".$date."'";
                break;
            case 4:
                $actual_month = date('Y-m', strtotime('-1 month', strtotime(date('Y-m')))).'-31';
                $last_month = date('Y-m', strtotime('-1 month', strtotime(date('Y-m')))).'-01';
                $search = $field." BETWEEN '".$last_month."' and '".$actual_month."'";
                break;
            default:
                $search = $field." LIKE '%".date('Y-m-d')."'";
                break;
        }

        return $search;
    }
}
if ( ! function_exists('retrieve_pay_request_status')){
    
    function retrieve_pay_request_status($status_id)
    {
        switch ($status_id) {
            case 0:
                $status = "Pendiente de Aprobación";
                break;
            case 1:
                $status = "Aprobado";
                break;
            case 2:
                $status = "Consignado en cuenta";
                break;
            case 3:
                $status = "Negado";
                break;
            default:
                $status = "Sin especificar";
                break;
        }

        return $status;
    }
}
if ( ! function_exists('retrieve_pay_request_action')){
    
    function retrieve_pay_request_action($status_id, $id)
    {
        switch ($status_id) {
            case 0:
                $status = '<button class="btn btn-primary btn-sm" data-action="approve" data-id="'.$id.'">Aprobar</button>';
                $status .= ' <button class="btn btn-secondary btn-sm" data-action="reject" data-id="'.$id.'">Rechazar</button>';
                break;
            case 1:
                $status = "";
                break;
            case 2:
                $status = "";
                break;
            case 3:
                $status = "";
                break;
            default:
                $status = "Sin especificar";
                break;
        }

        return $status;
    }
}
if ( ! function_exists('retrieve_status_filter')){
    
    function retrieve_status_filter($filter)
    {
        return "t1.statuscode = ".$filter;
    }
}
if ( ! function_exists('order_delivery_status')){
    
    function order_delivery_status($status_id)
    {
        switch ($status_id) {
            case 1:
                $status = "Camino al comercio";
                break;
            case 2:
                $status = "En camino a la dirección del cliente";
                break;
            case 3:
                $status = "Entregada";
                break;
            default:
                $status = "Sin especificar";
                break;
        }

        return $status;
    }
}
if ( ! function_exists('retrieve_link_shipping_track')){
    
    function retrieve_link_shipping_track($id_provider,$guide_number)
    {
        switch ($id_provider) {
            case 1:
                $link = "Pendiente de pago";
                break;
            case 2:
                $link = "Generada";
                break;
            case 3:
                $link = "Enviada";
                break;
            default:
                $link = $guide_number;
                break;
        }

        return $link;
    }
}
if ( ! function_exists('retrieve_list_categories')){
    
    function retrieve_list_categories()
    {
         // Get a reference to the controller object
        $CI = get_instance();

        return $CI->ss->getCampos(TABLE_PREFIX."tipocategorias", ["statuscode"=>1]);
    }
}
if ( ! function_exists('retrieve_size_name')){
    
    function retrieve_size_name($idtalla)
    {
         // Get a reference to the controller object
        $CI = get_instance();

        return $CI->ss->getCampos(TABLE_PREFIX."tallas", ["idtallas"=>$idtalla])[0]['talla'];
    }
}
if ( ! function_exists('retrieve_product_name')){
    
    function retrieve_product_name($idproducto)
    {
         // Get a reference to the controller object
        $CI = get_instance();

        return $CI->ss->getCampos(TABLE_PREFIX."productos", ["idproductos"=>$idproducto])[0]['nombre'];
    }
}
if ( ! function_exists('retrieve_table_products')){
    
    function retrieve_table_products($idorder)
    {
         // Get a reference to the controller object
        $CI = get_instance();

        $order_data = $CI->ss->getCampos(TABLE_PREFIX."orders", ["order_token"=>$idorder], "*", 1)->result();

        foreach($order_data as $order): $id_order = $order->id_orders; $id_business = $order->id_business;
            $table = '<table style="width: 100%;margin-bottom: 1rem;color: #524e53;border-collapse: collapse;">
                <thead>
                    <tr>
                      <th style="vertical-align: bottom;border-bottom: 2px solid #e3e6f0;padding: 0.15rem;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;" scope="col">Comercio</th>
                      <th style="vertical-align: bottom;border-bottom: 2px solid #e3e6f0;padding: 0.15rem;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;" scope="col" colspan="3">Producto</th>
                      <th style="vertical-align: bottom;border-bottom: 2px solid #e3e6f0;padding: 0.15rem;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;"></th>
                      <th style="vertical-align: bottom;border-bottom: 2px solid #e3e6f0;padding: 0.15rem;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;"></th>
                      <th style="vertical-align: bottom;border-bottom: 2px solid #e3e6f0;padding: 0.15rem;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;" scope="col">Cant.</th>
                      <th style="vertical-align: bottom;border-bottom: 2px solid #e3e6f0;padding: 0.15rem;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;" scope="col">Precio</th>
                      <th style="vertical-align: bottom;border-bottom: 2px solid #e3e6f0;padding: 0.15rem;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;" scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>';
            $items = $CI->ss->getCamposAndJoin(TABLE_PREFIX."orders_items t1", ["t1.id_order"=>$id_order], [TABLE_PREFIX."productos t2", TABLE_PREFIX."categories t3", TABLE_PREFIX."libraries t4"], ["t1.idproduct=t2.idproductos","t2.categoria=t3.idcategories","t2.imagen=t4.idlibraries"], "t2.nombre,t1.qty,t1.price,t1.total,t3.name,CONCAT('".base_url('uploads/images/')."', t4.file_location) as imagen,t1.comment", 1)->result();
                $business = $CI->ss->getCampos(TABLE_PREFIX."business", ["id_business" => $id_business], "business_name, business_address", 1)->result();
            foreach($items as $key => $product): 
                $table .= '<tr>
                    <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;">
                    <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;" colspan="3">
                      <div class="media">
                        <div class="media-body">
                          <p class="mt-0" style="font-size: 12px">'.$product->nombre.'</p>
                          <p class="mt-0" style="font-size: 14px;color: red;"><u>'.$product->comment.'</u></p>
                        </div>
                      </div>
                    </td>
                    <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;"></td>
                    <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;"></td>
                    <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;">'.$product->qty.'</td>
                    <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;">$ '.number_format($product->price,2).'</td>
                    <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;">$ '.number_format($product->total,2).'</td>
                  </tr>'; 
            endforeach;             
            $table .= '</tbody>
                <tfoot>';

            $comercio = $CI->ss->getCampos(TABLE_PREFIX."business", ["id_business"=>$id_business])[0]['business_name'];

            $domicilio = $order->shipping;

            $table .= '<tr>
                  <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;" colspan="4"></td>
                  <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;"></td>
                  <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;"></td>
                  <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;">Items Subtotal</td>
                  <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;">$ '.number_format($order->order_ammount,2).'</td>
                </tr>
                <tr>
                  <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;" colspan="2"></td>
                  <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;"></td>
                  <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;">Costo Domicilio</td>
                  <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;">'. $comercio .'</td>
                  <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;">$ '.number_format($domicilio,2).'</td>
                </tr>
                <tr>
                  <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;" colspan="4"></td>
                  <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;"></td>
                  <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;"></td>
                  <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;">Orden Total</td>
                  <td style="padding: 0.15rem;vertical-align: middle;border-top: 1px solid #e3e6f0;font-size: 12px;white-space: normal!important;">$ '.number_format($order->order_ammount,2).'</td>
                </tr>';
            $table .= '</tfoot></table>';
        endforeach;   

        return $table;
    }
}
if ( ! function_exists('push')){
    
    function push($idusuario, $tipo_push)
    {
         // Get a reference to the controller object
        $CI = get_instance();
        switch ($tipo_push) {
            case 'Pago pedido':
                $msj = "Se ha recibido su pedido!";
                break;
            case 'Nuevo pedido':
                $msj = "Se ha generado un nuevo pedido!";
                break;
            case 'Pedido Enviado':
                $msj = 'Se ha enviado su pedido!';
                break;
            case 'Nuevo pedido para recoger':
                $msj = 'Nuevo pedido disponible!';
                break;
            case 'Orden en camino':
                $msj = 'Su pedido está en camino!';
                break;
            default:
                $msj = null;
                break;
        }

        if($msj)
        {
            return $CI->mensajeria->pushAplication($idusuario, $msj);
        }
        return  $msj;
    }
}
if ( ! function_exists('retrieve_user_status')){
    
    function retrieve_user_status($idstatus)
    {
         // Get a reference to the controller object
        switch ($idstatus) {
            case 1:
                $msj = "Cuenta Activa";
                break;
            case 2:
                $msj = "Cuenta Suspendida";
                break;
            case 3:
                $msj = "Cuenta Reportada";
                break;
            default:
                $msj = "Cuenta Inactiva";
                break;
        }

        return  $msj;
    }
}
if ( ! function_exists('general_status')){
    
    function general_status($idstatus)
    {
         // Get a reference to the controller object
        switch ($idstatus) {
            case 1:
                $msj = "Activo";
                break;
            case 2:
                $msj = "Suspendido";
                break;
            default:
                $msj = "Inactivo";
                break;
        }

        return  $msj;
    }
}

if ( ! function_exists('alert_business')){
    
    function alert_business($order_token)
    {
        $CI =& get_instance();

        $orders = $CI->ss->getCampos(TABLE_PREFIX."orders", ["order_token"=>$order_token], "*", 1);

        foreach($orders->result() as $order): $idb = $order->id_business;

            $business = $CI->ss->getCampos(TABLE_PREFIX."business", ["id_business"=>$idb], "*", 1)->result()[0];

            $CI->mensajeria->sendEmail(
                "Nuevo pedido!", 
                $business->business_email,//_DEPARTAMENTO_ADMINISTRATIVO, 
                $business->firstname." ".$business->lastname, 
                'Hola, '.$business->firstname, 
                "Pedido Nº <b>".$order_token." ha sido pagado por el consumidor. Comienza a preparar la orden mientras buscamos el domiciliario para entregar tu pedido!. <br><br> Puedes consultar el estado del pedido presionando este botón", 
                '<a class="mcnButton " title="Consultar" href="'.base_url().'orders/view_order/'.$order->order_token.'" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Consultar Orden</a>'
            );
            $report=array(
              "id_user"=>$idb,
              "fecha"=>$CI->ss->fechadehoy(),
              "msj"=>"Notificación comercio: ".$business->business_email,
              "id_order"=>$order->id_orders
            );

            $CI->ss->pushtodb($report, "reports");

        endforeach;
    }
}

/**/

if ( ! function_exists('alert_dealers')){
    function alert_dealers( $id_order , $id_business = null, $id_juster = null)
    {
        $CI =& get_instance();
        $message = "Nuevo pedido!";

        if($id_business && $id_juster)
        {
            $message = "El comercio te ha asignado el pedido #00".$id_order.", recogelo cuanto antes!";
            $justers = $CI->ss->getCampos(USERS_TABLE, ["profile"=>PERFIL_DEALER,"identi"=>$id_business, "id_users"=>$id_juster], "*", 1)->result();
        }else if($id_business)
        {
            $message = "Se ha liberado un nuevo pedido, recogelo cuanto antes!";
            $justers = $CI->ss->getCampos(USERS_TABLE, ["profile"=>PERFIL_DEALER,"identi"=>$id_business], "*", 1)->result();
        }else{
            $justers = $CI->ss->getCampos(USERS_TABLE, ["profile"=>PERFIL_DEALER,"validated_email"=>1,"validated_sms"=>1], "*", 1)->result();
        }

        foreach ($justers as $juster) 
        {
            $order_data = ["order"=>$id_order];
            if($id_juster)
            {
                $order_data = ["my_business" => $id_order];
            }
            $CI->mensajeria->pushAplication($juster->id_users, $message, $order_data);
        }
    }
}

if ( ! function_exists('formulario_tipos_documento')){
    function formulario_tipos_documento( $type )
    {
        switch ($type) {
            case 1: 
                $e = 'Todos';
                break;
            case 2: 
                $e = 'Solo Comercios';
                break;
            case 3: 
                $e = 'Solo Justers';
                break;
            case 4: 
                $e = 'Solo Clientes';
                break;
            case 5: 
                $e = 'Comercios y Justers';
                break;
            case 6: 
                $e = 'Comercios y Clientes';
                break;
            case 7: 
                $e = 'Justers y Clientes';
                break;
            
            default:
                $e = "Sin especificar";
                break;
        }

        return $e;
    }
}

if ( ! function_exists('validar_campo')){
    function validar_campo( $campo, $tipo = 'email' )
    {
        $is_valid = false;

        switch ($tipo) {
            case 'email': 
                $e = ["teléfono","celular","telefono celular", "movil", "número de teléfono", "número de celular", "numero de celuar", "número móvil"];
                break;
            case 'phone': 
                $e = ["teléfono","celular","telefono celular", "movil", "número de teléfono", "número de celular", "numero de celuar", "número móvil"];
                break;
            case 'type_document': 
                $e = ["tipo documento","tipo"];
                break;
            case 'document': 
                $e = ["documento","número de documento", "número", "identificación", "id"];
                break;
            case 'password': 
                $e = ["contraseña","clave", "seguridad"];
                break;
            case 'file': 
                $e = ["imagen","foto","logo","archivo","captura"];
                break;
            case 'logo': 
                $e = ["logo"];
                break;
            case 'time': 
                $e = ["apertura","cierre","tiempo","hora","reloj"];
                break;
            
            default:
                $e = [];
                break;
        }

        foreach($e as $k => $c)
        {
            if(strpos(strtolower($campo), $c) !== FALSE)
            {
                $is_valid = true;
            }
        }

        return $is_valid;
    }
}

if ( ! function_exists('manage_meta_values')){
    function manage_meta_values( $id_account )
    {
        $CI =& get_instance();
        $fields = $CI->ss->getCampos(TABLE_PREFIX."business_meta_keys", ["statuscode"=>1], "*", 1)->result();
        $update_metas = $CI->ss->updaterequest(TABLE_PREFIX."business_meta", ["id_business"=>$id_account, "meta_key >"=>8], ["statuscode"=>0]);
        foreach($fields as $field)
        {
          $id = $field->id_business_meta_keys;
          $name = $field->name;
          $post = $CI->input->post("meta_".$id);
          if($post != "")
          {
            if(validar_campo($name, "logo"))
            {
                $CI->ss->updaterequest(TABLE_PREFIX."business", ["id_business"=>$id_account], ["business_logo"=>$post]);
            }
            $buscar_meta = $CI->ss->getCampos(TABLE_PREFIX."business_meta", ["id_business"=>$id_account,"meta_key"=>$id]);
            if(count($buscar_meta) > 0)
            {
              $CI->ss->updaterequest(TABLE_PREFIX."business_meta", ["meta_key"=>$buscar_meta[0]['meta_key'],"id_business"=>$id_account], ["meta_value"=>$post,"statuscode"=>1]);
            }else{
              $data_metas = ["id_business"=>$id_account,"meta_key"=>$id,"meta_value"=>$post,"statuscode"=>1];
              $save_meta = $CI->ss->pushtodb($data_metas, TABLE_PREFIX."business_meta");
            }
          }
        }
        //$CI->ss->deleterequest(["id_business"=>$id_account,"statuscode"=>0], TABLE_PREFIX."business_meta");
    }
}
if( ! function_exists('build_order_token') )
{
    function build_order_token()
    {
        $CI =& get_instance();

        $token = rand(0,99).$CI->ss->obtenToken(3).$CI->user;

        $buscar = $CI->ss->getCampos(TABLE_PREFIX."orders", ["order_token"=>$token]);

        if( count($buscar) > 0)
        {
            build_order_token();
        }else{
            return $token;
        }

    }
}
if( ! function_exists('set_account_privileges') )
{
    function set_account_privileges($profile,$id=null)
    {
        $CI =& get_instance();

        if(!$id) return;

        $p = [];
        switch ($profile) {
            case PERFIL_PARTNER:
                $p = [2,7,10,11,12,13,14,16,17,18,20];
                break;
            case PERFIL_PATROCINADOR:
                $p = [];
                break;
            
            default:
                # code...
                break;
        }

        foreach($p as $k => $pv)
        {
            $data = array(
                "id_privileges" => $pv,
                "id_usuario" => $id,
                "fecha" => $CI->ss->fechadehoy()
            );

            $CI->ss->pushtodb($data, TABLE_PREFIX."user_privileges");
        }

    }
}
if( ! function_exists('send_welcome_message') )
{
    function send_welcome_message($profile,$id=null)
    {
        $CI =& get_instance();

        if(!$id) return;

        $account = $CI->ss->getCampos(USERS_TABLE,["id_users"=>$id],"email,firstname,lastname,tokenrecovery,tokensesion,validate_email", 1)->result();
        if(count($account)==0) return;
        $account = $account[0];
        $title      = "Bienvenido a JustGo";
        $continue   = false;
        $perfiles_acceso = [PERFIL_CUSTOMER, PERFIL_DEALER];
        switch ($profile) {
            case PERFIL_PARTNER:
                $continue = true;
                $CI->mensajeria->sendEmail(
                    $title, 
                    $account->email, 
                    $account->firstname." ".$account->lastname,
                    'Hola, '.$account->firstname." ".$account->lastname." Bienvenido a JustGo nuestra nueva plataforma especializada en conectar usuarios con tiendas y justers, creada para respaldar nuestro comercio local y nacional.", 
                    "Hemos recibido tu solicitud para pertenecer a nuestros comercios aliados, este correo es la confirmación de la información que suministraste, muy pronto nuestro equipo de servicio al cliente se comunicará contigo para hacer la verificación correspondiente a la mayor brevedad posible <br><br> Recuerda tener a la mano la documentación (foto del RUT, logo y foto de una factura de tu establecimiento) para que podamos certificarte como comercio aliado JustGo y puedas empezar a beneficiarte cuanto antes.",
                    '<a class="mcnButton " title="Consultar" href="'.base_url().'signup/view/'.$account->tokensesion.'" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Consultar Solicitud</a>'
                );
                break;
            case PERFIL_CUSTOMER:
                $message    = "Mensaje de bienvenida";
                $button     = '<a class="mcnButton " title="Validar" href="'.base_url().'app/users/activar/'.$account->tokensesion.'" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Confirmar Correo</a>';
                break;
            case PERFIL_DEALER:
                $continue = true;
                $CI->mensajeria->sendEmail(
                    $title, 
                    $account->email, 
                    $account->firstname." ".$account->lastname,
                    'Hola, '.$account->firstname." ".$account->lastname, 
                    "Bienvenido a JustGo, nuestra nueva plataforma especializada en conectar usuarios con tiendas y justers, creada para respaldar nuestro comercio local y nacional.<br><br>Somos el proveedor ideal de servicios de domicilios para los Justers que trabajan de manera independiente.<br>Hemos recibido tu solicitud para pertenecer a nuestra familia de Justers aliados, este correo es la confirmación de los datos que nos suministraste. <br><br>Recuerda enviar los datos suministrados para completar tu registro y  así poder tener todos los beneficios que te provee la nueva plataforma JustGo.<br><br>¡Corre la voz y comparte los nuevos beneficios a tus amigos!",
                    '<a class="mcnButton " title="Consultar" href="'.base_url().'signup/juster/'.$account->tokensesion.'" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Consultar Solicitud</a>'
                );
                break;
            case PERFIL_PATROCINADOR:
                $continue = true;
                $code = $CI->ss->getCampos(TABLE_PREFIX."codigopromos", ["id_user"=>$id])[0]['codigopromo'];
                $CI->mensajeria->sendEmail(
                    $title, 
                    $account->email, 
                    $account->firstname.' '.$account->lastname, 
                    'Hola, '.$account->firstname." ".$account->lastname." Bienvenido a JustGo", 
                    "Bienvenido a JustGo, nuestra nueva plataforma especializada en conectar usuarios con tiendas y justers, creada para respaldar nuestro comercio local y nacional.<br>Hemos recibido tu solicitud para pertenecer a nuestra familia de Sponsors, este correo confirma los datos que nos suministraste, tu código de activación es: <br> <h2>".$code."</h2><br> Desde  ya puedes empezar a beneficiarte con la plataforma.<br><br>Trabajar en equipo nos mueve.",
                    ''
                );
                //$message    = "Felicitaciones tu solicitud  de  registro de comercio en la plataforma JustGo ha sido aprobada, para continuar es necesario tramitar el pago correspondiente del licenciamiento de la plataforma para  patrocinar tu comercio en JustGo. Presiona el siguiente botón para realizar el pago de tu plan de licenciamiento. $ 10.000 COP";
                break;
            
            default:
                $continue   = false;
                $title      = "JustGo";
                $message    = "No message";
                $button     = "";
                break;
        }

        /*if($profile == 99)
        {
            $CI->mensajeria->sendEmail(
                "Acceso Seguro",
                $account->email, 
                $account->firstname.' '.$account->lastname, 
                'Hola, '.$account->firstname, 
                "Has sido inscrito en JustGo recientemente, por lo que te invitamos a dar seguridad a tu cuenta asignando una contraseña segura y que solo tu conozcas, para cambiar la contraseña presiona el siguiente botón", 
                '<a class="mcnButton " title="Validar" href="'.base_url().'app/recover/'.$account->tokenrecovery.'" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Cambiar mi contraseña</a>'
            );
        }*/
        if($continue)
        {
            $CI->mensajeria->sendEmail(
                "Confirmación de Correo", 
                $account->email,
                $account->firstname.' '.$account->lastname, 
                'Hola, '.$account->firstname, 
                "¡Bienvenido a JustGo! Presiona el siguiente botón para confirmar tu dirección de correo electrónico.", 
                '<a class="mcnButton " title="Validar" href="'.base_url().'app/users/activar/'.$account->validate_email.'" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Confirmar Correo</a>'
            );
            $CI->mensajeria->sms($account->phonenumber,$message);
        }

    }
}
if ( ! function_exists('send_payment_message') ) 
{
    function send_payment_message($profile, $id=null, $idb=null)
    {

        $CI =& get_instance();

        if(!$id) return;

        $account = $CI->ss->getCampos(USERS_TABLE,["id_users"=>$id],"email,firstname,lastname,tokenrecovery,tokensesion", 1)->result();
        if(count($account)==0) return;
        $account = $account[0];
        $title      = "Bienvenido a JustGo";
        $continue   = false;
        $perfiles_acceso = [PERFIL_CUSTOMER, PERFIL_DEALER];
        switch ($profile) {
            case PERFIL_PARTNER:
                $CI->mensajeria->sendEmail(
                    $title, 
                    $account->email, 
                    $account->firstname.' '.$account->lastname, 
                    'Hola, '.$account->firstname." ".$account->lastname, 
                    "Tu solicitud ha sido aprobada, para continuar es necesario tramitar el pago correspondiente del licenciamiento de la plataforma para tu comercio en JustGo. Presiona el siguiente botón para realizar el pago de acuerdo a tu plan de licenciamiento. $ ".PAGO_LICENCIA." COP", 
                    '<a class="mcnButton " title="Consultar" href="'.base_url().'payments/account/'.$account->tokensesion.'" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Pagar inscripción</a>'
                );
                break;
            case PERFIL_PATROCINADOR:
                $business = $CI->ss->getCampos(TABLE_PREFIX."business", ["id_business"=>$idb])[0]['business_name'];
                $CI->mensajeria->sendEmail(
                    $title, 
                    $account->email, 
                    $account->firstname.' '.$account->lastname, 
                    'Hola, '.$account->firstname." ".$account->lastname." Bienvenido a JustGo", 
                    "¡Felicitaciones! tu solicitud de registro de sponsor para el comercio_frisby_______  en la plataforma JustGo ha sido aprobada, para continuar es necesario hacer el pago correspondiente del licenciamiento de uso de la plataforma para patrocinar cada uno de tus comercios en JustGo.
                        <br><br>Presiona el siguiente botón para realizar el pago de tu de licenciamiento de uso: ".PAGO_LICENCIA_SPONSOR,
                    '<a class="mcnButton " title="Consultar" href="'.base_url().'signup/view/'.$account->tokensesion.'" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Consultar Solicitud</a>'
                );
                //$message    = "Felicitaciones tu solicitud  de  registro de comercio en la plataforma JustGo ha sido aprobada, para continuar es necesario tramitar el pago correspondiente del licenciamiento de la plataforma para  patrocinar tu comercio en JustGo. Presiona el siguiente botón para realizar el pago de tu plan de licenciamiento. $ 10.000 COP";
                break;
            
            default:
                $continue   = false;
                $title      = "JustGo";
                $message    = "No message";
                $button     = "";
                break;
        }
    }
}

if( ! function_exists('send_confirmation_message') )
{
    function send_confirmation_message($profile,$id=null)
    {
        $CI =& get_instance();

        if(!$id) return;

        $account = $CI->ss->getCampos(USERS_TABLE,["id_users"=>$id],"email,firstname,lastname,tokenrecovery,tokensesion,validate_email", 1)->result();
        if(count($account)==0) return;
        $account = $account[0];
        $title      = "Bienvenido a JustGo";
        $CI->mensajeria->sendEmail(
            "Confirmación de Correo", 
            $account->email,
            $account->firstname.' '.$account->lastname, 
            'Hola, '.$account->firstname, 
            "¡Bienvenido a JustGo! Presiona el siguiente botón para confirmar tu dirección de correo electrónico.", 
            '<a class="mcnButton " title="Validar" href="'.base_url().'app/users/activar/'.$account->validate_email.'" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Confirmar Correo</a>'
        );
        $CI->mensajeria->sms($account->phonenumber,$message);

    }
}
if( ! function_exists('trybitly') )
{

    function trybitly($url)
    {
        $CI =& get_instance();

        $CI->load->helper('bitly');

        $client_id = '58c5a9837c388097817013e9f62c0138e3a641e7';
        $client_secret = '31d81829000aa4d1c1268bcdfae4263076399472';
        $user_access_token = 'abf1372d6fc6268a6c8f17a8526f8663fac26539';
        $user_login = 'o_564ontb2fd';
        $user_api_key = 'R_00505abc3ade4a46bb2a7af4d6b77f57';
        $params = array();
        $params['access_token'] = $user_access_token;
        $params['longUrl'] = $url;
        //$params['domain'] = 'validocus.com';
        $results = bitly_get('shorten', $params);

        //echo json_encode($results);
        return $results['data']['url'];
    }
}
if( ! function_exists('send_admin_message') )
{
    function send_admin_message($profile = null, $id=null)
    {
        $CI =& get_instance();

        if(!$profile) return;
        if(!$id) return;

        if($profile == PERFIL_PARTNER)
        {
            $account = $CI->ss->getCampos(TABLE_PREFIX."business",["id_business"=>$id],"*", 1)->result();
        }else{
            return; 
            //$account = $CI->ss->getCampos(USERS_TABLE, ["id_users"=>$id], "*", 1)->result();
        }

        $account_type = retrieve_account_type($profile);

        if(count($account)==0) return;

        $account = $account[0];

        $find_administratives = $CI->ss->getCampos(TABLE_PREFIX."administratives", ["profile"=>$profile, "statuscode"=>1], "*", 1)->result();
        
        $link = trybitly('https://justgoapp.co/dashboard/partners/addpartner/'.$id);

        if(count($find_administratives) == 0)
        {
            if(is_array(_DEPARTAMENTO_ADMINISTRATIVO))
            {
                $names = _NOMBRE_ADMINISTRATIVO;
                $phones = _TELEFONO_ADMINISTRATIVO;
                foreach(_DEPARTAMENTO_ADMINISTRATIVO as $key => $dep)
                {
                    $CI->mensajeria->sendEmail(
                        "Nueva solicitud: ".$account->business_name, 
                        $dep, 
                        @$names[$key], 
                        'Hola, '.@$names[$key], 
                        "Un nuevo ".$account_type." ha cargado su información a través del formulario web: <br> <h3 style='font-weight: 900'>".$account->business_name."</h3>", 
                        '<a class="mcnButton " title="Validar" href="'.base_url().'partners/addpartner/'.$id.'" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Ver Solicitud</a>'
                    );
                    $CI->mensajeria->sms($phones[$key],"Un nuevo ".$account_type." ha cargado su información a través del formulario web: ".$account->business_name.". Consultar: ".$link);
                }
            }
        }else{
            foreach($find_administratives as $key => $dep)
            {
                $CI->mensajeria->sendEmail(
                    "Nueva solicitud: ".$account->business_name, 
                    $dep->email, 
                    $dep->fullname, 
                    'Hola, '.$dep->fullname, 
                    "Un nuevo ".$account_type." ha cargado su información a través del formulario web: <br> <h3 style='font-weight: 900'>".$account->business_name."</h3>", 
                    '<a class="mcnButton " title="Validar" href="'.base_url().'partners/addpartner/'.$id.'" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Ver Solicitud</a>'
                );
                $CI->mensajeria->sms($dep->phone,"Un nuevo ".$account_type." ha cargado su información a través del formulario web: ".$account->business_name.". Consultar: ".$link);
            }
        }
    }
}
if( ! function_exists('send_admin_alert') )
{
    function send_admin_alert($profile = null, $id=null, $title = "", $body_message = "", $button_url = "#", $button_text = "", $ext = null)
    {
        $CI =& get_instance();

        if(!$profile) return;
        if(!$id) return;

        if($profile == PERFIL_PARTNER)
        {
            $account = $CI->ss->getCampos(USERS_TABLE,["identi"=>$id,"profile"=>PERFIL_PARTNER],"*", 1)->result();
        }else{
            $account = $CI->ss->getCampos(USERS_TABLE, ["id_users"=>$id], "*", 1)->result();
        }

        $account_type = retrieve_account_type($profile);

        if(count($account)==0) return;

        if($title == "") return;

        $account = $account[0];

        $find_administratives = $CI->ss->getCampos(TABLE_PREFIX."administratives", ["profile"=>$profile, "statuscode"=>1], "*", 1)->result();
        
        $link = trybitly(base_url().$button_url);

        $body_button = "";

        if($button_url != "#" && $button_text != "")
        {
            $body_button = '<a class="mcnButton " title="Validar" href="'.$link.'" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">'.$button_text.'</a>';
        }
        if(count($find_administratives) == 0)
        {
            if(is_array(_DEPARTAMENTO_ADMINISTRATIVO))
            {
                $names = _NOMBRE_ADMINISTRATIVO;
                $phones = _TELEFONO_ADMINISTRATIVO;
                foreach(_DEPARTAMENTO_ADMINISTRATIVO as $key => $dep)
                {
                    $CI->mensajeria->sendEmail(
                        $title,
                        $dep, 
                        @$names[$key], 
                        'Hola, '.@$names[$key], 
                        $body_message, 
                        $body_button
                    );
                    $CI->mensajeria->sms($phones[$key], $body_message.". Consultar: ".$link);
                }
                $report=array(
                    "iduser"=>$id,
                    "fecha"=>$CI->ss->fechadehoy(),
                    "msj"=>"Notificación administrativa: ".implode(",", _DEPARTAMENTO_ADMINISTRATIVO),
                    "idorder"=>$ext
                );
                $CI->ss->pushtodb($report, "reports");
            }
        }else{
            foreach($find_administratives as $key => $dep)
            {
                $CI->mensajeria->sendEmail(
                    $title.$account->fullname, 
                    $dep->email, 
                    $dep->fullname, 
                    'Hola, '.$dep->fullname, 
                    $body_message, 
                    $body_button
                );
                $CI->mensajeria->sms($dep->phone,$body_message.". Consultar: ".$link);
            }
            $report=array(
                "iduser"=>$id,
                "fecha"=>$CI->ss->fechadehoy(),
                "msj"=>"Notificación administrativa: ".implode(",", $find_administratives),
                "idorder"=>$ext
            );
            $CI->fn->pushtodb($report, "reports");
        }
    }
}
if ( ! function_exists( 'retrieve_account_type' ) ) 
{
    function retrieve_account_type($profile)
    {
        switch ($profile) {
            case PERFIL_PARTNER:
                return "Comercio";
                break;
            case PERFIL_DEALER:
                return "Juster";
                break;
            case PERFIL_CUSTOMER:
                return "Cliente";
                break;
            case PERFIL_PATROCINADOR:
                return "Sponsor";
                break;
            
            default:
                return "Sin definir";
                break;
        }
    }
}