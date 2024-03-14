<?php

namespace modelo;
use config\connect\DBConnect as DBConnect;
use PHPMailer\PHPMailer\PHPMailer;

class recuperar extends DBConnect{
	private $email;


	public function __construct(){
		parent::__construct();

	}

	public function getRecuperarSistema($email){
		if(preg_match_all("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email) == false){
			die(json_encode(['resultado' => 'Error de email' , 'error' => 'Correo inválido.']));
		}

		$this->email = $email;

		$this->recuperarSistema();
	}

	protected function recuperarSistema(){

		try{
			$this->conectarDB();
			$new = $this->con->prepare("SELECT correo, CONCAT(nombre,' ',apellido) AS nombre FROM usuario WHERE status = 1 and correo = ?");
			$new->bindValue(1 , $this->email);
			$new->execute();
			$data = $new->fetchAll();

			if(!isset($data[0]['correo'])){
				die(json_encode(['resultado' => 'Error de email' , 'error' => 'El correo no está registrado.']));
			}

			$nombre = $data[0]['nombre'];

			$date = date('m/d/Yh:i:sa', time());
			$rand = rand(10000,99999);
			$str = $date.$rand;
			$generatedPass = hash('crc32b', $str);
			$pass = password_hash($generatedPass, PASSWORD_BCRYPT);


			$new = $this->con->prepare("UPDATE usuario SET password = ? WHERE correo = ? AND status = 1");
			$new->bindValue(1, $pass);
			$new->bindValue(2, $this->email);
			$new->execute();

			if($this->enviarEmail($this->email, $generatedPass, $nombre)){
				$resultado = ['resultado' => 'Correo enviado'];
			}else{
				$resultado = ['resultado' => 'Error al enviar correo'];
			}

			$this->desconectarDB();
			die(json_encode($resultado));

		}catch(\PDOException $error){

			return $error;
		}


	}

	private function enviarEmail($email, $pass, $name){

		$mail = new PHPMailer(true);

		$body = '<body style="height: 100%; width: 100%;">
		<main>
		<header style="margin: 5%; font-size: 2vh;">
		<span style="width: 80%;display: flex; align-items: center; justify-content: center;">
		<img src="https://farmaciamedisalud.000webhostapp.com/MediSalud/assets/img/Logo_titulo.png" width="150px" height="150px">
		<h1 style="margin-left: 20px; font-weight: lighter; font-family: Times New Roman;">Recuperación de contraseña.</h1>
		</span>
		</header>
		<hr style="width: 80%;">
		<div style="margin: 5%; font-family: arial; font-size: 2vh;">
		<p>Usted ha solicitado una contraseña para ingresar al sistema de inventario de la farmacia MediSalud. Se generó una nueva contraseña para que pueda ingresar, por favor siga los pasos indicados para crear una nueva contraseña.</p>

		<h4>Contraseña generada: </h4>
		<h2>'.$pass.'</h2>

		<h4>Pasos a seguir: </h4>
		<ol>
		<li>Iniciar sesión con la contraseña generada.</li>
		<li>Dirigirse a "Mi perfil", dando click al nombre de usuario se desplegará un menú en el que está la opción "Mi perfil".</li>
		<li>Dentro del módulo Perfil, entrar a la opción de cambiar contraseña.</li>
		<li>Colocar la contraseña generada como contraseña actual.</li>
		<li>Colocar una nueva contraseña y su confirmación.</li>
		<li>Por último, enviar el formulario con el botón "Cambiar contraseña".</li>
		</ol>
		<p>Si siguió los pasos correctamente, ya podrá acceder cuando quiera al sistema con su nueva contraseña.</p>
		</div>

		</main>
		</body>';

		$mail->isSMTP(); 
		$mail->SMTPDebug = 0;
		$mail->Host = _SMTP;
		$mail->Port = 587;
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth = true;
		$mail->Username = _SMTP_USER; 
		$mail->Password = _SMTP_PASS; 

		$mail->setFrom('medisalud-farmacia@gmail.com', 'Farmacia MediSalud C.A');
		$mail->addAddress($email, $name);
		$mail->Subject = 'Recuperación de contraseña';
		$mail->Body = $body; 
		$mail->AltBody = 'Error: HTML no soportado.';
		$mail->CharSet = 'UTF-8';
			// $mail->SMTPOptions = array(
			//                     'ssl' => array(
			//                         'verify_peer' => false,
			//                         'verify_peer_name' => false,
			//                         'allow_self_signed' => true
			//                     )
			//                 );
		if(!$mail->send()){
			return false;
		}else{
			return true;
		}

	}
}

?>