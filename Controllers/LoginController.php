<?php 

namespace Controllers;

use Model\Usuario;
use MVC\Router;

class LoginController {
    
    public static function login(Router $router) {  
        $router->render('auth/login'); //no definimos aquí extención ni nombre de la carpeta porque estas estan definidas dentro del metodo render.
    }

    public static function logout() {
        echo "Desde Logout";
    }

    public static function olvide(Router $router) {
        $router->render('auth/olvide-password', [

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
                    
                }
            }
        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }    

}