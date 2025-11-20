import { useState } from "react";
import { ArrowLeft, Check, X, Eye } from "lucide-react";
import { Page } from "../../App";
import { ImageWithFallback } from "../figma/ImageWithFallback";

interface ProductModerationProps {
  navigate: (page: Page) => void;
}

const mockProducts = [
  { id: "1", name: "Fresh Tomatoes", farmer: "Juan Santos", price: 80, status: "Pending", date: "Nov 19, 2024" },
  { id: "2", name: "Organic Lettuce", farmer: "Maria Cruz", price: 60, status: "Pending", date: "Nov 19, 2024" },
  { id: "3", name: "Native Corn", farmer: "Pedro Reyes", price: 45, status: "Approved", date: "Nov 18, 2024" },
  { id: "4", name: "Banana Lakatan", farmer: "Rosa Garcia", price: 70, status: "Rejected", date: "Nov 17, 2024" }
];

export default function ProductModeration({ navigate }: ProductModerationProps) {
  const [statusFilter, setStatusFilter] = useState<"all" | "pending" | "approved" | "rejected">("pending");

  const filteredProducts = mockProducts.filter(product => {
    if (statusFilter === "all") return true;
    return product.status.toLowerCase() === statusFilter;
  });

  const handleApprove = (productId: string, productName: string) => {
    alert(`Product "${productName}" approved!`);
  };

  const handleReject = (productId: string, productName: string) => {
    if (confirm(`Are you sure you want to reject "${productName}"?`)) {
      alert(`Product "${productName}" rejected.`);
    }
  };

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

        <h1 className="mb-8">Product Moderation</h1>

        {/* Status Filters */}
        <div className="flex gap-2 mb-6 overflow-x-auto pb-2">
          <button
            onClick={() => setStatusFilter("pending")}
            className={`px-6 py-2 rounded-full whitespace-nowrap transition-colors ${
              statusFilter === "pending"
                ? "bg-warning text-white"
                : "bg-white text-neutral-700 border-2 border-neutral-200 hover:border-warning"
            }`}
          >
            Pending Review
          </button>
          <button
            onClick={() => setStatusFilter("approved")}
            className={`px-6 py-2 rounded-full whitespace-nowrap transition-colors ${
              statusFilter === "approved"
                ? "bg-success text-white"
                : "bg-white text-neutral-700 border-2 border-neutral-200 hover:border-success"
            }`}
          >
            Approved
          </button>
          <button
            onClick={() => setStatusFilter("rejected")}
            className={`px-6 py-2 rounded-full whitespace-nowrap transition-colors ${
              statusFilter === "rejected"
                ? "bg-error text-white"
                : "bg-white text-neutral-700 border-2 border-neutral-200 hover:border-error"
            }`}
          >
            Rejected
          </button>
          <button
            onClick={() => setStatusFilter("all")}
            className={`px-6 py-2 rounded-full whitespace-nowrap transition-colors ${
              statusFilter === "all"
                ? "bg-primary text-white"
                : "bg-white text-neutral-700 border-2 border-neutral-200 hover:border-primary"
            }`}
          >
            All Products
          </button>
        </div>

        {/* Products Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {filteredProducts.map((product) => (
            <div
              key={product.id}
              className="bg-white rounded-xl shadow-md border-2 border-neutral-200 overflow-hidden"
            >
              <div className="relative h-48">
                <ImageWithFallback
                  src={`https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=400&h=300&fit=crop&q=80`}
                  alt={product.name}
                  className="w-full h-full object-cover"
                />
                <span className={`absolute top-2 right-2 px-3 py-1 rounded-full text-white ${
                  product.status === "Pending" ? "bg-warning" :
                  product.status === "Approved" ? "bg-success" : "bg-error"
                }`} style={{ fontSize: "0.875rem" }}>
                  {product.status}
                </span>
              </div>

              <div className="p-4">
                <h3 className="mb-2">{product.name}</h3>
                <div className="text-neutral-600 mb-3" style={{ fontSize: "0.875rem" }}>
                  <p>👨‍🌾 {product.farmer}</p>
                  <p>💰 ₱{product.price} per kilo</p>
                  <p>📅 Posted: {product.date}</p>
                </div>

                {product.status === "Pending" ? (
                  <div className="flex gap-2">
                    <button
                      onClick={() => handleApprove(product.id, product.name)}
                      className="flex-1 flex items-center justify-center gap-2 py-2 bg-success text-white hover:bg-green-700 rounded-lg transition-colors"
                    >
                      <Check className="w-4 h-4" />
                      <span>Approve</span>
                    </button>
                    <button
                      onClick={() => handleReject(product.id, product.name)}
                      className="flex items-center justify-center px-3 py-2 bg-error text-white hover:bg-red-700 rounded-lg transition-colors"
                    >
                      <X className="w-4 h-4" />
                    </button>
                  </div>
                ) : (
                  <button className="w-full flex items-center justify-center gap-2 py-2 border-2 border-neutral-300 text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors">
                    <Eye className="w-4 h-4" />
                    <span>View Details</span>
                  </button>
                )}
              </div>
            </div>
          ))}
        </div>

        {filteredProducts.length === 0 && (
          <div className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-12 text-center">
            <p className="text-neutral-600">No {statusFilter} products found</p>
          </div>
        )}
      </div>
    </div>
  );
}
