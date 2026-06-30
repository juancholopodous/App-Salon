<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form class="formulario" action="/" method="POST">
    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="text"
            id="email"
            placeholder="Ingresa tu Email"
            name="email" 
            value="<?php echo s($auth->email); ?>" 
            /> <!-- name="email" nos permite leer luego el valor usando POST -->
    </div>

    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            placeholder="Ingresa tu Password"
            name="password"
        />
    </div>

    <input type="submit" class="boton" value="Iniciar Sesión"/>
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una aquí</a>
    <a href="/olvide-password">¿Olvidaste tu password?</a>
</div>