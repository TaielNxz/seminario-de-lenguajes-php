import axios from "axios";
import React, { useEffect , useState } from "react";
import { useNavigate } from "react-router-dom";

// const urlCreate = "http://localhost:90/pedidos/create";
// const urlSelect ="http://localhost:90/itemsmenu";
const urlCreate = "http://localhost:8000/pedidos/create";
const urlSelect ="http://localhost:8000/itemsmenu";

function NewPedido() {

    // Estado para los datos de los Pedidos
    const [pedidos, setPedidos] = useState([]);
    
    // Estado para la informacion del nuevo pedido
    const [newPedido, setNewPedido] = useState({
        "idItemMenu": null,
        "nromesa": null,
        "comentarios": null
    });

    // Funcion para obtener los pedidos
    const getPedidos = () => {
        axios.get(urlSelect)
        .then( res => setPedidos(res.data))
        .catch( err => alert(err.response.data));
    }

    // Llamar a la función getPedidos() cuando se renderiza el componente por primera vez.
    useEffect(() => {
        getPedidos();
    }, []);

    // Instancio el Navigate para poder redireccionar
    const navigate = useNavigate();

    const handleItemMenu = e => setNewPedido({ ...newPedido, idItemMenu: e.target.value })
    const handleNumeroMesa= e => setNewPedido({ ...newPedido, nromesa: e.target.value })
    const handleComenatiors = e => setNewPedido({ ...newPedido, comentarios: e.target.value })
    const handleNewPedido = e => {

        // Evitar que se recargue la pagina
        e.preventDefault();

        axios.post(`${urlCreate}` , newPedido)
             .then( res => {
                alert(res.data);
                navigate('/PedidosPage');
             })
             .catch( err => alert(err.response.data));
    }


    return(
        <main>
            <form className="formulario-alta" onSubmit={handleNewPedido}>
                <fieldset>
                    <div className="campo">
                        <label>Item Menú - Comida: </label>
                        <select name="idItemMenu" onChange={handleItemMenu} defaultValue=""> 
                            <option value="" disabled hidden>Seleccione un item</option>
                            <optgroup label="comidas">
                                {
                                    pedidos.filter((pedido) => pedido.tipo === "COMIDA")
                                        .map((pedido) => ( 
                                        <option key={pedido.id} value={pedido.id} >{pedido.nombre}</option>
                                    ))
                                }
                            </optgroup>
                                <optgroup label="bebidas">
                                {
                                    pedidos.filter((pedido) => pedido.tipo === "BEBIDA")
                                        .map((pedido) => ( 
                                        <option key={pedido.id} value={pedido.id} >{pedido.nombre}</option>
                                    ))
                                }
                            </optgroup>                  
                        </select>
                    </div>

                    <div className="campo">
                        <label>Número de Mesa: </label>
                        <select onChange={handleNumeroMesa} defaultValue="">
                                <option value="" disabled hidden>Seleccione mesa</option>
                                <option value="1">Mesa 1</option>
                                <option value="2">Mesa 2</option>
                                <option value="3">Mesa 3</option>
                                <option value="4">Mesa 4</option>
                                <option value="5">Mesa 5</option>
                        </select>
                    </div>

                    <div className="campo">
                        <label>Nota de Pedido: </label>
                        <textarea
                        onChange={handleComenatiors}></textarea>
                    </div>

                    <div className="campo">
                        <input type="submit"/>
                    </div>
                </fieldset>
            </form>
        </main>
    );
}

export default NewPedido;