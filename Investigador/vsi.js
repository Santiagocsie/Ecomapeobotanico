// Verificar si la cookie está activa al cargar la página
window.onload = function() {
    verificarCookie();
};

function verificarCookie(nombre) {
    // Obtener todas las cookies del navegador
    var cookies = document.cookie.split(';');

    // Buscar la cookie específica por su nombre
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        while (cookie.charAt(0) === ' ') {
            cookie = cookie.substring(1);
        }
        if (cookie.indexOf(nombre + '=') === 0) {
            // La cookie está activa
            return true;
        }
    }

    // La cookie no está activa
    return false;
}

// Verificar si la cookie 'investigador_email' está activa
var investigadorCookieActiva = verificarCookie('investigador_email');

if (investigadorCookieActiva) {
    console.log('La cookie "investigador_email" está activa.');
    // Mantener al usuario en la página actual al intentar retroceder
    window.history.pushState(null, null, window.location.href);
    window.onpopstate = function(event) {
        window.history.go(1); // Redirigir al usuario nuevamente al login
    };
} else {
    console.log('La cookie "investigador_email" no está activa.');	
    // Redirigir al usuario a la página de inicio de sesión del investigador
    window.location.href = '../LoginAdm/login.html';
    window.history.replaceState({}, document.title, "../LoginInv/login.html");
}

