// Agregar imagenes front end
function cambiarArchivo(event, idImagen, idIcono) {
    var imagen = document.getElementById(idImagen);
    var icono = document.getElementById(idIcono);
    imagen.src = URL.createObjectURL(event.target.files[0]);
    imagen.style.display = "block";
    icono.style.display = "none";
    icono.setAttribute('data-loaded', 'true');
}

function arrastrarSobre(event) {
    event.preventDefault();
}

function soltarArchivo(event, idImagen) {
    event.preventDefault();
    var archivo = event.dataTransfer.files[0];
    var imagen = document.getElementById(idImagen);
    var icono = document.getElementById('icon_' + idImagen);
    if (archivo.type.match('image.*')) {
        var lector = new FileReader();
        lector.onload = function (e) {
            imagen.src = e.target.result;
            imagen.style.display = "block";
            icono.style.display = "none";
        };
        lector.readAsDataURL(archivo);
    }
}

//Validar datos del formulario de registro
function validarFormulario() {
    var nombre = document.getElementById("nombre_apellidos").value.trim();
    var correo = document.getElementById("correo_electronico").value.trim();
    var telefono = document.getElementById("numero_telefono").value.trim();
    var codigoEstudiante = document.getElementById("codigo_estudiante").value.trim();
    var contrasena = document.getElementById("password").value.trim();
    var contrasena2 = document.getElementById("password2").value.trim();

    var errores = "";

    if (nombre === "") {
        errores += "❌El nombre y apellidos son obligatorios.<br>";
    }

    if (correo === "") {
        errores += "❌El correo electrónico es obligatorio.<br>";
    } else if (!correo.endsWith("@alumnos.udg.mx")) {
        errores += "❌El correo electrónico debe terminar con @alumnos.udg.mx<br>";
    }

    if (telefono === "") {
        errores += "❌El número telefónico es obligatorio.<br>";
    }else if (!/^\d{9}$/.test(codigoEstudiante)) {
        errores += "❌El numero de telefono debe ser un número de 10 dígitos.<br>";
    }

    if (codigoEstudiante === "") {
        errores += "❌El código de estudiante es obligatorio.<br>";
    } else if (!/^\d{9}$/.test(codigoEstudiante)) {
        errores += "❌El código de estudiante debe ser un número de 9 dígitos.<br>";
    }

    if (contrasena === "") {
        errores += "❌La contraseña es obligatoria.<br>";
    } else if (contrasena.length < 8) {
        errores += "❌La contraseña debe tener al menos 8 caracteres.<br>";
    }
    if (contrasena !== contrasena2){
        errores += "❌Las contraseñas no coinciden.";
    }



    var fotoPerfil = document.getElementById("foto_perfil").files[0];
    var fotoCredencial = document.getElementById("foto_credencial").files[0];

    if (!fotoPerfil) {
        errores += "❌La foto de perfil es obligatoria.<br>";
    } else if (!esFormatoValido(fotoPerfil)) {
        errores += "❌La foto de perfil debe ser JPG y tener entre 480 Mpx y 790 Mpx, y máximo 2MB de tamaño.<br>";
    }

    if (!fotoCredencial) {
        errores += "❌La foto de la credencial de estudiante es obligatoria.<br>";
    } else if (!esFormatoValido(fotoCredencial)) {
        errores += "❌La foto de la credencial de estudiante debe ser JPG y tener entre 480 Mpx y 790 Mpx, y máximo 2MB de tamaño.<br>";
    }

    if (errores !== "") {
        document.getElementById("Error").innerHTML = errores;
        return false;
    }

    return true;
}

function esFormatoValido(archivo) {
    var permitidos = ["image/jpeg"];
    var anchoMin = 480; // 480 Mpx
    var anchoMax = 790; // 790 Mpx
    var altoMin = 480;
    var altoMax = 790;
    var tamañoMaxBytes = 2 * 1024 * 1024; // 2 MB

    if (!permitidos.includes(archivo.type)) {
        return false;
    }

    var img = new Image();
    img.src = URL.createObjectURL(archivo);
    return new Promise(resolve => {
        img.onload = function() {
            var ancho = this.width;
            var alto = this.height;

            if (ancho < anchoMin || ancho > anchoMax || alto < altoMin || alto > altoMax || archivo.size > tamañoMaxBytes) {
                resolve(false);
            } else {
                resolve(true);
            }
        }
    });
}
