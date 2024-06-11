import axios from "axios";
import React, { useEffect , useState } from "react";
import { Link } from "react-router-dom";

// const urlSelect = "http://localhost:90/pedidos";
// const urlDelete = "http://localhost:90/pedidos/delete/";
const urlSelectPedido = "http://localhost:8000/pedidos";
const urlDeletePedido = "http://localhost:8000/pedidos/delete/";

function PedidosPage() {

  // Estado para la lista de pedidos
  const [pedidos, setPedidos] = useState([]);

  // Funcion para eliminar un pedido
  const eliminarPedido = (id) => {

    // Alerta de confirmacion
    const confirmar = window.confirm("desea eliminar el item?");

    // Si se acepta entonces se procede con el borrado
    if ( confirmar ) {
      axios.delete(`${urlDeletePedido}${id}`)
      .then((res) => {
        alert(res.data);
        setPedidos(pedidos.filter((pedido) => pedido.id !== id));
      })
      .catch( err => alert(err.response.data));
    }
  }
  
  // Mostrar pedidos al renderisar componente
  useEffect( () => {
    axios.get(`${urlSelectPedido}`)
    .then( res => setPedidos(res.data) )
    .catch( err => alert(err.response.data));
  }, []);


  return (
    <main>
      <div className="pedidos">
          <h2>Pedidos Realizados</h2>
      </div>
      <div className="tabla">
        <div className="tabla-contenedor">
            {
              pedidos.map((pedido) => (
                <div className="card" key={pedido.id}>
                  <img src={`data:image/${pedido.tipo_imagen};base64,${pedido.imagen}`} alt={`imagen del item ${pedido.nombre}`}></img>
                  <div className="card-texto">
                    <p className="nombre">{pedido.nombre}</p>
                    <p className="precio">{pedido.precio}</p>
                    <p className="mesa">mesa {pedido.nromesa}</p>
                    <p className="comentarios">{pedido.comentarios}</p>
                    <button key={pedido.id} onClick={ () => eliminarPedido(pedido.id)}>Eliminar</button>
                  </div>
                </div>
              ))
            }
          </div>
        </div>

      <div className="agregar">
      <button>
        <Link to="/NewPedido">Hacer un pedido nuevo</Link>
      </button>
      </div>
    </main>
  );
}

export default PedidosPage;