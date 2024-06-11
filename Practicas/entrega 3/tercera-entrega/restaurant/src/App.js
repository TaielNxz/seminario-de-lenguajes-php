import './App.css';
import HeaderComponent from "./components/HeaderComponent";
import FooterComponent from "./components/FooterComponent";
import NavBarComponent from './components/NavBarComponent';
import RouterConfig from "./routes/RouterConfig";

function App() {
  return (
    <>
      <div>
        <HeaderComponent />
        <NavBarComponent />
        <RouterConfig />
        <FooterComponent />
      </div>
    </>
  );
}

export default App;