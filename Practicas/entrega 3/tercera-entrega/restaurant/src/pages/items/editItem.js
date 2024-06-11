import axios from "axios";
import React, { useState } from "react";
import { useParams, useLocation, useNavigate } from "react-router-dom";

// const urlSelect="http://localhost:90/itemsmenu";
const urlEdit = "http://localhost:8000/itemsmenu/update/"

function EditItem() {

    // Utilizamos el navigate para redireccionar a otras rutas
    const navigate = useNavigate();

    // el useParams() devuelve un objeto que contiene los valores de los parámetros de la ruta actual
    // desestructiramos el 'id' de la ruta actual
    const { id } = useParams()

    // asigna la variable location a un objeto que representa la ubicación actual de la página.
    // El objeto location tiene varias propioedades, entre estas esta el 'state':
    const location = useLocation();
    // desestructurar el item del objeto location.state
    const { item } = location.state;

    // Agrego los datos del item a editar
    const [itemEdit, setItemEdit] = useState({
        id: item.id,
        nombre: item.nombre,
        precio: item.precio,
        tipo: item.tipo,
        imagen: item.imagen,
        tipo_imagen: item.tipo_imagen,
    });


    const handleNombre = e => setItemEdit({ ...itemEdit, nombre: e.target.value });
    const handlePrecio = e => setItemEdit({ ...itemEdit, precio: e.target.value });
    const handleTipo = e => setItemEdit({ ...itemEdit, tipo: e.target.value });
    const handleImagen = e => {
        // Obtener imagen
        let img = e.target.files[0];
        let tipoImg = e.target.files[0].type.split('/')[1];

        if ( img.size > 2000000 ) {
            alert("El tamaño de la imagen no debe ser mayor a 2MB");
            return;
        }

        // Hacer el cambio a base 64
        var reader = new FileReader();
        reader.readAsDataURL( img );
        reader.onload = () => {
            // el "reader.result" devuelve esto --> "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD//gA7Q1JFQVRPUjogZ2QtanBlZyB2MS4..."
            // usamos el split para partir el string a partir de la coma (,): 
            // reader.result.split(',')[0] --> data:image/jpeg;base64
            // reader.result.split(',')[1] --> /9j/4AAQSkZJRgABAQEAYABgAAD//gA7Q1JFQVRPUjogZ2QtanBlZyB2MS4...
            let base64Img = reader.result.split(',')[1];

            // guardar imagen convertida en base64 y el tipo de imagen
            setItemEdit({ ...itemEdit, imagen: base64Img , tipo_imagen: tipoImg });
        }
    }
    const handleEdit = e => {

        // Evitar que se recargue la pagina
        e.preventDefault();

        // Peticion PUT
        axios.put(`${urlEdit}${id}`, itemEdit)
            .then( res =>{ 
                alert(res.data);
                navigate('/');
            })
            .catch(err => alert(err.response.data));
    }


    return (
        <main>
            <form className="formulario-alta" onSubmit={handleEdit}>
                <fieldset>
                     <div className="campo">
                        <label>Nombre Item: </label>
                        <input 
                            type="text" 
                            maxLength="20" 
                            minLength="1" 
                            value={itemEdit.nombre} 
                            onChange={handleNombre}
                        />
                    </div>

                    <div className="campo">
                        <label>Precio Item: </label>
                        <input 
                            type="number"
                            min="0"
                            maxLength="7" 
                            minLength="1" 
                            pattern="[0-9]{10}"
                            value={itemEdit.precio}
                            onChange={handlePrecio}
                        />
                    </div>

                    <div className="campo">
                        <label>Tipo Item: </label>
                        <select onChange={handleTipo} value={itemEdit.tipo}>
                            <option value="COMIDA">Comida</option>
                            <option value="BEBIDA">Bebida</option>
                        </select>
                    </div>

                    <div className="campo">
                        <label>Imagen Item:</label>
                        <input 
                            type="file" 
                            id="imagen" 
                            accept="image/jpeg, image/png" 
                            onChange={handleImagen}
                        />
                        { item.imagen != null && <img className="imagen_form" src={`data:image/${itemEdit.tipo_imagen};base64,${itemEdit.imagen}`} alt={`imagen del pedido ${itemEdit.nombre}`}></img>}
                    </div>


                    <div className="campo">
                        <input id="procesar" type="submit" value="Guardar modificaciones"/>
                    </div>
                </fieldset>
            </form>
        </main>
    );
}

export default EditItem;