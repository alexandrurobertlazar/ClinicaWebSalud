function eliminarEspecialista(idesp,idpac) {
    if(!confirm("¿Desea borrar a este especialista?")) {
        return;
    }
    let data = {"idesp": idesp,"idpac": idpac};
    $.ajax({
       url: "borrar_especialista.php",
       type: "POST",
       data: JSON.stringify(data),
       contentType: "application/json;charset=utf-8",
       dataType: "json",
       success: function(res){
          if(res.deleted){
            var fila=document.querySelector('#fila'+idesp); //Se usa id por la clausura
            fila.parentNode.removeChild(fila); //;
          }else{
             alert('Error borrando:'+res.message);
          }
       },
       error: function(res){
          alert('Error:'+res);
       }
    });
}

function checkNewMedicalRecord(e) {
    var lenAsunto = document.forms["MedicalRecordForm"]["asunto"].value.length;
    var lenDesc = document.forms["MedicalRecordForm"]["descripcion"].value.length;
    if (lenAsunto < 1 || lenAsunto > 32) {
        document.getElementById('errorAddHistorial').innerHTML = "El asunto debe tener entre 1 y 32 caracteres!";
        return false;
    }
    if (lenDesc < 12 || lenDesc > 5000) {
        document.getElementById('errorAddHistorial').innerHTML = "Error! La longitud de la descripción debe estar entre 12 y 5000 caracteres!";
        return false;
    }
    return true;
}

var timeout = [];

function searchPaciente(userid){
    /** AJAX **/
    timeout.forEach(function(t){
        window.clearTimeout(t);
    });
    
    timeout.push(window.setTimeout(function() {
        var table = $("pacTable");
        let txt = $("#searchPacienteForm").val();
        if (txt.length == 1) return;
        let data = {"userid": userid,"nombre": txt};
        $.ajax({
           url: "filtrar_pacientes.php",
           type: "POST",
           data: JSON.stringify(data),
           contentType: "application/json;charset=utf-8",
           dataType: "json",
           success: function(res){
              var table2 = $("#pacTable tbody")[0];
              var tableContent = $("#pacTable tbody tr")[0].innerHTML;
              res.datos.forEach(function(resRow){
                 var row = `<tr>
                 <td>${resRow.nombre}</td>
                 <td>${resRow.email}</td>
                 <td><a href='historialpaciente.php?id=${resRow.id}'>Ver historial</td>
                 </tr>`;
                tableContent += row;
              });
            table2.innerHTML = tableContent;
           },
           error: function(res){
              alert('Error:'+res.message);
           }
        });
    }, 100));
}