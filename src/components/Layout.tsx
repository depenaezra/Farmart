import { Menu, X, Home, ShoppingBag, LayoutDashboard, Cloud, Megaphone, MessageSquare, ShoppingCart, User, LogOut, Users } from "lucide-react";
import { useState } from "react";
import { UserRole, Page } from "../App";

interface LayoutProps {
  children: React.ReactNode;
  navigate: (page: Page) => void;
  userRole: UserRole;
  onLogout: () => void;
  cartItemCount: number;
  userName: string;
}

export default function Layout({ children, navigate, userRole, onLogout, cartItemCount, userName }: LayoutProps) {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  const toggleMobileMenu = () => {
    setMobileMenuOpen(!mobileMenuOpen);
  };

  return (
    <div className="min-h-screen flex flex-col bg-neutral-50">
      {/* Navigation Bar */}
      <nav className="bg-white border-b-2 border-primary sticky top-0 z-50 shadow-sm">
        <div className="container">
          <div className="flex items-center justify-between h-16 md:h-20">
            {/* Logo */}
            <button 
              onClick={() => navigate(userRole === "guest" ? "landing" : userRole === "farmer" ? "farmer-dashboard" : userRole === "buyer" ? "marketplace" : "admin-dashboard")}
              className="flex items-center gap-2 hover:opacity-80 transition-opacity"
            >
              <div className="w-10 h-10 md:w-12 md:h-12 bg-primary rounded-lg flex items-center justify-center">
                <span className="text-white">🌾</span>
              </div>
              <div className="flex flex-col">
                <span className="text-primary font-bold" style={{ fontSize: "1.25rem", lineHeight: "1.2" }}>AgriConnect</span>
                <span className="text-neutral-600" style={{ fontSize: "0.75rem", lineHeight: "1" }}>Nasugbu, Batangas</span>
              </div>
            </button>

            {/* Desktop Navigation */}
            <div className="hidden md:flex items-center gap-4">
              <button
                onClick={() => navigate(userRole === "guest" ? "landing" : userRole === "farmer" ? "farmer-dashboard" : userRole === "buyer" ? "marketplace" : "admin-dashboard")}
                className="flex items-center gap-2 px-4 py-2 text-neutral-700 hover:text-primary hover:bg-neutral-100 rounded-lg transition-colors"
              >
                <Home className="w-5 h-5" />
                <span>Home</span>
              </button>

              <button
                onClick={() => navigate("marketplace")}
                className="flex items-center gap-2 px-4 py-2 text-neutral-700 hover:text-primary hover:bg-neutral-100 rounded-lg transition-colors"
              >
                <ShoppingBag className="w-5 h-5" />
                <span>Marketplace</span>
              </button>

              {userRole === "farmer" && (
                <button
                  onClick={() => navigate("farmer-dashboard")}
                  className="flex items-center gap-2 px-4 py-2 text-neutral-700 hover:text-primary hover:bg-neutral-100 rounded-lg transition-colors"
                >
                  <LayoutDashboard className="w-5 h-5" />
                  <span>Dashboard</span>
                </button>
              )}

              {userRole === "admin" && (
                <button
                  onClick={() => navigate("admin-dashboard")}
                  className="flex items-center gap-2 px-4 py-2 text-neutral-700 hover:text-primary hover:bg-neutral-100 rounded-lg transition-colors"
                >
                  <LayoutDashboard className="w-5 h-5" />
                  <span>Admin</span>
                </button>
              )}

              <button
                onClick={() => navigate("weather")}
                className="flex items-center gap-2 px-4 py-2 text-neutral-700 hover:text-primary hover:bg-neutral-100 rounded-lg transition-colors"
              >
                <Cloud className="w-5 h-5" />
                <span>Weather</span>
              </button>

              <button
                onClick={() => navigate("announcements")}
                className="flex items-center gap-2 px-4 py-2 text-neutral-700 hover:text-primary hover:bg-neutral-100 rounded-lg transition-colors"
              >
                <Megaphone className="w-5 h-5" />
                <span>Announcements</span>
              </button>

              {userRole !== "guest" && (
                <>
                  <button
                    onClick={() => navigate("inbox")}
                    className="flex items-center gap-2 px-4 py-2 text-neutral-700 hover:text-primary hover:bg-neutral-100 rounded-lg transition-colors"
                  >
                    <MessageSquare className="w-5 h-5" />
                    <span>Messages</span>
                  </button>

                  {userRole === "buyer" && (
                    <button
                      onClick={() => navigate("cart")}
                      className="relative flex items-center gap-2 px-4 py-2 text-neutral-700 hover:text-primary hover:bg-neutral-100 rounded-lg transition-colors"
                    >
                      <ShoppingCart className="w-5 h-5" />
                      <span>Cart</span>
                      {cartItemCount > 0 && (
                        <span className="absolute -top-1 -right-1 bg-accent text-white text-xs w-6 h-6 rounded-full flex items-center justify-center">
                          {cartItemCount}
                        </span>
                      )}
                    </button>
                  )}
                </>
              )}

              {userRole === "guest" ? (
                <button
                  onClick={() => navigate("login")}
                  className="flex items-center gap-2 px-6 py-2 bg-primary text-white hover:bg-primary-hover rounded-lg transition-colors"
                >
                  <User className="w-5 h-5" />
                  <span>Login</span>
                </button>
              ) : (
                <div className="flex items-center gap-2">
                  <span className="text-neutral-700 px-3 py-2 bg-neutral-100 rounded-lg">
                    {userName || "User"}
                  </span>
                  <button
                    onClick={onLogout}
                    className="flex items-center gap-2 px-4 py-2 text-error hover:bg-red-50 rounded-lg transition-colors"
                  >
                    <LogOut className="w-5 h-5" />
                    <span>Logout</span>
                  </button>
                </div>
              )}
            </div>

            {/* Mobile Menu Button */}
            <button
              onClick={toggleMobileMenu}
              className="md:hidden p-2 text-neutral-700 hover:text-primary"
              aria-label="Toggle menu"
            >
              {mobileMenuOpen ? <X className="w-8 h-8" /> : <Menu className="w-8 h-8" />}
            </button>
          </div>
        </div>

        {/* Mobile Menu Drawer */}
        {mobileMenuOpen && (
          <div className="md:hidden bg-white border-t border-neutral-200 shadow-lg">
            <div className="container py-4 space-y-2">
              <button
                onClick={() => {
                  navigate(userRole === "guest" ? "landing" : userRole === "farmer" ? "farmer-dashboard" : userRole === "buyer" ? "marketplace" : "admin-dashboard");
                  setMobileMenuOpen(false);
                }}
                className="w-full flex items-center gap-3 px-4 py-3 text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors"
              >
                <Home className="w-6 h-6" />
                <span>Home</span>
              </button>

              <button
                onClick={() => {
                  navigate("marketplace");
                  setMobileMenuOpen(false);
                }}
                className="w-full flex items-center gap-3 px-4 py-3 text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors"
              >
                <ShoppingBag className="w-6 h-6" />
                <span>Marketplace</span>
              </button>

              {userRole === "farmer" && (
                <button
                  onClick={() => {
                    navigate("farmer-dashboard");
                    setMobileMenuOpen(false);
                  }}
                  className="w-full flex items-center gap-3 px-4 py-3 text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors"
                >
                  <LayoutDashboard className="w-6 h-6" />
                  <span>Dashboard</span>
                </button>
              )}

              {userRole === "admin" && (
                <button
                  onClick={() => {
                    navigate("admin-dashboard");
                    setMobileMenuOpen(false);
                  }}
                  className="w-full flex items-center gap-3 px-4 py-3 text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors"
                >
                  <Users className="w-6 h-6" />
                  <span>Admin Panel</span>
                </button>
              )}

              <button
                onClick={() => {
                  navigate("weather");
                  setMobileMenuOpen(false);
                }}
                className="w-full flex items-center gap-3 px-4 py-3 text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors"
              >
                <Cloud className="w-6 h-6" />
                <span>Weather</span>
              </button>

              <button
                onClick={() => {
                  navigate("announcements");
                  setMobileMenuOpen(false);
                }}
                className="w-full flex items-center gap-3 px-4 py-3 text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors"
              >
                <Megaphone className="w-6 h-6" />
                <span>Announcements</span>
              </button>

              {userRole !== "guest" && (
                <>
                  <button
                    onClick={() => {
                      navigate("inbox");
                      setMobileMenuOpen(false);
                    }}
                    className="w-full flex items-center gap-3 px-4 py-3 text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors"
                  >
                    <MessageSquare className="w-6 h-6" />
                    <span>Messages</span>
                  </button>

                  {userRole === "buyer" && (
                    <button
                      onClick={() => {
                        navigate("cart");
                        setMobileMenuOpen(false);
                      }}
                      className="w-full flex items-center gap-3 px-4 py-3 text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors relative"
                    >
                      <ShoppingCart className="w-6 h-6" />
                      <span>Cart</span>
                      {cartItemCount > 0 && (
                        <span className="ml-auto bg-accent text-white px-2 py-1 rounded-full" style={{ fontSize: "0.875rem" }}>
                          {cartItemCount}
                        </span>
                      )}
                    </button>
                  )}
                </>
              )}

              <div className="border-t border-neutral-200 pt-2 mt-2">
                {userRole === "guest" ? (
                  <button
                    onClick={() => {
                      navigate("login");
                      setMobileMenuOpen(false);
                    }}
                    className="w-full flex items-center justify-center gap-3 px-4 py-3 bg-primary text-white hover:bg-primary-hover rounded-lg transition-colors"
                  >
                    <User className="w-6 h-6" />
                    <span>Login</span>
                  </button>
                ) : (
                  <>
                    <div className="px-4 py-2 mb-2 bg-neutral-100 rounded-lg">
                      <span className="text-neutral-700">{userName || "User"}</span>
                    </div>
                    <button
                      onClick={() => {
                        onLogout();
                        setMobileMenuOpen(false);
                      }}
                      className="w-full flex items-center justify-center gap-3 px-4 py-3 text-error hover:bg-red-50 rounded-lg transition-colors"
                    >
                      <LogOut className="w-6 h-6" />
                      <span>Logout</span>
                    </button>
                  </>
                )}
              </div>
            </div>
          </div>
        )}
      </nav>

      {/* Main Content */}
      <main className="flex-1">
        {children}
      </main>

      {/* Footer */}
      <footer className="bg-neutral-800 text-white mt-auto">
        <div className="container py-8 md:py-12">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
              <h3 className="text-white mb-4">AgriConnect</h3>
              <p className="text-neutral-300" style={{ fontSize: "1rem" }}>
                Direct marketplace connecting Nasugbu farmers with local buyers and cooperatives.
              </p>
            </div>

            <div>
              <h4 className="text-white mb-4">Quick Links</h4>
              <div className="space-y-2">
                <button
                  onClick={() => navigate("marketplace")}
                  className="block text-neutral-300 hover:text-white transition-colors"
                >
                  Marketplace
                </button>
                <button
                  onClick={() => navigate("weather")}
                  className="block text-neutral-300 hover:text-white transition-colors"
                >
                  Weather Updates
                </button>
                <button
                  onClick={() => navigate("announcements")}
                  className="block text-neutral-300 hover:text-white transition-colors"
                >
                  Announcements
                </button>
                <button
                  onClick={() => navigate("forum")}
                  className="block text-neutral-300 hover:text-white transition-colors"
                >
                  Community Forum
                </button>
              </div>
            </div>

            <div>
              <h4 className="text-white mb-4">Contact</h4>
              <div className="space-y-2 text-neutral-300">
                <p>📞 (043) 123-4567</p>
                <p>📧 info@agriconnect.ph</p>
                <p>📍 Nasugbu, Batangas</p>
              </div>
            </div>
          </div>

          <div className="border-t border-neutral-700 mt-8 pt-8 text-center text-neutral-400">
            <p>&copy; 2024 AgriConnect. All rights reserved.</p>
            <div className="flex justify-center gap-6 mt-4">
              <button className="hover:text-white transition-colors">Privacy Policy</button>
              <button className="hover:text-white transition-colors">Terms of Service</button>
              <button className="hover:text-white transition-colors">About Us</button>
            </div>
          </div>
        </div>
      </footer>
    </div>
  );
}
