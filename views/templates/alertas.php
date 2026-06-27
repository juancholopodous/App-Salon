<?php 
    foreach($alertas as $key => $mensajes): // foreach para identificar el TIPO de mensaje
        foreach ($mensajes as $mensaje) : // foreach para identificar el mensaje.
?> 

    <div class=" alerta <?php echo $key; ?> ">
        <?php echo $mensaje; ?>
    </div>

<?php 
        endforeach;
    endforeach;
?>