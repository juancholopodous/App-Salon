<?php 

namespace Controllers;

use Model\Usuario;
use MVC\Router;
use Classes\Email;

class LoginController {
    
    public static function login(Router $router) {  
        $alertas = [];

        $auth = new Usuario;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            
            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                // Comprobar que exista el usuario
                $usuario = Usuario::Where('email', $auth->email);

                if ($usuario) {
                    // Verificamos el password
                    if ( $usuario->comprobarPasswordAndVerificado($auth->password) ) {
                        //Autenticar el usuario
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionamiento
                        if ($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');

                        } else {
                            header('Location: /cita');
                        }
                    }

                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
                
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [ //no definimos aquí extención ni nombre de la carpeta porque estas estan definidas dentro del metodo render.
            'alertas' => $alertas,
            'auth' => $auth
        ]);
    }

    public static function logout() {
        echo "Desde Logout";
    }

    public static function olvide(Router $router) {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            //debuguear($auth);
            if ( empty($alertas) ) {
                $usuario = Usuario::Where('email', $auth->email);

                if ($usuario && $usuario->confirmado === "1") {
                    // El usuario existe Y está confirmado (generamos un nuevo token de un solo uso)
                    $usuario->crearToken();
                    $usuario->guardar();

                    // To Do: Enviar el mail al usuario...
                    Usuario::setAlerta('exito', "Revisa tu email");

                }else {
                    // El usuario NO existe Ó NO está confirmado
                    Usuario::setAlerta('error', "el usuario no existe ó no esta confirmado aún");
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar() {
        echo "Desde recuperar";
    }

    public static function crear(Router $router) {
        
        $usuario = new Usuario;
        
        // Alertas Vacías
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            //Revisamos que el arreglo Alertas esté vacío
            if (empty($alertas)) {
                
                //Verificar que el usuario no esté registrado
                $resultado = $usuario->existeUsuario();

                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                }else {
                    // El usuario no está registrado
                    $usuario->hashPassword();

                    // Generar un Token único
                    $usuario->crearToken();

                    // Enviar el Email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token); 
                    $email->enviarConfiramcion();

                    // Crear el Usuario
                    $resultado = $usuario->guardar();

                    //debuguear($usuario);

                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router) {
        
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router) {

        $alertas = [];    
        $token = s($_GET['token']);
        $usuario = Usuario::Where('token', $token);

        if ( empty($usuario) ) {
            // Mensaje de error
            Usuario::setAlerta('error', 'Token No válido');
            
            }else {
            // Modificar a usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta comprobada correctamente');
        }

        // Obtener Alertas
        $alertas = Usuario::getAlertas();

        // Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}