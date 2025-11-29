import { Page } from "../../App";

interface BuyerInventoryProps {
  navigate: (page: Page) => void;
}

const mockProducts = [
  { id: "1", name: "Fresh Tomatoes", category: "Vegetables", price: 80, unit: "kilo", stock: 50, status: "available", added: "Nov 18, 2024", location: "Brgy. Aga" },
  { id: "2", name: "Organic Lettuce", category: "Vegetables", price: 60, unit: "kilo", stock: 30, status: "available", added: "Nov 17, 2024", location: "Brgy. Wawa" },
  { id: "3", name: "Native Corn", category: "Grains", price: 45, unit: "kilo", stock: 0, status: "out-of-stock", added: "Nov 15, 2024", location: "Brgy. Lumbangan" }
];

export default function BuyerInventory({ navigate }: BuyerInventoryProps) {
  return (
    <div className="py-8 bg-neutral-50 min-h-screen">
      <div className="container">
        <div className="mb-8 flex items-center justify-between">
          <div>
            <h1 className="mb-2">My Listings</h1>
            <p className="text-neutral-600">Keep your selling inventory updated</p>
          </div>

          <button
            onClick={() => navigate("buyer-add-product")}
            className="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors"
          >
            Add Product
          </button>
        </div>

        {mockProducts.length === 0 ? (
          <div className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-12 text-center">
            <p className="text-neutral-600 mb-2">No listings yet</p>
            <p className="text-neutral-500" style={{ fontSize: "0.875rem" }}>
              Start by adding a product you would like to sell
            </p>
            <button
              onClick={() => navigate("buyer-add-product")}
              className="mt-4 px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors"
            >
              Add Your First Product
            </button>
          </div>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {mockProducts.map((product) => (
              <div
                key={product.id}
                className="bg-white rounded-xl shadow-md border-2 border-neutral-200 overflow-hidden"
              >
                <div className="p-4">
                  <div className="flex items-start justify-between mb-3">
                    <div>
                      <h3 className="text-lg font-semibold">{product.name}</h3>
                      <span className="inline-block px-3 py-1 rounded-full text-xs bg-primary/10 text-primary">
                        {product.category}
                      </span>
                    </div>
                    <span
                      className={`px-3 py-1 rounded-full text-xs ${
                        product.status === "available" ? "bg-success/10 text-success" : "bg-error/10 text-error"
                      }`}
                    >
                      {product.status === "available" ? "Available" : "Out of stock"}
                    </span>
                  </div>

                  <div className="mb-4">
                    <p className="text-primary text-2xl font-bold">
                      ₱{product.price}
                      <span className="text-neutral-500 text-base font-normal"> / {product.unit}</span>
                    </p>
                    <p className="text-neutral-600 text-sm mt-1 flex items-center gap-2">
                      <span>📍</span>
                      {product.location}
                    </p>
                  </div>

                  <div className="flex items-center justify-between mb-4 text-sm text-neutral-600">
                    <span>Stock: <strong>{product.stock}</strong> {product.unit}</span>
                    <span>Added: {product.added}</span>
                  </div>

                  <div className="flex gap-3">
                    <button className="flex-1 py-2 border-2 border-neutral-300 rounded-lg hover:bg-neutral-100">
                      Edit
                    </button>
                    <button className="flex-1 py-2 border-2 border-error text-error rounded-lg hover:bg-error hover:text-white transition-colors">
                      Remove
                    </button>
                  </div>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}


