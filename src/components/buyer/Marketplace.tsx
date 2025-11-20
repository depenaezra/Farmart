import { useState } from "react";
import { Search, Filter, SlidersHorizontal } from "lucide-react";
import { Page } from "../../App";
import { ImageWithFallback } from "../figma/ImageWithFallback";

interface MarketplaceProps {
  navigate: (page: Page) => void;
  onViewProduct: (productId: string) => void;
}

const mockProducts = [
  { id: "1", name: "Fresh Tomatoes", price: 80, unit: "kilo", farmer: "Juan Santos", cooperative: "Nasugbu Farmers Coop", stock: "50 kg", category: "vegetables", location: "Brgy. Aga" },
  { id: "2", name: "Organic Lettuce", price: 60, unit: "kilo", farmer: "Maria Cruz", cooperative: "Green Valley Coop", stock: "30 kg", category: "vegetables", location: "Brgy. Wawa" },
  { id: "3", name: "Native Corn", price: 45, unit: "kilo", farmer: "Pedro Reyes", cooperative: "Batangas Corn Growers", stock: "100 kg", category: "grains", location: "Brgy. Lumbangan" },
  { id: "4", name: "Banana Lakatan", price: 70, unit: "kilo", farmer: "Rosa Garcia", cooperative: "Nasugbu Farmers Coop", stock: "80 kg", category: "fruits", location: "Brgy. Poblacion" },
  { id: "5", name: "Eggplant", price: 55, unit: "kilo", farmer: "Juan Santos", cooperative: "Nasugbu Farmers Coop", stock: "40 kg", category: "vegetables", location: "Brgy. Aga" },
  { id: "6", name: "Pineapple", price: 90, unit: "piece", farmer: "Ana Bautista", cooperative: "Green Valley Coop", stock: "25 pieces", category: "fruits", location: "Brgy. Mataas na Pulo" }
];

