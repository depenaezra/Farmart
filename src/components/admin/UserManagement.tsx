import { useState } from "react";
import { ArrowLeft, Search, UserCheck, UserX, Mail } from "lucide-react";
import { Page } from "../../App";

interface UserManagementProps {
  navigate: (page: Page) => void;
}

const mockUsers = [
  { id: "1", name: "Juan Santos", type: "Farmer", email: "juan@example.com", status: "Active", joined: "Nov 1, 2024", products: 12 },
  { id: "2", name: "Maria Cruz", email: "maria@example.com", type: "Buyer", status: "Active", joined: "Nov 5, 2024", orders: 8 },
  { id: "3", name: "Pedro Reyes", email: "pedro@example.com", type: "Farmer", status: "Active", joined: "Oct 20, 2024", products: 8 },
  { id: "4", name: "Rosa Garcia", email: "rosa@example.com", type: "Buyer", status: "Inactive", joined: "Oct 15, 2024", orders: 3 }
];

export default function UserManagement({ navigate }: UserManagementProps) {
  const [searchTerm, setSearchTerm] = useState("");
  const [userTypeFilter, setUserTypeFilter] = useState<"all" | "farmer" | "buyer">("all");

  const filteredUsers = mockUsers.filter(user => {
    const matchesSearch = user.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         user.email.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesType = userTypeFilter === "all" || user.type.toLowerCase() === userTypeFilter;
    return matchesSearch && matchesType;
  });

  return (
    <div className="py-8 bg-neutral-50 min-h-screen">
      <div className="container">
        <button
          onClick={() => navigate("admin-dashboard")}
          className="flex items-center gap-2 text-neutral-600 hover:text-primary mb-6"
        >
          <ArrowLeft className="w-5 h-5" />
          <span>Back to Dashboard</span>
        </button>

        <h1 className="mb-8">User Management</h1>

        {/* Filters */}
        <div className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-4 mb-6">
          <div className="flex flex-col md:flex-row gap-4">
            <div className="flex-1 relative">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-400" />
              <input
                type="text"
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                placeholder="Search users..."
                className="w-full pl-12 pr-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
              />
            </div>

            <div className="flex gap-2">
              <button
                onClick={() => setUserTypeFilter("all")}
                className={`px-6 py-2 rounded-lg transition-colors ${
                  userTypeFilter === "all"
                    ? "bg-primary text-white"
                    : "bg-neutral-100 text-neutral-700 hover:bg-neutral-200"
                }`}
              >
                All Users
              </button>
              <button
                onClick={() => setUserTypeFilter("farmer")}
                className={`px-6 py-2 rounded-lg transition-colors ${
                  userTypeFilter === "farmer"
                    ? "bg-primary text-white"
                    : "bg-neutral-100 text-neutral-700 hover:bg-neutral-200"
                }`}
              >
                Farmers
              </button>
              <button
                onClick={() => setUserTypeFilter("buyer")}
                className={`px-6 py-2 rounded-lg transition-colors ${
                  userTypeFilter === "buyer"
                    ? "bg-primary text-white"
                    : "bg-neutral-100 text-neutral-700 hover:bg-neutral-200"
                }`}
              >
                Buyers
              </button>
            </div>
          </div>
        </div>

        {/* Users Table */}
        <div className="bg-white rounded-xl shadow-md border-2 border-neutral-200 overflow-hidden">
          <div className="overflow-x-auto">
            <table className="w-full">
              <thead className="bg-neutral-100 border-b-2 border-neutral-200">
                <tr>
                  <th className="text-left px-6 py-4" style={{ fontWeight: "600" }}>User</th>
                  <th className="text-left px-6 py-4" style={{ fontWeight: "600" }}>Type</th>
                  <th className="text-left px-6 py-4" style={{ fontWeight: "600" }}>Status</th>
                  <th className="text-left px-6 py-4" style={{ fontWeight: "600" }}>Joined</th>
                  <th className="text-left px-6 py-4" style={{ fontWeight: "600" }}>Activity</th>
                  <th className="text-left px-6 py-4" style={{ fontWeight: "600" }}>Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-neutral-200">
                {filteredUsers.map((user) => (
                  <tr key={user.id} className="hover:bg-neutral-50">
                    <td className="px-6 py-4">
                      <div>
                        <div style={{ fontWeight: "600" }}>{user.name}</div>
                        <div className="text-neutral-600" style={{ fontSize: "0.875rem" }}>{user.email}</div>
                      </div>
                    </td>
                    <td className="px-6 py-4">
                      <span className={`px-3 py-1 rounded-full ${
                        user.type === "Farmer" ? "bg-primary/10 text-primary" : "bg-accent/10 text-accent"
                      }`} style={{ fontSize: "0.875rem" }}>
                        {user.type}
                      </span>
                    </td>
                    <td className="px-6 py-4">
                      <span className={`px-3 py-1 rounded-full ${
                        user.status === "Active" ? "bg-success/10 text-success" : "bg-neutral-200 text-neutral-600"
                      }`} style={{ fontSize: "0.875rem" }}>
                        {user.status}
                      </span>
                    </td>
                    <td className="px-6 py-4 text-neutral-600">{user.joined}</td>
                    <td className="px-6 py-4 text-neutral-600">
                      {user.type === "Farmer" ? `${user.products} products` : `${user.orders} orders`}
                    </td>
                    <td className="px-6 py-4">
                      <div className="flex gap-2">
                        <button
                          className="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors"
                          title="Approve"
                        >
                          <UserCheck className="w-5 h-5" />
                        </button>
                        <button
                          className="p-2 text-info hover:bg-info/10 rounded-lg transition-colors"
                          title="Send Message"
                        >
                          <Mail className="w-5 h-5" />
                        </button>
                        <button
                          className="p-2 text-error hover:bg-error/10 rounded-lg transition-colors"
                          title="Suspend"
                        >
                          <UserX className="w-5 h-5" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  );
}
