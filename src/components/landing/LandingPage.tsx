import { ArrowRight, Cloud, Megaphone, Briefcase, ShoppingBag, TrendingUp, Users, Wheat, Leaf } from "lucide-react";
import { Page } from "../../App";
import { ImageWithFallback } from "../figma/ImageWithFallback";

interface LandingPageProps {
  navigate: (page: Page) => void;
}

// Mock featured products
const featuredProducts = [
  {
    id: "1",
    name: "Fresh Tomatoes",
    price: "₱80",
    unit: "per kilo",
    farmer: "Juan Santos",
    cooperative: "Nasugbu Farmers Coop",
    stock: "50 kg available",
    image: "tomatoes fresh vegetables"
  },
  {
    id: "2",
    name: "Organic Lettuce",
    price: "₱60",
    unit: "per kilo",
    farmer: "Maria Cruz",
    cooperative: "Green Valley Coop",
    stock: "30 kg available",
    image: "lettuce fresh green"
  },
  {
    id: "3",
    name: "Native Corn",
    price: "₱45",
    unit: "per kilo",
    farmer: "Pedro Reyes",
    cooperative: "Batangas Corn Growers",
    stock: "100 kg available",
    image: "corn vegetables yellow"
  },
  {
    id: "4",
    name: "Banana Lakatan",
    price: "₱70",
    unit: "per kilo",
    farmer: "Rosa Garcia",
    cooperative: "Nasugbu Farmers Coop",
    stock: "80 kg available",
    image: "banana yellow fruit"
  }
];

