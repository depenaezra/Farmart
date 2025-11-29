import { useState } from "react";
import { ArrowLeft, Check, X, MessageSquare } from "lucide-react";
import { Page } from "../../App";

interface SellerOrdersProps {
  navigate: (page: Page) => void;
}

const mockOrders = [
  {
    id: "SELL-001",
    buyer: "Maria Santos",
    contact: "0917-123-4567",
    product: "Banana Lakatan",
    quantity: "15 kg",
    total: "₱1,050",
    status: "Pending",
    date: "Nov 19, 2024, 10:30 AM",
    address: "Brgy. Poblacion, Tuy"
  },
  {
    id: "SELL-002",
    buyer: "Juan Cruz",
    contact: "0918-234-5678",
    product: "Native Corn",
    quantity: "20 kg",
    total: "₱900",
    status: "Processing",
    date: "Nov 18, 2024, 09:15 AM",
    address: "Brgy. Aga, Nasugbu"
  },
  {
    id: "SELL-003",
    buyer: "Rosa Garcia",
    contact: "0919-345-6789",
    product: "Fresh Tomatoes",
    quantity: "10 kg",
    total: "₱800",
    status: "Completed",
    date: "Nov 17, 2024, 03:45 PM",
    address: "Brgy. Wawa, Nasugbu"
  }
];

export default function SellerOrders({ navigate }: SellerOrdersProps) {
  const [selectedTab, setSelectedTab] = useState<"pending" | "processing" | "completed">("pending");
  const [orders] = useState(mockOrders);

  const filteredOrders = orders.filter(order => {
    if (selectedTab === "pending") return order.status === "Pending";
    if (selectedTab === "processing") return order.status === "Processing";
    return order.status === "Completed";
  });

  const handleConfirm = (orderId: string) => {
    alert(`Order ${orderId} confirmed!`);
  };

  const handleReject = (orderId: string) => {
    if (confirm(`Are you sure you want to reject order ${orderId}?`)) {
      alert(`Order ${orderId} rejected.`);
    }
  };

  const handleComplete = (orderId: string) => {
    alert(`Order ${orderId} marked as completed!`);
  };

  return (
    <div className="py-8 bg-neutral-50 min-h-screen">
      <div className="container max-w-5xl">
        <div className="mb-8">
          <button
            onClick={() => navigate("buyer-dashboard")}
            className="flex items-center gap-2 text-neutral-600 hover:text-primary mb-4"
          >
            <ArrowLeft className="w-5 h-5" />
            <span>Back to Dashboard</span>
          </button>
          <h1>Sales Orders</h1>
          <p className="text-neutral-600">Manage the orders placed on your listings</p>
        </div>

        <div className="flex gap-2 mb-6 overflow-x-auto">
          <button
            onClick={() => setSelectedTab("pending")}
            className={`px-6 py-3 rounded-lg transition-colors whitespace-nowrap ${
              selectedTab === "pending"
                ? "bg-warning text-white"
                : "bg-white text-neutral-700 border-2 border-neutral-200 hover:border-warning"
            }`}
          >
            Pending Orders
          </button>
          <button
            onClick={() => setSelectedTab("processing")}
            className={`px-6 py-3 rounded-lg transition-colors whitespace-nowrap ${
              selectedTab === "processing"
                ? "bg-info text-white"
                : "bg-white text-neutral-700 border-2 border-neutral-200 hover:border-info"
            }`}
          >
            Processing
          </button>
          <button
            onClick={() => setSelectedTab("completed")}
            className={`px-6 py-3 rounded-lg transition-colors whitespace-nowrap ${
              selectedTab === "completed"
                ? "bg-success text-white"
                : "bg-white text-neutral-700 border-2 border-neutral-200 hover:border-success"
            }`}
          >
            Completed
          </button>
        </div>

        <div className="space-y-4">
          {filteredOrders.map((order) => (
            <div
              key={order.id}
              className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-6"
            >
              <div className="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-4">
                <div className="flex-1">
                  <div className="flex items-center gap-3 mb-2">
                    <h3>{order.id}</h3>
                    <span className={`px-3 py-1 rounded-full text-white ${
                      order.status === "Pending" ? "bg-warning" :
                      order.status === "Processing" ? "bg-info" : "bg-success"
                    }`} style={{ fontSize: "0.875rem" }}>
                      {order.status}
                    </span>
                  </div>
                  <p className="text-neutral-600" style={{ fontSize: "0.875rem" }}>{order.date}</p>
                </div>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                  <p className="text-neutral-600 mb-1" style={{ fontSize: "0.875rem" }}>Buyer Information:</p>
                  <p style={{ fontSize: "1rem", fontWeight: "600" }}>👤 {order.buyer}</p>
                  <p className="text-neutral-600">📞 {order.contact}</p>
                  <p className="text-neutral-600" style={{ fontSize: "0.875rem" }}>📍 {order.address}</p>
                </div>

                <div>
                  <p className="text-neutral-600 mb-1" style={{ fontSize: "0.875rem" }}>Order Details:</p>
                  <p style={{ fontSize: "1rem", fontWeight: "600" }}>📦 {order.product}</p>
                  <p className="text-neutral-600">Quantity: {order.quantity}</p>
                  <p className="text-primary" style={{ fontSize: "1.25rem", fontWeight: "600" }}>{order.total}</p>
                </div>
              </div>

              <div className="flex flex-wrap gap-2 pt-4 border-t border-neutral-200">
                {order.status === "Pending" && (
                  <>
                    <button
                      onClick={() => handleConfirm(order.id)}
                      className="flex items-center gap-2 px-4 py-2 bg-success text-white hover:bg-green-700 rounded-lg transition-colors"
                    >
                      <Check className="w-4 h-4" />
                      <span>Confirm Order</span>
                    </button>
                    <button
                      onClick={() => handleReject(order.id)}
                      className="flex items-center gap-2 px-4 py-2 bg-error text-white hover:bg-red-700 rounded-lg transition-colors"
                    >
                      <X className="w-4 h-4" />
                      <span>Reject</span>
                    </button>
                  </>
                )}

                {order.status === "Processing" && (
                  <button
                    onClick={() => handleComplete(order.id)}
                    className="flex items-center gap-2 px-4 py-2 bg-primary text-white hover:bg-primary-hover rounded-lg transition-colors"
                  >
                    <Check className="w-4 h-4" />
                    <span>Mark as Completed</span>
                  </button>
                )}

                <button
                  onClick={() => navigate("inbox")}
                  className="flex items-center gap-2 px-4 py-2 border-2 border-primary text-primary hover:bg-primary hover:text-white rounded-lg transition-colors"
                >
                  <MessageSquare className="w-4 h-4" />
                  <span>Message Buyer</span>
                </button>
              </div>
            </div>
          ))}
        </div>

        {filteredOrders.length === 0 && (
          <div className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-12 text-center">
            <p className="text-neutral-600 mb-2">No {selectedTab} orders</p>
            <p className="text-neutral-500" style={{ fontSize: "0.875rem" }}>
              Orders placed on your listings will appear here
            </p>
          </div>
        )}
      </div>
    </div>
  );
}


