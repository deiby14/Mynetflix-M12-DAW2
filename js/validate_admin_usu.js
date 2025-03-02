document.addEventListener("DOMContentLoaded", function () {
    // Función genérica para validar un input con una expresión regular y mostrar mensaje de error
    function validateInput(input, regex, errorMessage) {
      const value = input.value.trim();
      let errorElement = input.parentElement.querySelector('.error-message');
  
      // Si no existe el elemento de error, lo creamos
      if (!errorElement) {
        errorElement = document.createElement("div");
        errorElement.classList.add("error-message");
        errorElement.style.fontSize = "0.875rem";
        errorElement.style.marginTop = "0.25rem";
        input.parentElement.appendChild(errorElement);
      }
  
      if (value === "") {
        input.style.border = "2px solid red";
        errorElement.textContent = "Este campo no puede estar vacío.";
        errorElement.style.color = "red";
        return false;
      } else if (!regex.test(value)) {
        input.style.border = "2px solid red";
        errorElement.textContent = errorMessage;
        errorElement.style.color = "red";
        return false;
      } else {
        input.style.border = "2px solid green";
        errorElement.textContent = "";
        return true;
      }
    }
  
    // Función para validar selects (que no queden en opción vacía)
    function validateSelect(select, errorMessage) {
      let errorElement = select.parentElement.querySelector('.error-message');
  
      if (!errorElement) {
        errorElement = document.createElement("div");
        errorElement.classList.add("error-message");
        errorElement.style.fontSize = "0.875rem";
        errorElement.style.marginTop = "0.25rem";
        select.parentElement.appendChild(errorElement);
      }
  
      if (select.value === "") {
        select.style.border = "2px solid red";
        errorElement.textContent = errorMessage;
        errorElement.style.color = "red";
        return false;
      } else {
        select.style.border = "2px solid green";
        errorElement.textContent = "";
        return true;
      }
    }
  
    /* ================= Registro ================= */
    const registerForm = document.querySelector("#registerfrm");
  
    // Validaciones para el formulario de registro
    function validateNombre(input) {
      return validateInput(
        input,
        /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,}$/,
        "Debe contener solo letras y al menos 2 caracteres."
      );
    }
  
    function validateEmail(input) {
      return validateInput(
        input,
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
        "Por favor, introduce un email válido."
      );
    }
  
    function validateContrasena(input) {
      return validateInput(
        input,
        /^.{6,}$/,
        "La contraseña debe tener al menos 6 caracteres."
      );
    }
  
    // Seleccionamos campos del formulario de registro
    const nombreInput = document.querySelector("#nombre");
    const emailInput = document.querySelector("#email");
    const contrasenaInput = document.querySelector("#contrasena");
    const rolSelect = document.querySelector("#es_admin");
    const estadoSelect = document.querySelector("#estado");
  
    if (nombreInput) {
      nombreInput.addEventListener("blur", function () {
        validateNombre(nombreInput);
      });
    }
    if (emailInput) {
      emailInput.addEventListener("blur", function () {
        validateEmail(emailInput);
      });
    }
    if (contrasenaInput) {
      contrasenaInput.addEventListener("blur", function () {
        validateContrasena(contrasenaInput);
      });
    }
    if (rolSelect) {
      rolSelect.addEventListener("change", function () {
        validateSelect(rolSelect, "Debe seleccionar un rol.");
      });
    }
    if (estadoSelect) {
      estadoSelect.addEventListener("change", function () {
        validateSelect(estadoSelect, "Debe seleccionar un estado.");
      });
    }
  
    if (registerForm) {
      registerForm.addEventListener("submit", function (e) {
        let valid = true;
        if (!validateNombre(nombreInput)) valid = false;
        if (!validateEmail(emailInput)) valid = false;
        if (!validateContrasena(contrasenaInput)) valid = false;
        if (!validateSelect(rolSelect, "Debe seleccionar un rol.")) valid = false;
        if (!validateSelect(estadoSelect, "Debe seleccionar un estado.")) valid = false;
        if (!valid) {
          e.preventDefault();
        }
      });
    }
  
    /* ================= Edición ================= */
    const editForm = document.querySelector("#editfrm");
  
    // Para edición, reutilizamos las validaciones de nombre y email.
    // La contraseña se valida solo si se ingresa valor (de lo contrario, se deja en blanco)
    function validateEditNombre(input) {
      return validateInput(
        input,
        /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,}$/,
        "Debe contener solo letras y al menos 2 caracteres."
      );
    }
  
    function validateEditEmail(input) {
      return validateInput(
        input,
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
        "Por favor, introduce un email válido."
      );
    }
  
    function validateEditContrasena(input) {
      const value = input.value.trim();
      if (value === "") {
        // Si está vacío, se considera válido (no se cambia la contraseña)
        input.style.border = "";
        let errorElement = input.parentElement.querySelector('.error-message');
        if (errorElement) {
          errorElement.textContent = "";
        }
        return true;
      } else {
        return validateInput(
          input,
          /^.{6,}$/,
          "La contraseña debe tener al menos 6 caracteres."
        );
      }
    }
  
    // Seleccionamos campos del formulario de edición
    const editNombreInput = document.querySelector("#edit_nombre");
    const editEmailInput = document.querySelector("#edit_email");
    const editContrasenaInput = document.querySelector("#edit_contrasena");
    const editRolSelect = document.querySelector("#edit_es_admin");
    const editEstadoSelect = document.querySelector("#edit_estado");
  
    if (editNombreInput) {
      editNombreInput.addEventListener("blur", function () {
        validateEditNombre(editNombreInput);
      });
    }
    if (editEmailInput) {
      editEmailInput.addEventListener("blur", function () {
        validateEditEmail(editEmailInput);
      });
    }
    if (editContrasenaInput) {
      editContrasenaInput.addEventListener("blur", function () {
        validateEditContrasena(editContrasenaInput);
      });
    }
    if (editRolSelect) {
      editRolSelect.addEventListener("change", function () {
        validateSelect(editRolSelect, "Debe seleccionar un rol.");
      });
    }
    if (editEstadoSelect) {
      editEstadoSelect.addEventListener("change", function () {
        validateSelect(editEstadoSelect, "Debe seleccionar un estado.");
      });
    }
  
    if (editForm) {
      editForm.addEventListener("submit", function (e) {
        let valid = true;
        if (!validateEditNombre(editNombreInput)) valid = false;
        if (!validateEditEmail(editEmailInput)) valid = false;
        if (!validateEditContrasena(editContrasenaInput)) valid = false;
        if (!validateSelect(editRolSelect, "Debe seleccionar un rol.")) valid = false;
        if (!validateSelect(editEstadoSelect, "Debe seleccionar un estado.")) valid = false;
        if (!valid) {
          e.preventDefault();
        }
      });
    }
  });
  