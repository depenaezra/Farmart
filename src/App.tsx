import { useState } from "react";
import Layout from "./components/Layout";
import LandingPage from "./components/landing/LandingPage";
import Login from "./components/auth/Login";
import RegisterFarmer from "./components/auth/RegisterFarmer";
import RegisterBuyer from "./components/auth/RegisterBuyer";
import FarmerDashboard from "./components/farmer/Dashboard";
import AddProduct from "./components/farmer/AddProduct";
import Inventory from "./components/farmer/Inventory";
import FarmerOrders from "./components/farmer/Orders";
import Marketplace from "./components/buyer/Marketplace";
import ProductDetail from "./components/buyer/ProductDetail";
import Cart from "./components/buyer/Cart";
import Checkout from "./components/buyer/Checkout";
import BuyerOrderHistory from "./components/buyer/OrderHistory";
import Inbox from "./components/messaging/Inbox";
import WeatherPage from "./components/weather/WeatherPage";
import Announcements from "./components/announcements/Announcements";
import Forum from "./components/forum/Forum";
import AdminDashboard from "./components/admin/Dashboard";
import UserManagement from "./components/admin/UserManagement";
import ProductModeration from "./components/admin/ProductModeration";
import PostAnnouncement from "./components/admin/PostAnnouncement";

export type UserRole = "guest" | "farmer" | "buyer" | "admin";

export type Page = 
  | "landing"
  | "login"
  | "register-farmer"
  | "register-buyer"
  | "farmer-dashboard"
  | "add-product"
  | "inventory"
  | "farmer-orders"
  | "marketplace"
  | "product-detail"
  | "cart"
  | "checkout"
  | "buyer-orders"
  | "inbox"
  | "weather"
  | "announcements"
  | "forum"
  | "admin-dashboard"
  | "user-management"
  | "product-moderation"
  | "post-announcement";

function App() {
  const [currentPage, setCurrentPage] = useState<Page>("landing");
  const [userRole, setUserRole] = useState<UserRole>("guest");
  const [selectedProductId, setSelectedProductId] = useState<string | null>(null);
  const [cartItems, setCartItems] = useState<any[]>([]);
  const [userName, setUserName] = useState<string>("");

  const navigate = (page: Page) => {
    setCurrentPage(page);
    window.scrollTo(0, 0);
  };

  const handleLogin = (role: UserRole, name: string) => {
    setUserRole(role);
    setUserName(name);
    
    if (role === "farmer") {
      navigate("farmer-dashboard");
    } else if (role === "buyer") {
      navigate("marketplace");
    } else if (role === "admin") {
      navigate("admin-dashboard");
    }
  };

  const handleLogout = () => {
    setUserRole("guest");
    setUserName("");
    navigate("landing");
  };

  const handleViewProduct = (productId: string) => {
    setSelectedProductId(productId);
    navigate("product-detail");
  };

  const handleAddToCart = (product: any) => {
    setCartItems([...cartItems, { ...product, cartId: Date.now() }]);
  };

  const renderPage = () => {
    switch (currentPage) {
      case "landing":
        return <LandingPage navigate={navigate} />;
      case "login":
        return <Login navigate={navigate} onLogin={handleLogin} />;
      case "register-farmer":
        return <RegisterFarmer navigate={navigate} />;
      case "register-buyer":
        return <RegisterBuyer navigate={navigate} />;
      case "farmer-dashboard":
        return <FarmerDashboard navigate={navigate} userName={userName} />;
      case "add-product":
        return <AddProduct navigate={navigate} />;
      case "inventory":
        return <Inventory navigate={navigate} />;
      case "farmer-orders":
        return <FarmerOrders navigate={navigate} />;
      case "marketplace":
        return <Marketplace navigate={navigate} onViewProduct={handleViewProduct} />;
      case "product-detail":
        return <ProductDetail navigate={navigate} productId={selectedProductId} onAddToCart={handleAddToCart} />;
      case "cart":
        return <Cart navigate={navigate} cartItems={cartItems} setCartItems={setCartItems} />;
      case "checkout":
        return <Checkout navigate={navigate} cartItems={cartItems} setCartItems={setCartItems} />;
      case "buyer-orders":
        return <BuyerOrderHistory navigate={navigate} />;
      case "inbox":
        return <Inbox navigate={navigate} userRole={userRole} />;
      case "weather":
        return <WeatherPage navigate={navigate} />;
      case "announcements":
        return <Announcements navigate={navigate} userRole={userRole} />;
      case "forum":
        return <Forum navigate={navigate} userRole={userRole} />;
      case "admin-dashboard":
        return <AdminDashboard navigate={navigate} />;
      case "user-management":
        return <UserManagement navigate={navigate} />;
      case "product-moderation":
        return <ProductModeration navigate={navigate} />;
      case "post-announcement":
        return <PostAnnouncement navigate={navigate} />;
      default:
        return <LandingPage navigate={navigate} />;
    }
  };

  return (
    <Layout 
      navigate={navigate} 
      userRole={userRole} 
      onLogout={handleLogout}
      cartItemCount={cartItems.length}
      userName={userName}
    >
      {renderPage()}
    </Layout>
  );
}

export default App;
