<?php
namespace Model;

class Usuario extends ActiveRecord {
    // Base de Datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','apellido','email','password','telefono','admin','confirmado','token']; //columnasDB es para normalizar los datos que se mentiene en memoria

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null; // "??" Si no esta precente, será nulo.
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? null; // admin y confirmado solo acepta 2 valores posibles.
        $this->confirmado = $args['confirmado'] ?? null;
        $this->token = $args['token'] ?? '';
    }

    // Mensajes de Validación para la creación de una cuenta
    public function validarNuevaCuenta() {
        if (!$this->nombre) {
            self::$alertas['error'][]= 'El Nombre es obligatorio';
        }
        
        if (!$this->apellido) {
            self::$alertas['error'][]= 'El Apellido es obligatorio';
        }

        if (!$this->email) {
            self::$alertas['error'][]= 'El email es obligatorio';
        }

        // Password
        if (!$this->password) {
            self::$alertas['error'][]= 'El password es obligatorio';
        }
        if (strlen($this->password) < 6) { // strlen evalua la longitud del valor, si es menor a 6 cae el mensaje
            self::$alertas['error'][]= 'El password no puede tener menos de 6 caracteres';
        }

        return self::$alertas;
    }

    // Revisa si el ya Usuario existe
    public function existeUsuario() {
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        //debuguear($query); // útil para verificar que el la consulta no tenga errores.
        
        $resultado = self::$db->query($query);

        if ($resultado->num_rows) {
            self::$alertas['error'][] = 'Este usuario ya está registrado';
        }

        return $resultado;
    }

    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }
}