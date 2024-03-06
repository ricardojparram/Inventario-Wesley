$(document).ready(function(){
    let tabla;
    rellenar();
    function rellenar(){
        $.ajax({
            method: "post",
            url: '',
            dataType: 'JSON',
            data: {mostrar: "xd"},
            success(list){
                console.log(list);
                list.forEach(row => {
                    tabla+=`
                    <tr>
                        <td>${row.modulo} </td>
                        <td>${row.nombre}</td>
                        <td>${row.descripcion}</td>
                        <td>${row.fecha}</td>
                    </tr>
                    `;
                });
                $('#tabla tbody').html(tabla);
                tabla = $("#tabla").DataTable({
                    responsive: true,
                    "order": [[ 3, "desc" ]]
                });
            }
        })
    }
})