export default function Marketplace({ navigate, onViewProduct }: MarketplaceProps) {
  const [searchTerm, setSearchTerm] = useState("");
  const [selectedCategory, setSelectedCategory] = useState("all");
  const [showFilters, setShowFilters] = useState(false);
  const [priceRange, setPriceRange] = useState({ min: "", max: "" });

  const filteredProducts = mockProducts.filter(product => {
    const matchesSearch = product.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         product.farmer.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesCategory = selectedCategory === "all" || product.category === selectedCategory;
    const matchesPrice = (!priceRange.min || product.price >= Number(priceRange.min)) &&
                        (!priceRange.max || product.price <= Number(priceRange.max));
    return matchesSearch && matchesCategory && matchesPrice;
  });

  return (
    <div className="py-8 bg-neutral-50 min-h-screen">
      <div className="container">
        {/* Header */}
        <div className="mb-8">
          <h1 className="mb-2">Marketplace</h1>
          <p className="text-neutral-600">Browse fresh produce from local Nasugbu farmers</p>
        </div>

        {/* Search and Filters */}
        <div className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-4 mb-6">
          <div className="flex flex-col md:flex-row gap-4">
            <div className="flex-1 relative">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-400" />
              <input
                type="text"
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                placeholder="Search products or farmers..."
                className="w-full pl-12 pr-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
              />
            </div>

            <button
              onClick={() => setShowFilters(!showFilters)}
              className="flex items-center justify-center gap-2 px-6 py-3 border-2 border-neutral-300 hover:border-primary rounded-lg transition-colors"
            >
              <SlidersHorizontal className="w-5 h-5" />
              <span>Filters</span>
            </button>
          </div>

          {showFilters && (
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 pt-4 border-t border-neutral-200">
              <div>
                <label className="block mb-2">Category</label>
                <select
                  value={selectedCategory}
                  onChange={(e) => setSelectedCategory(e.target.value)}
                  className="w-full px-4 py-2 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                >
                  <option value="all">All Categories</option>
                  <option value="vegetables">Vegetables</option>
                  <option value="fruits">Fruits</option>
                  <option value="grains">Grains & Cereals</option>
                </select>
              </div>

              <div>
                <label className="block mb-2">Min Price (₱)</label>
                <input
                  type="number"
                  value={priceRange.min}
                  onChange={(e) => setPriceRange({ ...priceRange, min: e.target.value })}
                  placeholder="0"
                  className="w-full px-4 py-2 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                />
              </div>

              <div>
                <label className="block mb-2">Max Price (₱)</label>
                <input
                  type="number"
                  value={priceRange.max}
                  onChange={(e) => setPriceRange({ ...priceRange, max: e.target.value })}
                  placeholder="1000"
                  className="w-full px-4 py-2 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-primary"
                />
              </div>
            </div>
          )}
        </div>

        {/* Quick Category Filters */}
        <div className="flex gap-2 mb-6 overflow-x-auto pb-2">
          <button
            onClick={() => setSelectedCategory("all")}
            className={`px-6 py-2 rounded-full whitespace-nowrap transition-colors ${
              selectedCategory === "all"
                ? "bg-primary text-white"
                : "bg-white text-neutral-700 border-2 border-neutral-200 hover:border-primary"
            }`}
          >
            All
          </button>
          <button
            onClick={() => setSelectedCategory("vegetables")}
            className={`px-6 py-2 rounded-full whitespace-nowrap transition-colors ${
              selectedCategory === "vegetables"
                ? "bg-primary text-white"
                : "bg-white text-neutral-700 border-2 border-neutral-200 hover:border-primary"
            }`}
          >
            🥬 Vegetables
          </button>
          <button
            onClick={() => setSelectedCategory("fruits")}
            className={`px-6 py-2 rounded-full whitespace-nowrap transition-colors ${
              selectedCategory === "fruits"
                ? "bg-primary text-white"
                : "bg-white text-neutral-700 border-2 border-neutral-200 hover:border-primary"
            }`}
          >
            🍌 Fruits
          </button>
          <button
            onClick={() => setSelectedCategory("grains")}
            className={`px-6 py-2 rounded-full whitespace-nowrap transition-colors ${
              selectedCategory === "grains"
                ? "bg-primary text-white"
                : "bg-white text-neutral-700 border-2 border-neutral-200 hover:border-primary"
            }`}
          >
            🌾 Grains
          </button>
        </div>

        {/* Product Grid */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          {filteredProducts.map((product) => (
            <div
              key={product.id}
              onClick={() => onViewProduct(product.id)}
              className="bg-white rounded-xl shadow-md border-2 border-neutral-200 hover:border-primary hover:shadow-lg transition-all overflow-hidden cursor-pointer group"
            >
              <div className="relative h-48">
                <ImageWithFallback
                  src={`https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=400&h=300&fit=crop&q=80`}
                  alt={product.name}
                  className="w-full h-full object-cover group-hover:scale-105 transition-transform"
                />
                <div className="absolute top-2 right-2 bg-success text-white px-3 py-1 rounded-full" style={{ fontSize: "0.875rem" }}>
                  Available
                </div>
              </div>

              <div className="p-4">
                <h3 className="mb-2">{product.name}</h3>
                
                <div className="flex items-baseline gap-2 mb-3">
                  <span className="text-primary" style={{ fontSize: "1.5rem", fontWeight: "600" }}>
                    ₱{product.price}
                  </span>
                  <span className="text-neutral-500">per {product.unit}</span>
                </div>
                
                <div className="space-y-1 text-neutral-600 mb-3" style={{ fontSize: "0.875rem" }}>
                  <p>👨‍🌾 {product.farmer}</p>
                  <p>🏢 {product.cooperative}</p>
                  <p>📍 {product.location}</p>
                  <p className="text-success">📦 {product.stock} available</p>
                </div>

                <button className="w-full py-2 bg-primary text-white hover:bg-primary-hover rounded-lg transition-colors">
                  View Details
                </button>
              </div>
            </div>
          ))}
        </div>

        {filteredProducts.length === 0 && (
          <div className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-12 text-center">
            <p className="text-neutral-600 mb-2">No products found</p>
            <p className="text-neutral-500" style={{ fontSize: "0.875rem" }}>
              Try adjusting your search or filters
            </p>
          </div>
        )}
      </div>
    </div>
  );
}