export default function LandingPage({ navigate }: LandingPageProps) {
  return (
    <div className="bg-white">
      {/* Hero Section */}
      <section className="bg-gradient-to-br from-primary to-primary-light text-white py-12 md:py-20">
        <div className="container">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div>
              <h1 className="text-white mb-4" style={{ fontSize: "2.5rem", lineHeight: "1.1" }}>
                Direct Marketplace for Local Farmers
              </h1>
              <p className="text-neutral-100 mb-8" style={{ fontSize: "1.25rem" }}>
                Connecting Nasugbu farmers directly with buyers. Fresh produce, fair prices, strong community.
              </p>
              
              <div className="flex flex-col sm:flex-row gap-4">
                <button
                  onClick={() => navigate("marketplace")}
                  className="flex items-center justify-center gap-2 px-8 py-4 bg-white text-primary hover:bg-neutral-100 rounded-lg transition-colors shadow-lg"
                  style={{ fontSize: "1.25rem" }}
                >
                  <ShoppingBag className="w-6 h-6" />
                  <span>Browse Products</span>
                  <ArrowRight className="w-6 h-6" />
                </button>
                
                <button
                  onClick={() => navigate("register-farmer")}
                  className="flex items-center justify-center gap-2 px-8 py-4 bg-accent text-white hover:bg-accent-hover rounded-lg transition-colors shadow-lg"
                  style={{ fontSize: "1.25rem" }}
                >
                  <Users className="w-6 h-6" />
                  <span>Register as Farmer</span>
                </button>
              </div>
            </div>

            <div className="relative">
              <div className="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border-2 border-white/20">
                <ImageWithFallback
                  src="https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=600&h=400&fit=crop"
                  alt="Filipino farmers"
                  className="w-full h-64 md:h-80 object-cover rounded-xl"
                />
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Quick Access Icons */}
      <section className="py-8 bg-neutral-50 border-b border-neutral-200">
        <div className="container">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
            <button
              onClick={() => navigate("weather")}
              className="flex flex-col items-center gap-3 p-6 bg-white hover:bg-primary hover:text-white rounded-xl shadow-sm border-2 border-neutral-200 hover:border-primary transition-all group"
            >
              <div className="w-16 h-16 bg-primary group-hover:bg-white rounded-full flex items-center justify-center">
                <Cloud className="w-8 h-8 text-white group-hover:text-primary" />
              </div>
              <span style={{ fontSize: "1.125rem" }}>Weather Update</span>
            </button>

            <button
              onClick={() => navigate("announcements")}
              className="flex flex-col items-center gap-3 p-6 bg-white hover:bg-primary hover:text-white rounded-xl shadow-sm border-2 border-neutral-200 hover:border-primary transition-all group"
            >
              <div className="w-16 h-16 bg-accent group-hover:bg-white rounded-full flex items-center justify-center">
                <Megaphone className="w-8 h-8 text-white group-hover:text-accent" />
              </div>
              <span style={{ fontSize: "1.125rem" }}>Announcements</span>
            </button>

            <button
              onClick={() => navigate("announcements")}
              className="flex flex-col items-center gap-3 p-6 bg-white hover:bg-primary hover:text-white rounded-xl shadow-sm border-2 border-neutral-200 hover:border-primary transition-all group"
            >
              <div className="w-16 h-16 bg-secondary group-hover:bg-white rounded-full flex items-center justify-center">
                <Briefcase className="w-8 h-8 text-white group-hover:text-secondary" />
              </div>
              <span style={{ fontSize: "1.125rem" }}>Gov't Programs</span>
            </button>

            <button
              onClick={() => navigate("forum")}
              className="flex flex-col items-center gap-3 p-6 bg-white hover:bg-primary hover:text-white rounded-xl shadow-sm border-2 border-neutral-200 hover:border-primary transition-all group"
            >
              <div className="w-16 h-16 bg-success group-hover:bg-white rounded-full flex items-center justify-center">
                <Users className="w-8 h-8 text-white group-hover:text-success" />
              </div>
              <span style={{ fontSize: "1.125rem" }}>Community Forum</span>
            </button>
          </div>
        </div>
      </section>

      {/* Featured Products */}
      <section className="py-12 md:py-16">
        <div className="container">
          <div className="flex items-center justify-between mb-8">
            <h2>Featured Products</h2>
            <button
              onClick={() => navigate("marketplace")}
              className="flex items-center gap-2 text-primary hover:text-primary-hover"
            >
              <span>View All</span>
              <ArrowRight className="w-5 h-5" />
            </button>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {featuredProducts.map((product) => (
              <div
                key={product.id}
                className="bg-white rounded-xl shadow-md border-2 border-neutral-200 hover:border-primary hover:shadow-lg transition-all overflow-hidden group cursor-pointer"
                onClick={() => navigate("marketplace")}
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
                  <div className="flex items-baseline gap-2 mb-2">
                    <span className="text-primary" style={{ fontSize: "1.5rem", fontWeight: "600" }}>
                      {product.price}
                    </span>
                    <span className="text-neutral-500">{product.unit}</span>
                  </div>
                  
                  <div className="space-y-1 text-neutral-600" style={{ fontSize: "0.875rem" }}>
                    <p>👨‍🌾 {product.farmer}</p>
                    <p>🏢 {product.cooperative}</p>
                    <p className="text-success">📦 {product.stock}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Why AgriConnect */}
      <section className="py-12 md:py-16 bg-neutral-50">
        <div className="container">
          <h2 className="text-center mb-12">Why Choose AgriConnect?</h2>
          
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div className="bg-white p-8 rounded-xl shadow-md border-2 border-neutral-200 text-center">
              <div className="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <TrendingUp className="w-10 h-10 text-primary" />
              </div>
              <h3 className="mb-3">Fair Prices</h3>
              <p className="text-neutral-600">
                Direct connection between farmers and buyers means better prices for everyone. No middlemen, just fair trade.
              </p>
            </div>

            <div className="bg-white p-8 rounded-xl shadow-md border-2 border-neutral-200 text-center">
              <div className="w-20 h-20 bg-success/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <Wheat className="w-10 h-10 text-success" />
              </div>
              <h3 className="mb-3">Fresh Produce</h3>
              <p className="text-neutral-600">
                Get the freshest fruits and vegetables directly from local Nasugbu farms. Quality guaranteed.
              </p>
            </div>

            <div className="bg-white p-8 rounded-xl shadow-md border-2 border-neutral-200 text-center">
              <div className="w-20 h-20 bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <Leaf className="w-10 h-10 text-accent" />
              </div>
              <h3 className="mb-3">Support Local</h3>
              <p className="text-neutral-600">
                Every purchase supports local farmers and strengthens our community. Grow together with Nasugbu.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Call to Action */}
      <section className="py-12 md:py-16 bg-primary text-white">
        <div className="container text-center">
          <h2 className="text-white mb-4">Ready to Get Started?</h2>
          <p className="text-neutral-100 mb-8 max-w-2xl mx-auto" style={{ fontSize: "1.125rem" }}>
            Join AgriConnect today and be part of the growing community of farmers and buyers in Nasugbu.
          </p>
          
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <button
              onClick={() => navigate("register-farmer")}
              className="px-8 py-4 bg-white text-primary hover:bg-neutral-100 rounded-lg transition-colors"
              style={{ fontSize: "1.125rem" }}
            >
              Register as Farmer
            </button>
            <button
              onClick={() => navigate("register-buyer")}
              className="px-8 py-4 bg-accent text-white hover:bg-accent-hover rounded-lg transition-colors"
              style={{ fontSize: "1.125rem" }}
            >
              Register as Buyer
            </button>
          </div>
        </div>
      </section>
    </div>
  );
}
