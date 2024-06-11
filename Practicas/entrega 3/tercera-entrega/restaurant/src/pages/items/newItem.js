import axios from "axios";
import React, { useState } from "react";
import { useNavigate } from "react-router-dom";

// const url = "http://localhost:90/itemsmenu/create";
const url = "http://localhost:8000/itemsmenu/create";

function NewItem() {

    // Estado del item
    const [newItem, setNewItem] = useState({
        "nombre": '',
        "precio": '',
        "tipo": null,
        "imagen": null,
        "tipo_imagen": ''
    });

    // Instancio el Navigate para poder redireccionar
    const navigate = useNavigate();

    // handle para los campos
    const handleNombre = e => setNewItem({ ...newItem, nombre: e.target.value });
    const handlePrecio = e => setNewItem({ ...newItem, precio: e.target.value });
    const handleTipo = e => setNewItem({ ...newItem, tipo: e.target.value });
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
                setNewItem( {...newItem, imagen: base64Img , tipo_imagen: tipoImg } );
        }
        
    }
    const handleSubmit = e => {

        // Evita que se recargue la pagina
        e.preventDefault();

        // ver item
        console.log( newItem );

        // envia peticion POST y manda el Item con los datos
        axios.post(`${url}` , newItem)
             .then( res => {
                alert(res.data);
                navigate('/');      
             })
             .catch( err => alert(err.response.data));
    }
    

    return(
        <main>
            <form className="formulario-alta" onSubmit={handleSubmit}>
                <fieldset>
                    <div className="campo">
                        <label>Nombre Item:</label>
                        <input 
                            type="text"
                            maxLength="20" 
                            minLength="1" 
                            onChange={handleNombre}
                        />
                    </div>

                    <div className="campo">
                        <label>Precio Item:</label>
                        <input 
                            type="number"
                            min="0"
                            maxLength="7" 
                            minLength="1" 
                            pattern="[0-9]{10}"
                            onChange={handlePrecio}
                        />
                    </div>


                    <div className="campo">
                        <label>Tipo Item:</label>
                        <select onChange={handleTipo} defaultValue="">
                            <option value="" hidden disabled>Seleccione un tipo</option>
                            <option value="COMIDA">Comida</option>
                            <option value="BEBIDA">Bebida</option>
                        </select>
                    </div>

                    <div className="campo">
                        <label>Imagen Item:</label>
                        <input 
                            type="file"
                            accept="image/jpeg, image/png" 
                            onChange={handleImagen}
                        />
                        { newItem.imagen != null && <img className="imagen_form" src={`data:image/${newItem.tipo_imagen};base64,${newItem.imagen}`} alt={`imagen del pedido ${newItem.nombre}`}></img>}
                    </div>

                    <div className="campo">
                        <input type="submit"/>
                    </div>
                </fieldset>
            </form>
        </main>
    );
}

export default NewItem;