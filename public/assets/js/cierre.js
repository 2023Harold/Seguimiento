$(document).ready(function() {
    var x=document.getElementById('tiempo').value;
   x = (x*60000)-11000; 
   // Se ejecuta esta funcion pasado el tiempo estimado. 
    setIdleTimeout(x, function () {
        console.log('hola1');
    // se muestra el mensaje con el contador en retroseso.    
        $('#conteo_cierre').click();
        updateClock(); 
    },function(){ // Se ejecuta esta funcion cuando la session o el tiempo ha expirado y se realiza alguna accion con el teclado o mouse.
        location.reload(); // se recarga la pagina cuando se da click con el mouse.
    }); 
});

/**** Función para mostrar el mensaje con el conteo en retroceso para el cierre de sesion por tiempo de inactividad****/
var totalTime = 10;
function updateClock() {   
    console.log(totalTime);
    document.getElementById('countdown').innerHTML = 'En '+totalTime+ ' segundos tu sesion va ha expirar';
    if(totalTime==0){
      document.getElementById('countdown').innerHTML = 'Tu sesion ha expirado.';
      console.log('Final');
    }else{
      totalTime=totalTime-1;
      setTimeout("updateClock()",1000);
    }
  } 

/***** Funcion que obtiene el tiempo de inactividad del ususario en el sistema. ****/
function setIdleTimeout(millis, onIdle, onUnidle) {
    var timeout = 0;
    startTimer();
   /**** Función para inicializar el tiempo de la sesion****/
    function startTimer() {
        timeout = setTimeout(onExpires, millis);
        document.addEventListener("mousedown", onActivity);
        document.addEventListener("keypress", onActivity);
    }
    
    function onExpires() {
        timeout = 0;
        onIdle();
    }
   /**** Función para actualizar el tiempo de la sesion al momento presionar alguna tecla o dar click al mouse ****/
    function onActivity() {
        if (timeout) clearTimeout(timeout);
        else onUnidle();
        //Dado que el mouse se presiona, desactivamos nuestros ganchos de eventos durante 1 segundo.
        document.removeEventListener("mousedown", onActivity);
        document.removeEventListener("keypress", onActivity);
        setTimeout(startTimer, 1000);
    }
   }
    