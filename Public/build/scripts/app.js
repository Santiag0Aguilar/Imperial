import DarkMode from "./components/DarkMode.js";
import BarrasActive from "./components/BarrasActive.js";
import EnlaceNavCursorOver from "./components/EnlaceNavOver.js";
import InitSwiper from "./components/swiper.js";

// Import para nosotros MAP
import initMap from "./components/MapNosotros.js";

//Imports necesarios para las paginas de productos
import InitMeter from "./components/Meter.js";
import FilterActiveMobile from "./components/FilterActive.js";

// IMPORT API
import LoginAPI from "./components/LoginAPI.js";
import AlertasApi from "./components/AlertasAPI.js";
// IMPORT REGISTRO API
import RegistroAPI from "./components/RegistroAPI.js";
import CrearProducto from "./components/CrearAPI.js";
import ActualizarProductoAPI from "./components/ActualizarProductoAPI.js";
import BorrarAPI from "./components/BorrarAPI.js";
//Filtrar
import filtrar from "./components/Filtrar.js";
//Validar usuario en productos
import ValidateUserProductos from "./components/ValidateUserProductos.js";

// Cambiar imagen del producto
import ChangeImageProducto from "./components/ChangeImagenProducto.js";

//Carrito
import agregarAlCarrito from "./components/AgregarAlCarrito.js";
import actualizarIconoCarrito from "./components/ActualizarIconoCarrito.js";

// Eliminar producto del carrito
import DeleteAllCar from "./components/DeleteAllCar.js";
import DeleteOneCar from "./components/DeleteOneCar.js";

// Borrar producto del carrito DOM
import BorrarProductoDOM from "./components/BorrarProductoDOM.js";
import BorrarAllProductosDOM from "./components/BorrarAllProductosDOM.js";

// Recuperar cuenta(cambiar contraseña)
import recuperarCuenta from "./components/RecuperarCuenta.js";

// VerificarToken
import VerificarToken from "./components/VerificarToken.js";

//Cambiar contraseña API
import ChangesPassowrdAPI from "./components/ChangesPasswordAPI.js";

// Form direccion
import FormDireccion from "./components/FormDireccion.js";
//Cargar estados
import CargarEdos from "./components/CargarEdos.js";
// Add municipios dependiendo del estado
import AddMunicipios from "./components/AddMunicipios.js";

//SEPOMEX AUTOCOMPLETAR Direccion
/* import SepoMexAPI from "./components/SepoMexAPI.js";
 */
document.addEventListener("DOMContentLoaded", function () {
  DarkMode();
  BarrasActive();
  EnlaceNavCursorOver();
  InitSwiper();

  // Nosotros
  initMap();

  // Productos
  InitMeter();
  FilterActiveMobile();

  //APi
  LoginAPI();
  AlertasApi();
  RegistroAPI();
  CrearProducto();
  ActualizarProductoAPI();
  BorrarAPI();

  //Validar usuario en productos
  ValidateUserProductos();

  // Cambiar imagen del producto
  ChangeImageProducto();

  // Carrito
  agregarAlCarrito();
  actualizarIconoCarrito();
  // Eliminar producto del carrito
  DeleteAllCar();
  DeleteOneCar();

  // Filtrar productos
  filtrar();
  // Borrar producto del carrito DOM
  BorrarProductoDOM();
  BorrarAllProductosDOM();

  // Recuperar cuenta
  recuperarCuenta();

  // VerificarToken
  VerificarToken();

  //ChangesPassowrdAPI
  ChangesPassowrdAPI();
  // Form direccion
  FormDireccion();
  //Cargar estados
  CargarEdos();

  //ADD municipios dependiendo del estado
  AddMunicipios();
  //SEPOMEX AUTOCOMPLETAR Direccion
  /*   SepoMexAPI(); */
});
