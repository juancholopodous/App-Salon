<h1 class="nombre-pagina">Olvidaste tu Password?</h1>
<p class="descripcion-pagina">Reestablece tu password escibiendo tu email a continuación</p>

<form class="formulario" action="/olvide" method="POST">
    <div class="campo">
        <label for="email">E-mail:</label>
        <input 
            type="email"
            id="email"
            name="email"
            placeholder="Tu email"
        />
    </div>

    <input type="submit" class="boton" value="Enviar Instrucciones"/>
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una aquí</a>
</div>