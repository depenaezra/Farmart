import { ArrowLeft, Package } from "lucide-react";
import { Page } from "../../App";

interface OrderHistoryProps {
  navigate: (page: Page) => void;
}

const mockOrders = [
  { id: "ORD-B001", date: "Nov 19, 2024", items: "Tomatoes (10kg), Lettuce (5kg)", total: "₱1,100", status: "Delivered", farmer: "Juan Santos" },
  { id: "ORD-B002", date: "Nov 18, 2024", items: "Corn (20kg)", total: "₱900", status: "In Transit", farmer: "Pedro Reyes" },
  { id: "ORD-B003", date: "Nov 15, 2024", items: "Banana (15kg)", total: "₱1,050", status: "Processing", farmer: "Rosa Garcia" }
];

export default function OrderHistory({ navigate }: OrderHistoryProps) {
  return (
    <div className="py-8 bg-neutral-50 min-h-screen">
      <div className="container max-w-4xl">
        <button
          onClick={() => navigate("marketplace")}
          className="flex items-center gap-2 text-neutral-600 hover:text-primary mb-6"
        >
          <ArrowLeft className="w-5 h-5" />
          <span>Back to Marketplace</span>
        </button>

        <h1 className="mb-8">Order History</h1>

        <div className="space-y-4">
          {mockOrders.map((order) => (
            <div key={order.id} className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-6">
              <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                <div>
                  <h3 className="mb-1">{order.id}</h3>
                  <p className="text-neutral-600" style={{ fontSize: "0.875rem" }}>{order.date}</p>
                </div>
                <span className={`px-4 py-2 rounded-full text-white w-fit ${
                  order.status === "Delivered" ? "bg-success" :
                  order.status === "In Transit" ? "bg-info" : "bg-warning"
                }`}>
                  {order.status}
                </span>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <p className="text-neutral-600 mb-1" style={{ fontSize: "0.875rem" }}>Items:</p>
                  <p>{order.items}</p>
                  <p className="text-neutral-600 mt-2" style={{ fontSize: "0.875rem" }}>Farmer: {order.farmer}</p>
                </div>
                <div className="text-left md:text-right">
                  <p className="text-neutral-600 mb-1" style={{ fontSize: "0.875rem" }}>Total Amount:</p>
                  <p className="text-primary" style={{ fontSize: "1.5rem", fontWeight: "700" }}>{order.total}</p>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
