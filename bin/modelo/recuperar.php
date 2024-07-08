<?php

namespace modelo;

use config\connect\DBConnect as DBConnect;
use PHPMailer\PHPMailer\PHPMailer;
use utils\validar;

class recuperar extends DBConnect
{
    use validar;
    private $email;

    public function getRecuperarSistema($email)
    {
        if (!$this->validarString('correo', $email)) {
            return $this->http_error(400, 'Correo inválido.');
        }

        $this->email = $email;

        return $this->recuperarSistema();
    }

    protected function recuperarSistema()
    {
        try {
            $this->conectarDB();
            $new = $this->con->prepare("SELECT correo, CONCAT(nombre,' ',apellido) AS nombre FROM usuario WHERE status = 1 and correo = ?");
            $new->bindValue(1, $this->email);
            $new->execute();
            $data = $new->fetchAll();

            if (!isset($data[0]['correo'])) {
                return $this->http_error(400, 'El correo no está registrado.');
            }

            $nombre = $data[0]['nombre'];

            $date = date('m/d/Yh:i:sa', time());
            $rand = rand(10000, 99999);
            $str = $date . $rand;
            $generatedPass = hash('crc32b', $str);
            $pass = password_hash($generatedPass, PASSWORD_BCRYPT);


            $new = $this->con->prepare("UPDATE usuario SET password = ? WHERE correo = ? AND status = 1");
            $new->bindValue(1, $pass);
            $new->bindValue(2, $this->email);
            $new->execute();
            $this->desconectarDB();

            if ($this->enviarEmail($this->email, $generatedPass, $nombre)) {
                return ['resultado' => 'ok', 'msg' => 'Correo enviado'];
            } else {
                return $this->http_error(500, 'Error al enviar correo');
            }
        } catch (\PDOException $error) {
            return $this->http_error(500, $error);
        }
    }

    private function enviarEmail($email, $pass, $name): bool
    {
        $mail = new PHPMailer(true);

        $body = '
        <body style="padding: 5%; height: 100%; width: 100%;">
					<main>
						<header style="font-size: 20px;">
					  	<h1 style="font-weight: lighter; font-family: Times New Roman;">Recuperación de contraseña.</h1>
						</header>
						<div style="font-family: arial; font-size: 14px;">
							<p>Usted ha solicitado una contraseña para ingresar al sistema de la Fundación Centro Médico Wesley. Se generó una nueva contraseña para que pueda ingresar, por favor siga los pasos indicados para crear una nueva contraseña.</p>

							<h4>Contraseña generada: </h4>
							<h2>' . $pass . '</h2>

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

        $mail->setFrom('centromedicowesley@gmail.com', 'Fundación Centro Médico Wesley');
        $mail->addAddress($email, $name);
        $mail->Subject = 'Recuperación de contraseña';
        $mail->Body = $body;
        $mail->AltBody = 'Error: HTML no soportado.';
        $mail->CharSet = 'UTF-8';

        return !!$mail->send();
    }
}
