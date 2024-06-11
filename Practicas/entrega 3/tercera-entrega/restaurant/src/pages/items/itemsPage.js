import axios from "axios";
import React, { useEffect , useState } from "react";
import { Link } from "react-router-dom";

// const urlSelect="http://localhost:90/itemsmenu";
// const urlDelete="http://localhost:90/itemsmenu/delete/";
const urlSelectItems="http://localhost:8000/itemsmenu";
const urlDeleteItem="http://localhost:8000/itemsmenu/delete/";


function ItemsPage() {

  // Estado para la lista de items
  const [items, setItems] = useState([]);
  // Estados para los filtros
  const [nombre, setNombre] = useState([]);
  const [tipo, setTipo] = useState([]);
  const [orden, setOrden] = useState([]);

  // Funcion para obtener los items del menu
  const getItems = () =>{
    axios.get(`${urlSelectItems}?tipo=${tipo}&nombre=${nombre}&orden=${orden}`)
    .then( res => {
      setItems(res.data)
    } )
    .catch( err => {
      setItems([]);
      alert(err.response.data);
    } );
  }

  // Funcion para eliminar un item
  const eliminarItem = (id) => {

    // Alerta de confirmacion
    const confirmar = window.confirm("desea eliminar el item?")
    
    // Si se acepta entonces se procede con el borrado
    if ( confirmar ) {
      axios.delete(`${urlDeleteItem}${id}`)
           .then((res) => {
              alert(res.data);
              setItems( items.filter((item) => item.id !== id) );
           })
           .catch( err => alert(err.response.data) );
    }
  }

  // Llamar a la función getItems() cuando se renderiza el componente por primera vez.
  useEffect(() => {
    getItems();
  }, []);

  const handleNombre = e => setNombre(e.target.value);
  const handleTipo = e => setTipo(e.target.value);
  const handleorden = e => setOrden(e.target.value)
  const handleFilter = e => {
    // Evitar que se recargue la pagina
    e.preventDefault();
    // Obtener Items del menu
    getItems();
  };

  return (
    <main>
      <form className="formulario" onSubmit={handleFilter}>
          <fieldset>
          <div className="campo">
                    <label>Nombre: </label>
                    <input 
                      type="text" 
                      value={nombre} 
                      onChange={handleNombre}
                     />   
                </div>

                <div className="campo">
                    <label>Tipo: </label>
                    <select  onChange={handleTipo}>
                        <option value="">Todos</option>
                        <option value="COMIDA">Comida</option>
                        <option value="BEBIDA">Bebida</option>
                    </select>    
                </div>

                <div className="campo">
                    <label>Orden Precio: </label>
                    <select  onChange={handleorden}>
                        <option value="" disabled defaultValue hidden></option>
                        <option value="ASC">Ascendente</option>
                        <option value="DESC">Descendente</option>
                    </select>
                </div>

                <div className="campo">
                    <input 
                      type="submit" 
                      value="Aplicar" 
                    />
                </div>
          </fieldset>
      </form>

      <div className="tabla">
        <div className="tabla-contenedor">
            {
              items.map((item) => (
                <div className="card" key={item.id} >
                  <img src={`data:image/${item.tipo_imagen};base64,${item.imagen}`} alt={`imagen del item ${item.nombre}`}></img>
                  <div className="card-texto">
                    <p className="nombre">{item.nombre}</p>
                    <p className="tipo">{item.tipo}</p>
                    <p className="precio">{item.precio}</p>
                    <div className="components">
                      <button key={item.id} >
                        <Link to={`/EditItem/${item.id}`} state={{ item }}>editar</Link>
                      </button>
                      <button onClick={ () => eliminarItem(item.id) }>
                        Eliminar
                      </button>
                    </div>
                  </div>
                </div>
              ))
            }
        </div>
      </div>
      
      <div  className="agregar">
        <button>
          <Link to="/NewItem">Crear nuevo item</Link>
        </button>
      </div>
    </main>
  );
}

export default ItemsPage;