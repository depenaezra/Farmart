import { useState } from "react";
import { ArrowLeft, ShoppingCart, MessageSquare, MapPin, Star } from "lucide-react";
import { Page } from "../../App";
import { ImageWithFallback } from "../figma/ImageWithFallback";

interface ProductDetailProps {
  navigate: (page: Page) => void;
  productId: string | null;
  onAddToCart: (product: any) => void;
}

export default function ProductDetail({ navigate, productId, onAddToCart }: ProductDetailProps) {
  const [quantity, setQuantity] = useState(1);

  // Mock product data
  const product = {
    id: productId || "1",
    name: "Fresh Tomatoes",
    price: 80,
    unit: "kilo",
    stock: 50,
    farmer: "Juan Santos",
    cooperative: "Nasugbu Farmers Coop",
    location: "Brgy. Aga, Nasugbu, Batangas",
    contact: "0917-123-4567",
    description: "Fresh, locally-grown tomatoes. Harvested daily. Perfect for salads, cooking, or making sauces. No pesticides used. Grown using organic farming methods.",
    category: "Vegetables",
    harvestDate: "Nov 18, 2024",
    rating: 4.5,
    reviews: 12
  };

  const handleAddToCart = () => {
    onAddToCart({ ...product, quantity });
    alert(`${quantity} ${product.unit}(s) of ${product.name} added to cart!`);
  };

  return (
    <div className="py-8 bg-neutral-50 min-h-screen">
      <div className="container max-w-6xl">
        <button
          onClick={() => navigate("marketplace")}
          className="flex items-center gap-2 text-neutral-600 hover:text-primary mb-6"
        >
          <ArrowLeft className="w-5 h-5" />
          <span>Back to Marketplace</span>
        </button>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
          {/* Product Images */}
          <div>
            <div className="bg-white rounded-xl shadow-md border-2 border-neutral-200 overflow-hidden mb-4">
              <ImageWithFallback
                src={`https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=600&h=600&fit=crop&q=80`}
                alt={product.name}
                className="w-full h-96 object-cover"
              />
            </div>
            <div className="grid grid-cols-4 gap-2">
              {[1, 2, 3, 4].map((i) => (
                <div key={i} className="bg-white rounded-lg border-2 border-neutral-200 overflow-hidden cursor-pointer hover:border-primary">
                  <ImageWithFallback
                    src={`https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=200&h=200&fit=crop&q=80`}
                    alt={`Thumbnail ${i}`}
                    className="w-full h-20 object-cover"
                  />
                </div>
              ))}
            </div>
          </div>

          {/* Product Details */}
          <div>
            <div className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-6">
              <div className="flex items-start justify-between mb-4">
                <div>
                  <h1 className="mb-2">{product.name}</h1>
                  <div className="flex items-center gap-2 mb-2">
                    <div className="flex items-center gap-1">
                      {[...Array(5)].map((_, i) => (
                        <Star key={i} className={`w-4 h-4 ${i < Math.floor(product.rating) ? "fill-warning text-warning" : "text-neutral-300"}`} />
                      ))}
                    </div>
                    <span className="text-neutral-600" style={{ fontSize: "0.875rem" }}>
                      ({product.reviews} reviews)
                    </span>
                  </div>
                  <span className="inline-block px-3 py-1 bg-success/10 text-success rounded-full" style={{ fontSize: "0.875rem" }}>
                    {product.category}
                  </span>
                </div>
              </div>

              <div className="mb-6">
                <div className="flex items-baseline gap-2 mb-2">
                  <span className="text-primary" style={{ fontSize: "2.5rem", fontWeight: "700" }}>
                    ₱{product.price}
                  </span>
                  <span className="text-neutral-600" style={{ fontSize: "1.25rem" }}>per {product.unit}</span>
                </div>
                <p className="text-success" style={{ fontSize: "1rem", fontWeight: "500" }}>
                  📦 {product.stock} {product.unit}s available
                </p>
              </div>

              <div className="mb-6 pb-6 border-b border-neutral-200">
                <h4 className="mb-3">Description</h4>
                <p className="text-neutral-700">{product.description}</p>
                <div className="mt-3 text-neutral-600" style={{ fontSize: "0.875rem" }}>
                  <p>🌱 Harvest Date: {product.harvestDate}</p>
                </div>
              </div>

              <div className="mb-6 pb-6 border-b border-neutral-200">
                <h4 className="mb-3">Farmer Information</h4>
                <div className="space-y-2 text-neutral-700">
                  <p>👨‍🌾 {product.farmer}</p>
                  <p>🏢 {product.cooperative}</p>
                  <p><MapPin className="inline w-4 h-4 mr-1" />{product.location}</p>
                  <p>📞 {product.contact}</p>
                </div>
              </div>

              <div className="mb-6">
                <label className="block mb-2">Quantity ({product.unit}s)</label>
                <div className="flex items-center gap-4">
                  <button
                    onClick={() => setQuantity(Math.max(1, quantity - 1))}
                    className="w-12 h-12 bg-neutral-200 hover:bg-neutral-300 rounded-lg transition-colors"
                    style={{ fontSize: "1.5rem" }}
                  >
                    -
                  </button>
                  <input
                    type="number"
                    value={quantity}
                    onChange={(e) => setQuantity(Math.max(1, Math.min(product.stock, Number(e.target.value))))}
                    className="w-20 h-12 text-center border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                    style={{ fontSize: "1.25rem", fontWeight: "600" }}
                  />
                  <button
                    onClick={() => setQuantity(Math.min(product.stock, quantity + 1))}
                    className="w-12 h-12 bg-neutral-200 hover:bg-neutral-300 rounded-lg transition-colors"
                    style={{ fontSize: "1.5rem" }}
                  >
                    +
                  </button>
                </div>
                <p className="text-neutral-600 mt-2" style={{ fontSize: "0.875rem" }}>
                  Total: <span style={{ fontSize: "1.25rem", fontWeight: "600" }} className="text-primary">
                    ₱{(product.price * quantity).toFixed(2)}
                  </span>
                </p>
              </div>

              <div className="flex gap-3">
                <button
                  onClick={handleAddToCart}
                  className="flex-1 flex items-center justify-center gap-2 py-4 bg-primary text-white hover:bg-primary-hover rounded-lg transition-colors"
                >
                  <ShoppingCart className="w-5 h-5" />
                  <span>Add to Cart</span>
                </button>
                <button
                  onClick={() => navigate("inbox")}
                  className="flex items-center justify-center gap-2 px-6 py-4 border-2 border-primary text-primary hover:bg-primary hover:text-white rounded-lg transition-colors"
                >
                  <MessageSquare className="w-5 h-5" />
                  <span>Contact</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
