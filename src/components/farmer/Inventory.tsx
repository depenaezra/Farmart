import { useState } from "react";
import { ArrowLeft, Edit, Trash2, Plus, Search } from "lucide-react";
import { Page } from "../../App";
import { ImageWithFallback } from "../figma/ImageWithFallback";

interface InventoryProps {
  navigate: (page: Page) => void;
}

const mockProducts = [
  { id: "1", name: "Fresh Tomatoes", category: "Vegetables", price: 80, unit: "kilo", stock: 50, status: "Available", image: "tomatoes" },
  { id: "2", name: "Organic Lettuce", category: "Vegetables", price: 60, unit: "kilo", stock: 30, status: "Available", image: "lettuce" },
  { id: "3", name: "Native Corn", category: "Grains", price: 45, unit: "kilo", stock: 100, status: "Available", image: "corn" },
  { id: "4", name: "Banana Lakatan", category: "Fruits", price: 70, unit: "kilo", stock: 5, status: "Low Stock", image: "banana" },
  { id: "5", name: "Eggplant", category: "Vegetables", price: 55, unit: "kilo", stock: 0, status: "Out of Stock", image: "eggplant" }
];

export default function Inventory({ navigate }: InventoryProps) {
  const [searchTerm, setSearchTerm] = useState("");
  const [products] = useState(mockProducts);

  const filteredProducts = products.filter(product =>
    product.name.toLowerCase().includes(searchTerm.toLowerCase())
  );

  const getStatusColor = (status: string) => {
    if (status === "Available") return "bg-success";
    if (status === "Low Stock") return "bg-warning";
    return "bg-error";
  };

  return (
    <div className="py-8 bg-neutral-50 min-h-screen">
      <div className="container">
        {/* Header */}
        <div className="mb-8">
          <button
            onClick={() => navigate("farmer-dashboard")}
            className="flex items-center gap-2 text-neutral-600 hover:text-primary mb-4"
          >
            <ArrowLeft className="w-5 h-5" />
            <span>Back to Dashboard</span>
          </button>
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <h1>Product Inventory</h1>
              <p className="text-neutral-600">Manage your products and stock levels</p>
            </div>
            <button
              onClick={() => navigate("add-product")}
              className="flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white hover:bg-primary-hover rounded-lg transition-colors"
            >
              <Plus className="w-5 h-5" />
              <span>Add Product</span>
            </button>
          </div>
        </div>

        {/* Search */}
        <div className="mb-6">
          <div className="relative max-w-md">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-400" />
            <input
              type="text"
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              placeholder="Search products..."
              className="w-full pl-12 pr-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
            />
          </div>
        </div>

        {/* Products Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {filteredProducts.map((product) => (
            <div
              key={product.id}
              className="bg-white rounded-xl shadow-md border-2 border-neutral-200 overflow-hidden hover:border-primary transition-colors"
            >
              <div className="relative h-48">
                <ImageWithFallback
                  src={`https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=400&h=300&fit=crop&q=80`}
                  alt={product.name}
                  className="w-full h-full object-cover"
                />
                <span className={`absolute top-2 right-2 ${getStatusColor(product.status)} text-white px-3 py-1 rounded-full`} style={{ fontSize: "0.875rem" }}>
                  {product.status}
                </span>
              </div>

              <div className="p-4">
                <h3 className="mb-1">{product.name}</h3>
                <p className="text-neutral-600 mb-3" style={{ fontSize: "0.875rem" }}>{product.category}</p>
                
                <div className="flex items-baseline gap-2 mb-3">
                  <span className="text-primary" style={{ fontSize: "1.5rem", fontWeight: "600" }}>
                    ₱{product.price}
                  </span>
                  <span className="text-neutral-500">per {product.unit}</span>
                </div>

                <div className="mb-4">
                  <div className="flex items-center justify-between mb-1">
                    <span className="text-neutral-600" style={{ fontSize: "0.875rem" }}>Stock:</span>
                    <span style={{ fontSize: "0.875rem", fontWeight: "600" }}>{product.stock} {product.unit}s</span>
                  </div>
                </div>

                <div className="flex gap-2">
                  <button className="flex-1 flex items-center justify-center gap-2 py-2 border-2 border-primary text-primary hover:bg-primary hover:text-white rounded-lg transition-colors">
                    <Edit className="w-4 h-4" />
                    <span>Edit</span>
                  </button>
                  <button className="flex items-center justify-center px-3 py-2 border-2 border-error text-error hover:bg-error hover:text-white rounded-lg transition-colors">
                    <Trash2 className="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>
          ))}
        </div>

        {filteredProducts.length === 0 && (
          <div className="text-center py-12">
            <p className="text-neutral-600 mb-4">No products found</p>
            <button
              onClick={() => navigate("add-product")}
              className="px-6 py-3 bg-primary text-white hover:bg-primary-hover rounded-lg transition-colors"
            >
              Add Your First Product
            </button>
          </div>
        )}
      </div>
    </div>
  );
}
