if (document.querySelector("#formLogin")) {
  let formLogin = document.querySelector("#formLogin");

  formLogin.addEventListener("submit", (e) => {
    e.preventDefault();

    let strTelefono = document.querySelector("#txtTelefono").value;
    let strPassword = document.querySelector("#txtPassword").value;

    if (strTelefono == "" || strPassword == "") {
      Swal.fire({
        title: "Ingresar",
        text: "Por favor, escribe usuario y contraseña",
        icon: "error",
      });
      return false;
    } else {
      frmData = new FormData(formLogin);
      fetch(base_url + "/login/loginUser", {
        method: "POST",
        body: frmData,
      }).then((res) => {
        if (res.status == 200) {
          res.json().then((data) => {
            if (data.status) {
              window.location = base_url + "/dashboard";
              console.log(data.msg);
            } else {
                
              Swal.fire({
                title: data.status ? "Correcto" : "Error",
                text: data.msg,
                icon: data.status ? "success" : "error",
              });
              document.querySelector("#txtPassword").value = "";
            }
          });
        } else {
          Swal.fire({
            title: "Atencion",
            text: "Error en el proceso, intente mas tarde",
            icon: "error",
          });
        }
      });

      return false;
    }
  });
}
