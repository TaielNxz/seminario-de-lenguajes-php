import {BrowserRouter, Route, Routes} from "react-router-dom";
import {paths} from "../config/paths";
import ItemsPage from "../pages/items/itemsPage";
import PedidosPage from "../pages/pedidos/pedidosPage";
import EditItem from "../pages/items/editItem";
import NewPedido from "../pages/pedidos/newPedido";
import NewItem from "../pages/items/newItem";
//Acordarse que el nombre de los import debe estar sí o sí en mayuscula para que funcionen

const RouterConfig = () =>{
    return(
        <BrowserRouter>
            <Routes>
                <Route path={paths.ITEMS} element={<ItemsPage />}/>
                <Route path={paths.PEDIDOS} element={<PedidosPage />}/>
                <Route path={paths.NEWPEDIDO} element={<NewPedido />}/>
                <Route path={paths.NEWITEM} element={<NewItem />}/>
                <Route path={paths.EDITITEM} element={<EditItem />}/>
            </Routes>
        </BrowserRouter>
    )
}

export default RouterConfig;