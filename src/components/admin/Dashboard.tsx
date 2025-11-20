import { Users, Package, ShoppingCart, TrendingUp, Eye } from "lucide-react";
import { Page } from "../../App";

interface AdminDashboardProps {
  navigate: (page: Page) => void;
}

export default function AdminDashboard({ navigate }: AdminDashboardProps) {
  const stats = [
    { title: "Total Farmers", value: "124", icon: Users, color: "bg-primary", change: "+8 this month" },
    { title: "Total Buyers", value: "356", icon: Users, color: "bg-accent", change: "+15 this month" },
    { title: "Active Products", value: "1,248", icon: Package, color: "bg-success", change: "+42 this week" },
    { title: "Total Orders", value: "892", icon: ShoppingCart, color: "bg-info", change: "+23 this week" }
  ];

  const recentActivity = [
    { type: "user", message: "New farmer registered: Juan Dela Cruz", time: "5 min ago" },
    { type: "product", message: "Product posted: Fresh Tomatoes by Maria Santos", time: "15 min ago" },
    { type: "order", message: "Order completed: ORD-001", time: "1 hour ago" },
    { type: "user", message: "New buyer registered: Pedro Reyes", time: "2 hours ago" }
  ];

  const topProducts = [
    { name: "Fresh Tomatoes", views: 450, sales: 89 },
    { name: "Organic Lettuce", views: 380, sales: 67 },
    { name: "Native Corn", views: 320, sales: 54 },
    { name: "Banana Lakatan", views: 290, sales: 48 }
  ];

  return (
    <div className="py-8 bg-neutral-50 min-h-screen">
      <div className="container">
        <h1 className="mb-2">Admin Dashboard</h1>
        <p className="text-neutral-600 mb-8">System overview and management</p>

        {/* Quick Actions */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
          <button
            onClick={() => navigate("user-management")}
            className="flex items-center gap-3 p-6 bg-white hover:bg-neutral-100 border-2 border-neutral-200 hover:border-primary rounded-xl shadow-md transition-all"
          >
            <Users className="w-8 h-8 text-primary" />
            <div className="text-left">
              <div style={{ fontSize: "1.125rem", fontWeight: "600" }}>User Management</div>
              <div style={{ fontSize: "0.875rem" }} className="text-neutral-600">Manage users</div>
            </div>
          </button>

          <button
            onClick={() => navigate("product-moderation")}
            className="flex items-center gap-3 p-6 bg-white hover:bg-neutral-100 border-2 border-neutral-200 hover:border-primary rounded-xl shadow-md transition-all"
          >
            <Package className="w-8 h-8 text-success" />
            <div className="text-left">
              <div style={{ fontSize: "1.125rem", fontWeight: "600" }}>Product Moderation</div>
              <div style={{ fontSize: "0.875rem" }} className="text-neutral-600">Review products</div>
            </div>
          </button>

          <button
            onClick={() => navigate("post-announcement")}
            className="flex items-center gap-3 p-6 bg-white hover:bg-neutral-100 border-2 border-neutral-200 hover:border-primary rounded-xl shadow-md transition-all"
          >
            <TrendingUp className="w-8 h-8 text-accent" />
            <div className="text-left">
              <div style={{ fontSize: "1.125rem", fontWeight: "600" }}>Post Announcement</div>
              <div style={{ fontSize: "0.875rem" }} className="text-neutral-600">Create announcement</div>
            </div>
          </button>

          <button className="flex items-center gap-3 p-6 bg-white hover:bg-neutral-100 border-2 border-neutral-200 hover:border-primary rounded-xl shadow-md transition-all">
            <ShoppingCart className="w-8 h-8 text-info" />
            <div className="text-left">
              <div style={{ fontSize: "1.125rem", fontWeight: "600" }}>View Reports</div>
              <div style={{ fontSize: "0.875rem" }} className="text-neutral-600">Analytics</div>
            </div>
          </button>
        </div>

        {/* Statistics */}
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
          {/* Recent Activity */}
          <div className="lg:col-span-2 bg-white rounded-xl shadow-md border-2 border-neutral-200 p-6">
            <h3 className="mb-6">Recent Activity</h3>
            <div className="space-y-4">
              {recentActivity.map((activity, index) => (
                <div key={index} className="flex items-start gap-4 p-4 bg-neutral-50 rounded-lg">
                  <div className="w-10 h-10 bg-primary rounded-full flex items-center justify-center flex-shrink-0">
                    <span className="text-white" style={{ fontSize: "1.25rem" }}>
                      {activity.type === "user" ? "👤" : activity.type === "product" ? "📦" : "🛒"}
                    </span>
                  </div>
                  <div className="flex-1">
                    <p className="text-neutral-700 mb-1">{activity.message}</p>
                    <p className="text-neutral-500" style={{ fontSize: "0.875rem" }}>{activity.time}</p>
                  </div>
                </div>
              ))}
            </div>
          </div>

          {/* Top Products */}
          <div className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-6">
            <h3 className="mb-6">Top Products</h3>
            <div className="space-y-4">
              {topProducts.map((product, index) => (
                <div key={index} className="pb-4 border-b border-neutral-200 last:border-0">
                  <h4 className="mb-2">{product.name}</h4>
                  <div className="space-y-1 text-neutral-600" style={{ fontSize: "0.875rem" }}>
                    <div className="flex items-center justify-between">
                      <span className="flex items-center gap-1">
                        <Eye className="w-4 h-4" />
                        Views
                      </span>
                      <span style={{ fontWeight: "600" }}>{product.views}</span>
                    </div>
                    <div className="flex items-center justify-between">
                      <span>Sales</span>
                      <span style={{ fontWeight: "600" }} className="text-primary">{product.sales}</span>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
