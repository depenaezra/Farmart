import { Package, ShoppingCart, CheckCircle, Plus, Inbox, TrendingUp, AlertCircle } from "lucide-react";
import { Page } from "../../App";

interface DashboardProps {
  navigate: (page: Page) => void;
  userName: string;
}

export default function Dashboard({ navigate, userName }: DashboardProps) {
  const stats = [
    {
      title: "Total Products",
      value: "12",
      icon: Package,
      color: "bg-primary",
      change: "+2 this week"
    },
    {
      title: "Pending Orders",
      value: "5",
      icon: ShoppingCart,
      color: "bg-warning",
      change: "Needs attention"
    },
    {
      title: "Completed Orders",
      value: "38",
      icon: CheckCircle,
      color: "bg-success",
      change: "+8 this month"
    },
    {
      title: "Total Sales",
      value: "₱24,500",
      icon: TrendingUp,
      color: "bg-accent",
      change: "+15% vs last month"
    }
  ];

  const recentOrders = [
    { id: "ORD-001", buyer: "Maria Santos", product: "Tomatoes", quantity: "10 kg", status: "Pending", date: "Nov 19, 2024" },
    { id: "ORD-002", buyer: "Juan Cruz", product: "Lettuce", quantity: "5 kg", status: "Pending", date: "Nov 19, 2024" },
    { id: "ORD-003", buyer: "Rosa Garcia", product: "Corn", quantity: "20 kg", status: "Processing", date: "Nov 18, 2024" }
  ];

  const announcements = [
    { title: "🌧️ Weather Alert", message: "Heavy rain expected this weekend. Secure your crops.", type: "warning" },
    { title: "📢 New Feature", message: "You can now add multiple product photos!", type: "info" }
  ];

  return (
    <div className="py-8 bg-neutral-50 min-h-screen">
      <div className="container">
        {/* Welcome Section */}
        <div className="mb-8">
          <h1 className="mb-2">Welcome back, {userName}!</h1>
          <p className="text-neutral-600">Here's what's happening with your farm today</p>
        </div>

        {/* Quick Actions */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
          <button
            onClick={() => navigate("add-product")}
            className="flex items-center gap-3 p-6 bg-primary text-white hover:bg-primary-hover rounded-xl shadow-md transition-colors"
          >
            <Plus className="w-8 h-8" />
            <div className="text-left">
              <div style={{ fontSize: "1.125rem", fontWeight: "600" }}>Add Product</div>
              <div style={{ fontSize: "0.875rem" }} className="opacity-90">Post new item</div>
            </div>
          </button>

          <button
            onClick={() => navigate("inventory")}
            className="flex items-center gap-3 p-6 bg-white hover:bg-neutral-100 border-2 border-neutral-200 rounded-xl shadow-md transition-colors"
          >
            <Package className="w-8 h-8 text-primary" />
            <div className="text-left">
              <div style={{ fontSize: "1.125rem", fontWeight: "600" }}>Manage Inventory</div>
              <div style={{ fontSize: "0.875rem" }} className="text-neutral-600">View all products</div>
            </div>
          </button>

          <button
            onClick={() => navigate("farmer-orders")}
            className="flex items-center gap-3 p-6 bg-white hover:bg-neutral-100 border-2 border-neutral-200 rounded-xl shadow-md transition-colors relative"
          >
            <ShoppingCart className="w-8 h-8 text-accent" />
            <div className="text-left">
              <div style={{ fontSize: "1.125rem", fontWeight: "600" }}>View Orders</div>
              <div style={{ fontSize: "0.875rem" }} className="text-neutral-600">Manage orders</div>
            </div>
            <span className="absolute top-2 right-2 bg-warning text-white px-2 py-1 rounded-full" style={{ fontSize: "0.75rem" }}>
              5 new
            </span>
          </button>

          <button
            onClick={() => navigate("inbox")}
            className="flex items-center gap-3 p-6 bg-white hover:bg-neutral-100 border-2 border-neutral-200 rounded-xl shadow-md transition-colors"
          >
            <Inbox className="w-8 h-8 text-success" />
            <div className="text-left">
              <div style={{ fontSize: "1.125rem", fontWeight: "600" }}>Messages</div>
              <div style={{ fontSize: "0.875rem" }} className="text-neutral-600">Chat with buyers</div>
            </div>
          </button>
        </div>

        {/* Statistics Cards */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          {stats.map((stat, index) => (
            <div key={index} className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-6">
              <div className="flex items-start justify-between mb-4">
                <div className={`${stat.color} w-12 h-12 rounded-lg flex items-center justify-center`}>
                  <stat.icon className="w-6 h-6 text-white" />
                </div>
              </div>
              <div className="text-neutral-600 mb-2" style={{ fontSize: "0.875rem" }}>{stat.title}</div>
              <div style={{ fontSize: "2rem", fontWeight: "700" }} className="text-neutral-900 mb-1">{stat.value}</div>
              <div className="text-neutral-500" style={{ fontSize: "0.875rem" }}>{stat.change}</div>
            </div>
          ))}
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          {/* Recent Orders */}
          <div className="lg:col-span-2 bg-white rounded-xl shadow-md border-2 border-neutral-200 p-6">
            <div className="flex items-center justify-between mb-6">
              <h3>Recent Orders</h3>
              <button
                onClick={() => navigate("farmer-orders")}
                className="text-primary hover:underline"
              >
                View All
              </button>
            </div>

            <div className="space-y-3">
              {recentOrders.map((order) => (
                <div key={order.id} className="p-4 bg-neutral-50 rounded-lg border border-neutral-200 hover:border-primary transition-colors">
                  <div className="flex items-start justify-between mb-2">
                    <div>
                      <div style={{ fontSize: "1rem", fontWeight: "600" }}>{order.id}</div>
                      <div className="text-neutral-600" style={{ fontSize: "0.875rem" }}>👤 {order.buyer}</div>
                    </div>
                    <span className={`px-3 py-1 rounded-full text-white ${
                      order.status === "Pending" ? "bg-warning" : "bg-info"
                    }`} style={{ fontSize: "0.875rem" }}>
                      {order.status}
                    </span>
                  </div>
                  <div className="flex items-center justify-between text-neutral-600" style={{ fontSize: "0.875rem" }}>
                    <span>📦 {order.product} - {order.quantity}</span>
                    <span>{order.date}</span>
                  </div>
                </div>
              ))}
            </div>
          </div>

          {/* Announcements & Alerts */}
          <div className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-6">
            <h3 className="mb-6">Announcements</h3>
            
            <div className="space-y-4">
              {announcements.map((announcement, index) => (
                <div
                  key={index}
                  className={`p-4 rounded-lg border-l-4 ${
                    announcement.type === "warning" ? "bg-warning/10 border-warning" : "bg-info/10 border-info"
                  }`}
                >
                  <div style={{ fontSize: "1rem", fontWeight: "600" }} className="mb-2">
                    {announcement.title}
                  </div>
                  <p className="text-neutral-700" style={{ fontSize: "0.875rem" }}>
                    {announcement.message}
                  </p>
                </div>
              ))}

              <button
                onClick={() => navigate("announcements")}
                className="w-full py-3 text-primary border-2 border-primary hover:bg-primary hover:text-white rounded-lg transition-colors"
              >
                View All Announcements
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
