<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends CI_Controller {
  public function __construct()
  {
    parent::__construct();
    $this->load->helper(array('url_helper','url','form','functions'));
    $this->load->library(array('session','form_validation'));
    $this->load->model(array('fn'));
    $this->tabla = "ss_users";
    $this->user = null;
    $this->res = array(
      "success" => false,
      "msj" => "Acceso restringido",
      "data" => null,
      "login" => false
    );
  }
  public function index()
  {
    $this->load->view('user/contact', ["allowed"=>false,"changed"=>false,"token"=>null]);
  }
  public function pay($token = null)
  {
    if($token)
    {
      $find_generated_order = (object) $this->ss->getCampos(TABLE_PREFIX."orders_payments", ["payment_transaction"=>$token], "id_user, id_orders_payments as id_orders, payment_token, payment_transaction as order_token, payment_amount as total_full, payment_amount as order_total");

      if($find_generated_order->success)
      {
        if($find_generated_order->data[0]->payment_token === null)
        {
          $data = array("changed"=>false,"allowed"=>true,"token"=>$token, "order"=>$find_generated_order->data[0]);
        }else{
          $data = array("changed"=>false,"allowed"=>false,"token"=>$token, "order"=>null);
        }
        $this->load->view('user/contact', $data);
      }else{
        $this->load->view('user/contact', ["allowed"=>false,"changed"=>false,"token"=>null]);
      }
    }else{
      $this->load->view('user/contact', ["changed"=>false,"allowed"=>false,"token"=>"No method no token"]);
    }
  }
  public function transactions()
  {
    $payment_status         = $_REQUEST['x_cod_response'];
    $payment_state          = $_REQUEST['x_cod_transaction_state'];
    $payment_request        = $_REQUEST['x_transaction_state'];
    $payment_i              = $_REQUEST['x_transaction_id'];

    $payment_transaction    = $_REQUEST['x_id_invoice']; //pay_t identificador de Factura
    $plan_transaction       = $_REQUEST['x_extra1']; //pay_p'); //identificador de plan
    $payment_method         = $_REQUEST['x_franchise']; //metodo de pago
    $id_factura             = $_REQUEST['x_extra2']; //pay_i'); //id registro de factura
    $info                   = $this->ss->getCampos(TABLE_PREFIX."orders_payments", ["id_orders_payments" => $id_factura]);
    $idUser                 = $_REQUEST['x_extra3']; //id del usuario
    $payment_user           = $_REQUEST['x_amount_ok']; //monto a pagar
    $payment_country        = "CO";//$this->model->getLocationInfo('country'); //pais de pago
    $payment_address        = "1.00";//$this->model->getLocationInfo('ip');
    $payment_date           = $_REQUEST['x_transaction_date'];
    $payment_token          = $_REQUEST['x_approval_code'];
    $payment_ref            = $_REQUEST['x_ref_payco'];
    $payment_cicle          = 1;

    $orders_data = (object) $this->ss->getCampos(TABLE_PREFIX."orders_payments", ["payment_transaction" => $payment_transaction]);

    if($orders_data->success)
    {
      $orders_data = $orders_data->data[0];

      $payment_amount         = $orders_data->payment_amount; //monto del plan
      $payment_currency       = $orders_data->payment_currency; //moneda
      $total_ammount_on_order = 0;
      if($payment_state == 1 && $payment_request == 'Aceptada')
      {
        $data["payment_user"] = $orders_data->payment_amount;
        $data["payment_total"] = $payment_user;
        $data["payment_token"] = $payment_token;
        $data["payment_location"] = $payment_country;
        $data["payment_method"] = $payment_method;
        $data["payment_currency"] = $payment_currency;
        $data["payment_date"] = $payment_date;
        $data["payment_address"] = $payment_address; 

        $save = $this->ss->update($data, ["id_orders_payments"=>$orders_data->id_orders_payments,"payment_token"=>NULL], "ss_orders_payments");

        if($save)
        {
          $data = array( "statuscode" => 2 );
          //$update = $this->ss->update(TABLE_PREFIX."orders", ["order_token"=>$payment_transaction], $data);

          $find_wallet = (object) $this->ss->getCampos("ss_user_wallets", ["id_user"=>$idUser]);

          if(count($find_wallet->data) > 0)
          {
            $wallet = $find_wallet->data[0];
            $ammount = $wallet->ammount + $payment_user;

            $save = $this->ss->update(["ammount"=>$ammount], ["id_user"=>$idUser], "ss_user_wallets");
          }else{
            $campos = ["id_user"=>$idUser, "ammount"=>$payment_user, "saved"=>date("Y-m-d H:i:s")];
            $save = $this->ss->insert($campos, "ss_user_wallets");
          }
        }
      }
      if($payment_state == 2 && $payment_request == 'Rechazada')
      {
        /*foreach($orders_data as $order)
        {
          $find_items = $this->ss->getCamposAndJoin(TABLE_PREFIX."orders_items t1", ["t1.id_order"=>$order['id_orders']], [TABLE_PREFIX."productos t2"], ["t1.idproduct=t2.idproductos"], ["t1.*,t2.inventory"], 1, "t1.idproduct")->result();

          foreach($find_items as $item)
          {
            if($item->inventory == 1)
            {
              $buyed_qty = $item->qty;
              $actual_qty = $item->cantidad;

              $returned_qty = $buyed_qty + $actual_qty;

              $this->ss->updaterequest(TABLE_PREFIX."productos", ["idproductos"=>$item->idproduct], ["cantidad"=>$returned_qty]);
            }
          }

          $order_cancelation = [
            "id_order"=> $order['id_orders'],
            "reason" => 4,
            "message" => "Transacción ".$payment_request." por ePayco",
            "saved" => today(),
            "statuscode" => 1,
            "coupon" => 0,
            "id_user" => 1
          ];

          $this->ss->pushtodb($order_cancelation, TABLE_PREFIX."orders_cancellation");

          $this->ss->updaterequest(TABLE_PREFIX."orders", ["id_orders"=>$order['id_orders']], ["statuscode"=>10]);

          $report=array(
            "id_user"=>$idUser,
            "fecha"=>$this->ss->fechadehoy(),
            "msj"=>"Pago ".$payment_request.": #00".$order['id_orders'].". Confirmación: ".$payment_token,
            "id_order"=>$order['id_orders'],
            "statuscode"=>1
          );
          $this->ss->pushtodb($report, "reports");
        }*/
      }
    }
  }
  public function account($token)
  {
    $validarToken = $this->ss->getCampos(USERS_TABLE,array("tokensesion"=>$token),"*");
    if(count($validarToken)>0)
    {
      $membership = $this->ss->getCampos(TABLE_PREFIX."memberships",["id_user"=>$validarToken[0]['id_users'],"statuscode"=>1]);
      if(count($membership)==0)
      {
        $find_pending_payment = $this->ss->getCampos(TABLE_PREFIX."accountpayment", ['id_user'=>$validarToken[0]['id_users'],"payment_token"=>NULL]);
        $monto_pagar = PAGO_LICENCIA;
        $validarToken[0]['mensaje'] = "Tu solicitud ha sido aprobada, para continuar es necesario tramitar el pago correspondiente del licenciamiento de la plataforma para tu comercio en JustGo. Presiona el siguiente botón para realizar el pago de acuerdo a tu plan de licenciamiento.";
        if($validarToken[0]['profile'] == PERFIL_PATROCINADOR)
        {
          $monto_pagar = PAGO_LICENCIA_SPONSOR;
          $validarToken[0]['mensaje'] = "Felicitaciones tu solicitud  de  registro de comercio en la plataforma JustGo ha sido aprobada, para continuar es necesario tramitar el pago correspondiente del licenciamiento de la plataforma para  patrocinar tu comercio en JustGo. Presiona el siguiente botón para realizar el pago de tu plan de licenciamiento.";
        }
        if(count($find_pending_payment) == 0)
        {
          $transaction = uniqid();
          $bill_data = array(
            "id_user" => $validarToken[0]['id_users'],
            "plan_transaction" => $transaction,
            "payment_amount" => $monto_pagar,
            "payment_total" => $monto_pagar,
            "payment_user" => $validarToken[0]['id_users'],
            "payment_token" => null,
            "payment_transaction" => 1,
            "fecha" => $this->ss->fechadehoy(),
            "payment_location" => null,
            "payment_method" => null,
            "payment_currency" => "COP",
            "payment_address" => null
          );
          $bill_id = $this->ss->pushtodb($bill_data, TABLE_PREFIX."accountpayment", true);
        }else{
          $bill_id = $find_pending_payment[0]['id_accountpayment'];
          $transaction = $find_pending_payment[0]['plan_transaction'];
        }

        $validarToken[0]['monto'] = $monto_pagar;

        $validarToken[0]['pay_link']= site_url('payments/payment');
        $validarToken[0]['pay_i']   = $bill_id;
        $validarToken[0]['pay_t']   = $transaction;
        $validarToken[0]['pay_p']   = "";
        $validarToken[0]['pay_m']   = "";
        $validarToken[0]['pay_c']   = $monto_pagar;
        $validarToken[0]['msg']     = 'Seras redirigido a la ventana de pago...';
        $data = array("changed"=>false,"allowed"=>true,"token"=>$token, "payment"=>$validarToken[0]);
      }else{
        $data = ["allowed"=>false,"changed"=>false,"token"=>$validarToken];
      }
      $this->load->view('user/contact', $data);
    }else{
      $this->load->view('user/contact', ["allowed"=>false,"changed"=>false,"token"=>$validarToken]);
    }
  }
  public function payment()
  {
    $payment_status         = $_REQUEST['x_cod_response'];
    $payment_state          = $_REQUEST['x_cod_transaction_state'];
    $payment_request        = $_REQUEST['x_transaction_state'];
    $payment_i              = $_REQUEST['x_transaction_id'];

    $payment_transaction    = $_REQUEST['x_id_invoice']; //pay_t identificador de Factura
    $plan_transaction       = $_REQUEST['x_extra1']; //pay_p'); //identificador de plan
    $payment_method         = $_REQUEST['x_franchise']; //metodo de pago
    $id_factura             = $_REQUEST['x_extra2']; //pay_i'); //id registro de factura
    $info                   = $this->ss->getCampos(TABLE_PREFIX."accountpayment", ["id_accountpayment" => $id_factura])[0];
    $idUser                 = $_REQUEST['x_extra3']; //id del usuario
    $payment_user           = $_REQUEST['x_amount_ok']; //monto a pagar
    $payment_country        = "CO";//$this->model->getLocationInfo('country'); //pais de pago
    $payment_address        = "1.00";//$this->model->getLocationInfo('ip');
    $payment_date           = $_REQUEST['x_transaction_date'];
    $payment_token          = $_REQUEST['x_approval_code'];
    $payment_ref            = $_REQUEST['x_ref_payco'];
    $payment_cicle          = 1;

    $fromV=$this->ss->getCampos(TABLE_PREFIX."accountpayment", ["plan_transaction" => $payment_transaction]);
    if(count($fromV)>0)
    {
      $payment_amount         = $info['payment_amount']; //monto del plan
      $payment_currency       = $info['payment_currency']; //moneda
      if($payment_state == 1 && $payment_request == 'Aceptada')
      {
        $data["payment_amount"] = $payment_user;
        $data["payment_user"] = $payment_user;
        $data["payment_token"] = $payment_token;
        $data["payment_transaction"] = $payment_transaction;
        $data["payment_location"] = $payment_country;
        $data["payment_method"] = $payment_method;
        $data["payment_currency"] = $payment_currency;
        $data["payment_date"] = $payment_date;
        $data["payment_address"] = $payment_address;                
        $res['data'] = $data;
        $save = $this->ss->updaterequest(TABLE_PREFIX."accountpayment", ["plan_transaction"=>$payment_transaction], $data);

        if($save)
        {
          $data = array(
            "statuscode" => 1
          );
          $update = $this->ss->updaterequest(USERS_TABLE, ["id_users"=>$idUser], $data);

          $saved = $this->ss->fechadehoy();
          $expire = date('Y-m-d H:i:s', strtotime('+30 days', strtotime($saved)));
          $membership = array(
            "id_user" => $idUser,
            "saved" => $saved,
            "updated" => $saved,
            "token" => $payment_token,
            "expiration" => $expire,
            "ammount" => $payment_user,
            "statuscode" => 1
          );
          $save_membership = $this->ss->pushtodb($membership, TABLE_PREFIX."memberships");

          $user = $this->ss->getCampos(USERS_TABLE, ["id_users"=>$idUser]);
          if($user[0]['profile'] == PERFIL_PARTNER)
          {
            $query=$this->ss->updaterequest(TABLE_PREFIX."business", array("id_business"=>$user[0]['identi']), $data);
            $this->mensajeria->sendEmail(
              "Activación de cuenta", 
              $user[0]['email'], 
              $user[0]['fullname'], 
              'Hola, '.$user[0]['firstname'], 
              "El pago de tu licenciamiento de uso de la plataforma ha sido recibido y tu cuenta ha sido habilitada.", 
              '<a class="mcnButton " title="Recuperar" href="'.base_url().'dashboard" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Iniciar Sesión</a>'
            );
            $this->mensajeria->sendEmail(
              "Acceso Seguro",
              $user[0]['email'], 
              $user[0]['fullname'], 
              'Hola, '.$user[0]['firstname'], 
              "Te has  inscrito en JustGo recientemente, por lo que te invitamos a dar seguridad a tu cuenta asignando una contraseña segura y que solo tu conozcas, para cambiar la contraseña presiona el siguiente botón", 
              '<a class="mcnButton " title="Validar" href="'.base_url().'app/recover/'.$user[0]['tokensesion'].'" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Cambiar contraseña</a>'
            );

            send_admin_alert(PERFIL_PARTNER, $idUser, "Pago licencia", "Un nuevo PERFIL ha hecho efectivo su pago en JustGo.", 'dashboard', "Iniciar Sesión", null);
          }
          if($user[0]['profile'] == PERFIL_PATROCINADOR)
          {
            /*$code = $this->ss->getCampos(TABLE_PREFIX."codigopromos", ["id_user"=>$idUser])[0]['codigopromo'];
            $this->mensajeria->sendEmail(
              "Activación de cuenta", 
              $user[0]['email'], 
              $user[0]['fullname'], 
              'Hola, '.$user[0]['firstname'], 
              "Bienvenido a JustGo nuestra nueva plataforma especializada en conectar usuarios con tiendas y justers, creada para respaldar nuestro comercio local y nacional.<br>Hemos recibido tu solicitud para pertenecer a nuestros Sponsors aliados, este correo es confirmación de la información que suministraste, tu código de patrocinador es: <br><br><h3>".$code."</h3> <br><br>Desde ya puedes empezar a beneficiarte con esta nueva plataforma.<br><br>Trabajar en equipo nos mueve", 
              ''
            );*/
            send_admin_alert(PERFIL_PATROCINADOR, $idUser, "Pago licencia", "Un nuevo PERFIL ha hecho efectivo su pago en JustGo.", 'dashboard', "Iniciar Sesión", null);
          }
          $report=array(
            "iduser"=>$idUser,
            "fecha"=>$this->ss->fechadehoy(),
            "msj"=>"Pago confirmado: ".$user[0]['email']
          );
          /*$this->mensajeria->sendEmail(
            "Pago licencia comercio", 
            _DEPARTAMENTO_ADMINISTRATIVO, 
            _NOMBRE_ADMINISTRATIVO, 
            'Hola, '._NOMBRE_ADMINISTRATIVO, 
            "", 
            '<a class="mcnButton " title="Validar" href="'.base_url().'" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;"></a>'
          );*/
          $this->ss->pushtodb($report, "reports");
        }
      }
    }
    echo json_encode($res);
  }
}
